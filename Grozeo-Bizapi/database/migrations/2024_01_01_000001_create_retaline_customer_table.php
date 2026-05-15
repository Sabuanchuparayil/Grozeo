<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('retaline_customer')) {
            return;
        }

        Schema::create('retaline_customer', function (Blueprint $table) {
            $table->bigIncrements('cust_id');
            $table->string('name', 100);
            $table->string('cust_email', 255)->index();
            $table->string('password', 255);
            $table->string('phone', 20)->nullable()->index();
            $table->string('role', 50)->default('customer');
            $table->unsignedBigInteger('storegroup_id')->index();
            $table->boolean('is_active')->default(true);
            $table->string('profile_image', 500)->nullable();
            $table->string('device_token', 500)->nullable();
            $table->string('device_type', 20)->nullable();
            $table->decimal('wallet_balance', 10, 2)->default(0);
            $table->boolean('age_verified')->default(false);
            $table->timestamp('cust_created_at')->nullable();
            $table->timestamp('cust_updated_at')->nullable();

            $table->unique(['cust_email', 'storegroup_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retaline_customer');
    }
};
