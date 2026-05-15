<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('mypha_productcategory')) {
            return;
        }

        Schema::create('mypha_productcategory', function (Blueprint $table) {
            $table->bigIncrements('category_id');
            $table->string('category_name', 255);
            $table->string('category_value', 255)->nullable();
            $table->integer('category_level')->default(0);
            $table->string('category_businessType', 50)->nullable();
            $table->string('category_businessgroup_filter', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_on')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_on')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mypha_productcategory');
    }
};
