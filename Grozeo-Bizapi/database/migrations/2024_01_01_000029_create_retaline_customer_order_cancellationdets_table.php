<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('retaline_customer_order_cancellationdets')) {
            return;
        }

        Schema::create('retaline_customer_order_cancellationdets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->index();
            $table->string('cancelled_by', 50)->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->text('cancellation_comments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retaline_customer_order_cancellationdets');
    }
};
