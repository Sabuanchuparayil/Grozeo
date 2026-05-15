<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('retaline_delivery_rules')) {
            return;
        }

        Schema::create('retaline_delivery_rules', function (Blueprint $table) {
            $table->bigIncrements('rdr_id');
            $table->integer('rdr_ruleFor')->default(0);
            $table->unsignedBigInteger('rdr_deliveryMode')->nullable();
            $table->string('rdr_calculationMode', 50)->nullable();
            $table->boolean('rdr_isfreeDelivery')->default(false);
            $table->decimal('rdr_isfreeDeliveryAmt', 12, 2)->default(0);
            $table->decimal('rdr_fromkm1', 10, 2)->default(0);
            $table->decimal('rdr_tokm1', 10, 2)->default(0);
            $table->decimal('rdr_amt1', 12, 2)->default(0);
            $table->decimal('rdr_fromkm2', 10, 2)->default(0);
            $table->decimal('rdr_tokm2', 10, 2)->default(0);
            $table->decimal('rdr_amt2', 12, 2)->default(0);
            $table->decimal('rdr_fromkm3', 10, 2)->default(0);
            $table->decimal('rdr_tokm3', 10, 2)->default(0);
            $table->decimal('rdr_amt3', 12, 2)->default(0);
            $table->decimal('rdr_fixedRateMin', 12, 2)->default(0);
            $table->decimal('rdr_fixedRateMax', 12, 2)->default(0);
            $table->decimal('rdr_fixedRateperkm', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retaline_delivery_rules');
    }
};
