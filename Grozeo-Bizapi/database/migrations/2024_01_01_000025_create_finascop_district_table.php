<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('finascop_district')) {
            return;
        }

        Schema::create('finascop_district', function (Blueprint $table) {
            $table->bigIncrements('dst_Id');
            $table->string('dst_Name', 100);
            $table->unsignedBigInteger('dst_state_id')->nullable()->index();
            $table->boolean('dst_status')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finascop_district');
    }
};
