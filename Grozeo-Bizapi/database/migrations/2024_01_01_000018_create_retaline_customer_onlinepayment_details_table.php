<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('retaline_customer_onlinepayment_details')) {
            return;
        }

        Schema::create('retaline_customer_onlinepayment_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('roop_requestid', 255)->nullable()->index();
            $table->text('roop_requeststring')->nullable();
            $table->text('roop_responsestring')->nullable();
            $table->timestamp('roop_requestdatetime')->nullable();
            $table->timestamp('roop_responsedatetime')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retaline_customer_onlinepayment_details');
    }
};
