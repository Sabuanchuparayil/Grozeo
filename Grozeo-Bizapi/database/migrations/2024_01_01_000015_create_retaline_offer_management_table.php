<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('retaline_offer_management')) {
            return;
        }

        Schema::create('retaline_offer_management', function (Blueprint $table) {
            $table->bigIncrements('bom_id');
            $table->string('bom_offerCode', 50)->nullable()->index();
            $table->string('bom_offerType', 50)->nullable();
            $table->string('bom_type', 50)->nullable();
            $table->string('bom_offrPlacement', 50)->nullable();
            $table->string('bom_offrDiffer', 50)->nullable();
            $table->string('bom_offfrvalidtype', 50)->nullable();
            $table->integer('bom_use')->default(0);
            $table->boolean('bom_locked')->default(false);
            $table->date('bom_startdate')->nullable();
            $table->date('bom_enddate')->nullable();
            $table->string('bom_status', 20)->default('Active');
            $table->timestamp('bom_createdOn')->nullable();
            $table->timestamp('bom_updatedOn')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retaline_offer_management');
    }
};
