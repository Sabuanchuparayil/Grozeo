<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('mypha_productsubcategory')) {
            return;
        }

        Schema::create('mypha_productsubcategory', function (Blueprint $table) {
            $table->bigIncrements('sub_category_id');
            $table->string('sub_category_name', 255)->nullable();
            $table->string('sub_category_image', 500)->nullable();
            $table->unsignedBigInteger('main_category')->index();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_on')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('updated_on')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mypha_productsubcategory');
    }
};
