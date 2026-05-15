<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('retaline_customer_order_items')) {
            return;
        }

        Schema::create('retaline_customer_order_items', function (Blueprint $table) {
            $table->bigIncrements('item_id');
            $table->unsignedBigInteger('customer_order_id')->index();
            $table->unsignedBigInteger('item_product_id')->index();
            $table->unsignedBigInteger('item_group_id')->nullable();
            $table->string('item_name', 255);
            $table->integer('item_quantity')->default(1);
            $table->decimal('item_price', 10, 2)->default(0);
            $table->decimal('item_total', 10, 2)->default(0);
            $table->decimal('item_mrp', 10, 2)->default(0);
            $table->decimal('item_tax', 10, 2)->default(0);
            $table->string('item_variant', 255)->nullable();
            $table->string('item_image', 500)->nullable();
            $table->string('item_unit', 50)->nullable();
            $table->unsignedInteger('item_status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retaline_customer_order_items');
    }
};
