<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('finascop_branch')) {
            return;
        }

        Schema::create('finascop_branch', function (Blueprint $table) {
            $table->bigIncrements('br_ID');
            $table->string('br_Name', 255);
            $table->string('br_Phone', 20)->nullable();
            $table->string('br_Email', 255)->nullable();
            $table->text('br_Address')->nullable();
            $table->string('br_Address2', 255)->nullable();
            $table->string('br_Address3', 255)->nullable();
            $table->string('br_City', 100)->nullable();
            $table->unsignedBigInteger('br_State')->nullable()->index();
            $table->unsignedBigInteger('br_District')->nullable()->index();
            $table->string('br_pincode', 10)->nullable();
            $table->decimal('br_Lat', 10, 7)->nullable();
            $table->decimal('br_Lng', 10, 7)->nullable();
            $table->unsignedBigInteger('br_storeGroup')->index();
            $table->string('br_ReferenceID', 100)->nullable();
            $table->integer('br_PyramidLevel')->default(0);
            $table->unsignedBigInteger('br_rdrIdExpress')->nullable();
            $table->string('br_GST', 50)->nullable();
            $table->string('br_status', 20)->default('Active');
            $table->boolean('br_directDelivery')->default(false);
            $table->boolean('br_cpd')->default(false);
            $table->unsignedBigInteger('br_pgchargeId')->nullable();
            $table->integer('br_stocklevel')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finascop_branch');
    }
};
