<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('retaline_customer_order_delivery_address')) {
            return;
        }

        Schema::create('retaline_customer_order_delivery_address', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_order_id')->index();
            $table->string('name', 100);
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('address2', 255)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('pincode', 10)->nullable();
            $table->string('landmark', 255)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retaline_customer_order_delivery_address');
    }
};
