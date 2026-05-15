<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('retaline_cart')) {
            return;
        }

        Schema::create('retaline_cart', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cart_customer_id')->index();
            $table->unsignedBigInteger('cart_product_id')->index();
            $table->unsignedBigInteger('cart_group_id')->nullable()->index();
            $table->integer('cart_quantity')->default(1);
            $table->decimal('cart_price', 10, 2)->default(0);
            $table->string('cart_variant', 255)->nullable();
            $table->string('order_method', 20)->default('delivery');
            $table->unsignedBigInteger('cart_branch_id')->nullable();
            $table->unsignedBigInteger('storegroup_id')->index();
            $table->string('guest_token', 100)->nullable()->index();
            $table->timestamps();

            $table->index(['cart_customer_id', 'storegroup_id', 'order_method']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retaline_cart');
    }
};
