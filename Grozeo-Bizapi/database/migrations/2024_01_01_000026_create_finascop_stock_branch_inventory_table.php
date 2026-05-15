<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('finascop_stock_branch_inventory')) {
            return;
        }

        Schema::create('finascop_stock_branch_inventory', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('item_id')->index();
            $table->unsignedBigInteger('branch_id')->index();
            $table->decimal('quantity', 12, 3)->default(0);
            $table->decimal('selling_price', 12, 2)->default(0);
            $table->decimal('mrp', 12, 2)->default(0);
            $table->string('status', 20)->default('Active');
            $table->timestamps();

            $table->index(['item_id', 'branch_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finascop_stock_branch_inventory');
    }
};
