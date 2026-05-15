<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sms_email_logs')) {
            return;
        }

        Schema::create('sms_email_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type', 20)->index();
            $table->string('recipient', 255)->index();
            $table->string('subject', 255)->nullable();
            $table->text('message')->nullable();
            $table->string('status', 20)->default('pending');
            $table->text('response')->nullable();
            $table->unsignedBigInteger('storegroup_id')->nullable()->index();
            $table->timestamp('sent_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_email_logs');
    }
};
