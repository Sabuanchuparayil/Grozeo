<?php

namespace App\Schedulers\PostingScheduler\Postings;

use Illuminate\Support\Facades\DB;
use App\Schedulers\PostingScheduler\{
    FinascopPosting,
    CostDistribution,
    RestaurantAutoposting,
    AutopostingCalculations
};
use App\Models\ProcessLock;
use Aws\DynamoDb\DynamoDbClient;
use App\Events\SendNotifications;

class AutoPostingNew
{
    public function __invoke()
    {
        try
        {
            $eventMaster = DB::table('finance_event_master')->select('event_ref_id')->orderBy('ExecutionOrder', 'asc')->get();
            $dynamoClient = DynamoDbClient::factory(config('aws.dynamodb'));
            foreach ($eventMaster as $em)
            {
                $params = [
                    'TableName'                 => config('aws.prefix').'named_events',
                    'FilterExpression'          => 'neStatus = :statCond AND finascopEventRefId = :finascopEventRefId', //AND (tstamp < :timeStampDiff) ',
                    'ExpressionAttributeValues' => [
                        ':statCond'             => ['S' => '0'],
                        ':finascopEventRefId'   => ['S' => $em->event_ref_id]
                    ],
                ];
                $eventList = [];
                do
                {
                    $result = $dynamoClient->scan($params);
                    $eventList = array_merge($eventList, $result['Items']);
                    $params['ExclusiveStartKey'] = $result['LastEvaluatedKey'];
                } while (!empty($params['ExclusiveStartKey']));

                array_reverse($eventList);
                
                if(!empty($eventList))
                {
                    $defaultFinance = config('finance.default');
                    $financeClass = config("finance.{$defaultFinance}");
                    $financeObj = new $financeClass();
                    foreach($eventList as $event)
                    {
                        $financeObj->financeAutopostings($event['order_id']['S'], $event['finascopEventRefId']['S']);
                        (new AutopostingCalculations)->addAutopostingDetails($event['order_id']['S'], $event['finascopEventRefId']['S']);
                        // (new CostDistribution)->costDistribution($event['order_id']['S'], $event['finascopEventRefId']['S'], $event['storeGroupId']['S']);
                        (new FinascopPosting)->finascopPosting($event['order_id']['S'], $event['finascopEventRefId']['S'], $event['storeGroupId']['S']);

                        event(new SendNotifications($event['order_id']['S'], $event['finascopEventRefId']['S']));

                        $result = $dynamoClient->updateItem([
                            'TableName'                 => config('aws.prefix').'named_events',
                            'Key'                       => [
                                'uuid'      => $event['uuid'],
                                'tstamp'    => $event['tstamp']
                            ],
                            'ExpressionAttributeNames' => [
                                '#neStatus' => 'neStatus',
                            ],
                            'ExpressionAttributeValues' => [
                                ':neStatus' => ['S' => '1']
                            ],
                            'UpdateExpression'          => 'SET #neStatus=:neStatus'
                        ]);
                    }
                }
            }
            ProcessLock::updateColData("BizAPI_PostingScheduler", 0);
        }
        catch (\Exception $e)
        {
            info("AutoPosting ERROR => ".$e);
            ProcessLock::updateColData("BizAPI_PostingScheduler", 0);
            return [];
        }
    }
}