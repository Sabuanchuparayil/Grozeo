<?php

namespace App\Http\Repositories\Payment;

use App\Models\Cart;
use App\Models\BlockedItems;
use Illuminate\Support\Facades\DB;
use BackOffice\Models\BranchInventory;

class AfterPayment
{

    public static function minusStock($customer_id)
    {
        return (new static)->reduceStockNo($customer_id);
    }
    /**
     * Reduce stock count when transaction is completed.
     *
     * @param string $pay
     * @return json
     */
    private function reduceStockNo($customer_id)
    {

        $blockedItem = $this->getStockBlocked($customer_id)
            ->toArray();
        $this->getBranchInventory($blockedItem);

        return $customer_id;
    }
    /**
     * Get product based on order id .
     *
     * @param string $orderId
     * @return \Illuminate\Support\Collection
     */
    private function getStockBlocked($customer_id = '')
    {
        return BlockedItems::where('customer_id', $customer_id)
            ->select('item_id', 'branch_id', 'count')
            ->get();
    }

    private function getBranchInventory(array $blockedItem)
    {
        DB::enableQueryLog(); 
        DB::transaction(function () use ($blockedItem) {
            foreach ($blockedItem as $item) {
                $branchInventoy = BranchInventory::where('stit_id', $item['item_id'])
                    ->where('branch_id', $item['branch_id'])
                    ->select('item_count')
                    ->first();
                if ($branchInventoy) {
                    $reduceCount = $branchInventoy->item_count - $item['count'];
                    BranchInventory::where('stit_id', $item['item_id'])
                        ->where('branch_id', $item['branch_id'])
                        ->update(['item_count' => $reduceCount]);
                }
            }
            $this->removeBlockedItem();
            $this->clearItems();
        });
    }

    private function removeBlockedItem()
    {
        $customer_id = auth_user()->cust_id ?? 0;
        return BlockedItems::where('customer_id', $customer_id)
            ->delete();
    }

    
    public function clearItems()
    {
        Cart::where('cart_customer_id', auth_user()->cust_id)
            ->delete();
    }
}
