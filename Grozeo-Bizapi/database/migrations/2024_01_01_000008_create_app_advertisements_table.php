<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('app_advertisements')) {
            return;
        }

        Schema::create('app_advertisements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 255)->nullable();
            $table->string('image', 500)->nullable();
            $table->string('link', 500)->nullable();
            $table->unsignedBigInteger('adzone_id')->index();
            $table->unsignedBigInteger('storegroup_id')->index();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_advertisements');
    }
};
