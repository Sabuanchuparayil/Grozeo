<?php

namespace App\Console;
use App\Schedulers\{
    AssignOrder,
    RemoveBlockedItems,
    ReAssignOrder,
    BoyStatusChecker,
    OrderStatusUpdate,
    BranchStatusUpdate,
    MerchantSettlements,
    FinanceTransaction,
    ConsignmentTrackingUpdate,
    CreateShippingConsignment,
    CheckOrderTimeout,
    CheckOrderFailed,
    CreateExpressConsignment,
    ExpressTrackingUpdate,
    CreatePacking,
    CustomerOrderRefunds,
    PartnerDeliveryStartedCheck,
    PartnerDeliveryCompletedCheck,
    InventoryUpdate
};
use App\Schedulers\Drivers\{
    ResponsePolls,
    RescheduleDelivery,
    RescheduleBookings,
    ValidateLiveDrivers,
    ScheduleNewBookings
};
use App\Schedulers\Supports\{
    PackingDelayCalls,
    PackingDelayManualCalls
};
use App\Schedulers\ScheduledDelays\{
    /* PackingNotStartedDelay,
    PackingNotCompletedDelay,
    DeliveryNotStartedDelay,
    DeliveryNotCompletedDelay, */
    DelayedActions\DelayedAPICancellations,
    DelayedActions\DelayedMerchantCancellations
};
use Illuminate\Console\Scheduling\Schedule;
use App\Schedulers\RelationOfficer\ContactToLead;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Schedulers\PostingScheduler\Postings\AutoPostingNew;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(new AssignOrder)->name('AssignOrder')->everyTwoMinutes()->withoutOverlapping();
        $schedule->call(new RemoveBlockedItems)->name('RemoveBlockedItems')->everyFiveMinutes()->withoutOverlapping();
        $schedule->call(new OrderStatusUpdate)->name('OrderStatusUpdate')->everyTwoMinutes()->withoutOverlapping();
        $schedule->call(new BranchStatusUpdate)->name('BranchStatusUpdate')->cron('*/3 * * * *')->withoutOverlapping();
        //$schedule->call(new InventoryUpdate)->name('InventoryUpdate')->withoutOverlapping();
       
        
        //merchant settlements
        $schedule->call(new MerchantSettlements)->name('MerchantSettlements')->dailyAt('00:02');

        //merchant settlements ro finance transaction
        $schedule->call(new FinanceTransaction)->name('FinanceTransaction')->dailyAt('13:02');

        //consignment tracking update
        $schedule->call(new ConsignmentTrackingUpdate)->name('ConsignmentTrackingUpdate')->cron('0 */2 * * *');

        //create new shipping consignment
        $schedule->call(new CreateShippingConsignment)->name('CreateShippingConsignment')->everyFiveMinutes()->withoutOverlapping();

        //create express consignment
        $schedule->call(new CreateExpressConsignment)->name('CreateExpressConsignment')->everyFiveMinutes()->withoutOverlapping();
        //express delivery status update
        // $schedule->call(new ExpressTrackingUpdate)->name('ExpressTrackingUpdate')->cron('0 */1 * * *');
        // express tracking by delay
        $schedule->call(new PartnerDeliveryStartedCheck)->name('PartnerDeliveryStartedCheck')->cron('*/30 * * * *');
        $schedule->call(new PartnerDeliveryCompletedCheck)->name('PartnerDeliveryCompletedCheck')->cron('*/30 * * * *');
        
        //create packing
        $schedule->call(new CreatePacking)->name('CreatePacking')->everyFiveMinutes()->withoutOverlapping();

        //customer order refunds
        $schedule->call(new CustomerOrderRefunds)->name('CustomerOrderRefunds')->everyThirtyMinutes()->withoutOverlapping();

        //convert crm contact to lead by area
        $schedule->call(new ContactToLead)->name('ContactToLead')->hourly()->withoutOverlapping();

        //scheduler for autoposting and costdistribution
        $schedule->call(new AutoPostingNew)->name('AutoPostingNew')->everyFifteenMinutes()->withoutOverlapping();

        // scheduled delay orders
        // $schedule->call(new PackingNotStartedDelay)->name('PackingNotStartedDelay')->cron('*/5 * * * *')->withoutOverlapping();
        // $schedule->call(new PackingNotCompletedDelay)->name('PackingNotCompletedDelay')->cron('*/5 * * * *')->withoutOverlapping();
        // $schedule->call(new DeliveryNotStartedDelay)->name('DeliveryNotStartedDelay')->cron('*/5 * * * *')->withoutOverlapping();
        // $schedule->call(new DeliveryNotCompletedDelay)->name('DeliveryNotCompletedDelay')->cron('*/5 * * * *')->withoutOverlapping();
        // Merchant Cancellation Delay
        $schedule->call(new DelayedMerchantCancellations)->name('DelayedMerchantCancellations')->everyFiveMinutes()->withoutOverlapping();
        // Thirdparty Delivery Cancellation Delay
        $schedule->call(new DelayedAPICancellations)->name('DelayedAPICancellations')->everyFiveMinutes()->withoutOverlapping();

        //packing delay ivr calls
        $schedule->call(new PackingDelayCalls)->name('PackingDelayCalls')->cron('*/20 * * * *')->withoutOverlapping();

        //packing delay outbound calls
        $schedule->call(new PackingDelayManualCalls)->name('PackingDelayManualCalls')->cron('*/10 * * * *')->withoutOverlapping();

        // check and set timout to orders
        $schedule->call(new CheckOrderTimeout)->name('CheckOrderTimeout')->everyTwoMinutes()->withoutOverlapping();

        // check and set failed status to timedout orders
        $schedule->call(new CheckOrderFailed)->name('CheckOrderFailed')->everyFiveMinutes()->withoutOverlapping();

        // driver schedulers
        $schedule->call(new ValidateLiveDrivers)->name('ValidateLiveDrivers')->everyFiveMinutes()->withoutOverlapping();
        $schedule->call(new ScheduleNewBookings)->name('ScheduleNewBookings')->everyTwoMinutes()->withoutOverlapping();
        $schedule->call(new ResponsePolls)->name('ResponsePolls')->everyTwoMinutes()->withoutOverlapping();
        $schedule->call(new RescheduleBookings)->name('RescheduleBookings')->everyFiveMinutes()->withoutOverlapping();
        $schedule->call(new RescheduleDelivery)->name('RescheduleDelivery')->everyFiveMinutes()->withoutOverlapping();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
