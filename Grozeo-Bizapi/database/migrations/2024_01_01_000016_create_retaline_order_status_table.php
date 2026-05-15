<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('retaline_order_status')) {
            return;
        }

        Schema::create('retaline_order_status', function (Blueprint $table) {
            $table->bigIncrements('stat_id');
            $table->unsignedBigInteger('stat_order_id')->index();
            $table->string('stat_order_status', 50);
            $table->text('stat_order_description')->nullable();
            $table->text('stat_addinfo')->nullable();
            $table->timestamp('stat_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retaline_order_status');
    }
};
