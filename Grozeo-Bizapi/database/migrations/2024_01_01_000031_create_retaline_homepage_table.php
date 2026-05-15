<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('retaline_homepage')) {
            return;
        }

        Schema::create('retaline_homepage', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('storegroup_id')->index();
            $table->string('section_type', 50)->nullable();
            $table->string('title', 255)->nullable();
            $table->text('content')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retaline_homepage');
    }
};
