<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('finascop_company')) {
            return;
        }

        Schema::create('finascop_company', function (Blueprint $table) {
            $table->bigIncrements('comp_id');
            $table->string('comp_name', 255);
            $table->string('comp_code', 50)->nullable();
            $table->string('comp_email', 255)->nullable();
            $table->string('comp_phone', 20)->nullable();
            $table->text('comp_address')->nullable();
            $table->string('comp_city', 100)->nullable();
            $table->string('comp_state', 100)->nullable();
            $table->string('comp_pincode', 10)->nullable();
            $table->string('comp_country', 100)->nullable();
            $table->string('comp_gst', 50)->nullable();
            $table->string('comp_pan', 50)->nullable();
            $table->string('comp_logo', 500)->nullable();
            $table->string('comp_currency', 10)->nullable();
            $table->string('comp_timezone', 50)->nullable();
            $table->boolean('comp_status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finascop_company');
    }
};
