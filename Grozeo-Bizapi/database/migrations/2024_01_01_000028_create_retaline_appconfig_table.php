<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('retaline_appconfig')) {
            return;
        }

        Schema::create('retaline_appconfig', function (Blueprint $table) {
            $table->bigIncrements('brac_id');
            $table->unsignedBigInteger('brac_branch')->index();
            $table->string('brac_phone', 20)->nullable();
            $table->decimal('brac_delivery_charge', 12, 2)->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retaline_appconfig');
    }
};
