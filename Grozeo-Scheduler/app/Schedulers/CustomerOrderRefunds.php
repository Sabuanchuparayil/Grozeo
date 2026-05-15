<?php

namespace App\Schedulers;

use Illuminate\Support\Facades\DB;
use App\Models\{
    Order,
    ProcessLock,
    OrderRefunds
};
use App\Events\OrderHistory;
use App\Status\CustomerOrderStatus;

class CustomerOrderRefunds
{
    public function __invoke()
    {
        try
        {
            $refundData = OrderRefunds::where('status', '0')->with('order')->get();
            foreach ($refundData as $ref)
            {
                $order = $ref->order;
                $pg = $ref->payment_gateway;
                $amount = $ref->amount;
                if(($pg != "") && ($amount > 0))
                {
                    $pgClass = config("paymentgateway.{$pg}.class");
                    $pgObj = new $pgClass();
                    $ref->status = '2';
                    $ref->save();

                    $refundUpdate = $pgObj->cancellationRefunds($order->order_group_id, $amount);
                    if($refundUpdate)
                    {
                        $ref->status = '1';
                        $ref->payment_gateway_id = $refundUpdate["id"];
                        $ref->request = $refundUpdate["request"];
                        $ref->response = $refundUpdate["response"];
                        $ref->save();
                        $orderUpdate = Order::where('order_id', $order->order_id)->update([
                            'status_id' => CustomerOrderStatus::REFUND_COMPLETED
                        ]);
                        event(new OrderHistory($order->order_id, CustomerOrderStatus::REFUND_COMPLETED));
                    }
                }
                else
                {
                    $orderUpdate = Order::where('order_id', $order->order_id)->update([
                        'status_id' => CustomerOrderStatus::REFUND_CANCELLED
                    ]);
                    event(new OrderHistory($order->order_id, CustomerOrderStatus::REFUND_CANCELLED));
                    $ref->status = 2;
                    $ref->save();
                }
            }
            ProcessLock::updateColData("BizAPI_CustomerOrderRefunds", 0);
        }
        catch (\Exception $e)
        {
            info("CustomerOrderRefunds ERROR => ".$e->getMessage());
            ProcessLock::updateColData("BizAPI_CustomerOrderRefunds", 0);
        }
    }
}
