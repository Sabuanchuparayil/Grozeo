<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('finascop_state')) {
            return;
        }

        Schema::create('finascop_state', function (Blueprint $table) {
            $table->bigIncrements('st_ID');
            $table->string('st_name', 100);
            $table->string('st_code', 10)->nullable();
            $table->unsignedBigInteger('st_country_id')->nullable()->index();
            $table->boolean('st_status')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finascop_state');
    }
};
