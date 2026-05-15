<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('finascop_stock_itemmaster')) {
            return;
        }

        Schema::create('finascop_stock_itemmaster', function (Blueprint $table) {
            $table->bigIncrements('stit_ID');
            $table->string('stit_itemName', 255);
            $table->string('stit_SKU', 100)->nullable()->index();
            $table->text('stit_Description')->nullable();
            $table->text('stit_long_description')->nullable();
            $table->decimal('stit_MRP', 12, 2)->default(0);
            $table->decimal('stit_GST', 5, 2)->default(0);
            $table->string('stit_HSNCode', 20)->nullable();
            $table->unsignedBigInteger('stit_unit')->nullable();
            $table->decimal('stit_quantity', 10, 3)->default(0);
            $table->string('stit_displaylabel', 100)->nullable();
            $table->string('stit_brand_name', 255)->nullable()->index();
            $table->string('stit_category_name', 255)->nullable();
            $table->string('stit_foodtype', 20)->nullable();
            $table->string('stit_orgin_country', 100)->nullable();
            $table->text('stit_ingredients')->nullable();
            $table->text('stit_allergens')->nullable();
            $table->text('stit_nutritionlabel')->nullable();
            $table->text('stit_preparation_use')->nullable();
            $table->string('stit_product_variant', 255)->nullable();
            $table->decimal('stit_courierWt', 10, 3)->nullable();
            $table->decimal('stit_item_volume', 10, 3)->nullable();
            $table->integer('stit_itemReturnTime')->default(0);
            $table->boolean('stit_HasChildItem')->default(false);
            $table->unsignedBigInteger('stit_ParentItemId')->nullable()->index();
            $table->boolean('stit_custInitiate')->default(false);
            $table->string('stit_status', 20)->default('Active')->index();
            $table->unsignedBigInteger('stit_fsiuid')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finascop_stock_itemmaster');
    }
};
