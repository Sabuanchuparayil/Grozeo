<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('retaline_customer_order')) {
            return;
        }

        Schema::create('retaline_customer_order', function (Blueprint $table) {
            $table->bigIncrements('order_id');
            $table->string('order_order_id', 50)->unique();
            $table->string('order_group_id', 50)->nullable()->index();
            $table->unsignedBigInteger('order_customer_id')->index();
            $table->unsignedBigInteger('order_branch_id')->index();
            $table->unsignedBigInteger('storegroup_id')->index();
            $table->unsignedInteger('status_id')->default(1);
            $table->decimal('order_total', 12, 2)->default(0);
            $table->decimal('order_subtotal', 12, 2)->default(0);
            $table->decimal('order_delivery_charge', 10, 2)->default(0);
            $table->decimal('order_discount', 10, 2)->default(0);
            $table->decimal('order_tax', 10, 2)->default(0);
            $table->string('payment_method', 50)->nullable();
            $table->string('payment_status', 30)->default('pending');
            $table->string('order_method', 20)->default('delivery');
            $table->string('delivery_slot', 100)->nullable();
            $table->string('order_notes', 500)->nullable();
            $table->string('coupon_code', 50)->nullable();
            $table->decimal('coupon_discount', 10, 2)->default(0);
            $table->decimal('wallet_used', 10, 2)->default(0);
            $table->unsignedBigInteger('delivery_rule_id')->nullable();
            $table->string('payment_transaction_id', 255)->nullable();
            $table->timestamps();

            $table->index(['order_customer_id', 'storegroup_id']);
            $table->index(['storegroup_id', 'status_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retaline_customer_order');
    }
};
