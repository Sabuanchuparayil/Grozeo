<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('retaline_customer_wallet_transaction')) {
            return;
        }

        Schema::create('retaline_customer_wallet_transaction', function (Blueprint $table) {
            $table->bigIncrements('brcw_id');
            $table->unsignedBigInteger('brcw_customer_id')->index();
            $table->decimal('brcw_Amount', 12, 2)->default(0);
            $table->decimal('brcw_OpeningBalance', 12, 2)->default(0);
            $table->decimal('brcw_closingBalance', 12, 2)->default(0);
            $table->string('brcw_SourceType', 50)->nullable();
            $table->text('brcw_AddInfo')->nullable();
            $table->timestamp('brcw_CreatedOn')->nullable();
            $table->timestamp('brcw_Updateon')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retaline_customer_wallet_transaction');
    }
};
