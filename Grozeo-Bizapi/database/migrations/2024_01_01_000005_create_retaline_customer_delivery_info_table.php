<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('retaline_customer_delivery_info')) {
            return;
        }

        Schema::create('retaline_customer_delivery_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('deli_customer_id')->index();
            $table->string('deli_name', 100);
            $table->string('deli_phone', 20)->nullable();
            $table->text('deli_address')->nullable();
            $table->string('deli_address2', 255)->nullable();
            $table->string('deli_city', 100)->nullable();
            $table->string('deli_state', 100)->nullable();
            $table->string('deli_pincode', 10)->nullable();
            $table->string('deli_landmark', 255)->nullable();
            $table->decimal('deli_latitude', 10, 7)->nullable();
            $table->decimal('deli_longitude', 10, 7)->nullable();
            $table->boolean('deli_primary')->default(false);
            $table->string('deli_type', 20)->default('home');
            $table->unsignedBigInteger('deli_retailer')->nullable();
            $table->unsignedBigInteger('storegroup_id')->index();
            $table->timestamps();

            $table->index(['deli_customer_id', 'storegroup_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retaline_customer_delivery_info');
    }
};
