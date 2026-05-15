<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('retaline_saved_items')) {
            return;
        }

        Schema::create('retaline_saved_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('order_method', 20)->default('delivery');
            $table->unsignedBigInteger('storegroup_id')->index();
            $table->timestamps();

            $table->unique(['customer_id', 'product_id', 'storegroup_id', 'order_method'], 'saved_items_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retaline_saved_items');
    }
};
