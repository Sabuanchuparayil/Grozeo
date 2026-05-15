<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('retaline_customer_order_status')) {
            return;
        }

        Schema::create('retaline_customer_order_status', function (Blueprint $table) {
            $table->bigIncrements('status_id');
            $table->string('status_name', 50);
            $table->string('status_description', 255)->nullable();
            $table->integer('status_order')->default(0);
            $table->boolean('is_active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retaline_customer_order_status');
    }
};
