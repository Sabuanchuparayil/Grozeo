<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // finascop_stock_itemmaster — heavy query target
        $this->addIndexIfNotExists('finascop_stock_itemmaster', 'stit_fsiuid', 'idx_itemmaster_fsiuid');
        $this->addIndexIfNotExists('finascop_stock_itemmaster', 'stit_HasChildItem', 'idx_itemmaster_has_child');

        // finascop_stock_branch_inventory — product listings
        $this->addIndexIfNotExists('finascop_stock_branch_inventory', 'status', 'idx_inventory_status');

        // retaline_customer_order — frequent query columns
        $this->addIndexIfNotExists('retaline_customer_order', 'order_customer_id', 'idx_order_customer_id');
        $this->addIndexIfNotExists('retaline_customer_order', 'order_status', 'idx_order_status');
        $this->addIndexIfNotExists('retaline_customer_order', 'order_group_id', 'idx_order_group_id');
        $this->addIndexIfNotExists('retaline_customer_order', 'order_method', 'idx_order_method');

        // retaline_customer_order_items — order item lookups
        $this->addIndexIfNotExists('retaline_customer_order_items', 'customer_order_id', 'idx_orderitems_order_id');
        $this->addIndexIfNotExists('retaline_customer_order_items', 'product_id', 'idx_orderitems_product_id');

        // retaline_cart — cart queries
        $this->addIndexIfNotExists('retaline_cart', 'guest_token', 'idx_cart_guest_token');
        $this->addIndexIfNotExists('retaline_cart', 'cart_product_id', 'idx_cart_product_id');

        // mypha_productsubcategory — category browsing
        $this->addIndexIfNotExists('mypha_productsubcategory', 'main_category', 'idx_subcategory_main');

        // mypha_productcategory — parent category lookups
        $this->addIndexIfNotExists('mypha_productcategory', 'category_businessType', 'idx_category_biztype');

        // app_advertisements — ad serving
        $this->addIndexIfNotExists('app_advertisements', 'adzone_id', 'idx_adv_adzone');
        $this->addIndexIfNotExists('app_advertisements', 'is_active', 'idx_adv_active');

        // app_adzones — ad zone lookups
        $this->addIndexIfNotExists('app_adzones', 'adzone_screen', 'idx_adzone_screen');
        $this->addIndexIfNotExists('app_adzones', 'adzone_type', 'idx_adzone_type');

        // retaline_customer — customer lookups
        $this->addIndexIfNotExists('retaline_customer', 'cust_mobile', 'idx_customer_mobile');

        // retaline_offer_management — offer lookups
        $this->addIndexIfNotExists('retaline_offer_management', 'bom_status', 'idx_offer_status');
        $this->addIndexIfNotExists('retaline_offer_management', 'bom_startdate', 'idx_offer_start');
        $this->addIndexIfNotExists('retaline_offer_management', 'bom_enddate', 'idx_offer_end');

        // retaline_customer_delivery_info — delivery address lookups
        $this->addIndexIfNotExists('retaline_customer_delivery_info', 'deli_primary', 'idx_delivery_primary');

        // retaline_order_status — order status tracking
        $this->addIndexIfNotExists('retaline_order_status', 'stat_order_id', 'idx_orderstatus_order');
        $this->addIndexIfNotExists('retaline_order_status', 'stat_order_status', 'idx_orderstatus_status');

        // finascop_stock_item_images — image lookups
        $this->addIndexIfNotExists('finascop_stock_item_images', 'image_type', 'idx_itemimg_type');

        // finascop_branch — branch queries
        $this->addIndexIfNotExists('finascop_branch', 'br_PyramidLevel', 'idx_branch_pyramid');
        $this->addIndexIfNotExists('finascop_branch', 'br_status', 'idx_branch_status');
    }

    public function down(): void
    {
        // Indexes are safe to leave in place; dropping requires checking existence
    }

    private function addIndexIfNotExists(string $table, string $column, string $indexName): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        if (!Schema::hasColumn($table, $column)) {
            return;
        }

        $connection = Schema::getConnection();
        $existingIndexes = collect($connection->getDoctrineSchemaManager()->listTableIndexes($table));

        if ($existingIndexes->has($indexName)) {
            return;
        }

        Schema::table($table, function (Blueprint $table) use ($column, $indexName) {
            $table->index($column, $indexName);
        });
    }
};
