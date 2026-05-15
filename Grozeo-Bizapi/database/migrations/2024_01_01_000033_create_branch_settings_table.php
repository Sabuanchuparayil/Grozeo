<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('branch_settings')) {
            return;
        }

        Schema::create('branch_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id')->index();
            $table->string('setting_key', 100);
            $table->text('setting_value')->nullable();
            $table->timestamps();

            $table->unique(['branch_id', 'setting_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_settings');
    }
};
