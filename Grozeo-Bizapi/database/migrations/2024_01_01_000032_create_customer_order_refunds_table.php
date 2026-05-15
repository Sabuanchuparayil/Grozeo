<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customer_order_refunds')) {
            return;
        }

        Schema::create('customer_order_refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->index();
            $table->unsignedBigInteger('customer_id')->index();
            $table->decimal('refund_amount', 12, 2)->default(0);
            $table->string('refund_status', 50)->default('pending');
            $table->string('refund_method', 50)->nullable();
            $table->text('refund_reason')->nullable();
            $table->string('transaction_id', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_order_refunds');
    }
};
