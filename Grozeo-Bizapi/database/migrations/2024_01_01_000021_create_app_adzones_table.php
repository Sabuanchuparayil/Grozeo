<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('app_adzones')) {
            return;
        }

        Schema::create('app_adzones', function (Blueprint $table) {
            $table->bigIncrements('adzone_id');
            $table->string('adzone_name', 100);
            $table->string('adzone_screen', 100)->nullable();
            $table->string('adzone_type', 50)->nullable();
            $table->boolean('adzone_status')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_adzones');
    }
};
