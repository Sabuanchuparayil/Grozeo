<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('retaline_country')) {
            return;
        }

        Schema::create('retaline_country', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('code', 5)->unique();
            $table->string('phone_code', 10)->nullable();
            $table->string('currency', 10)->nullable();
            $table->string('currency_symbol', 10)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retaline_country');
    }
};
