<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ai_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->nullable()->constrained('ai_providers')->onDelete('set null');
            $table->foreignId('api_key_id')->nullable()->constrained('ai_api_keys')->onDelete('set null');
            $table->string('model', 255)->nullable();
            $table->integer('prompt_tokens')->default(0);
            $table->integer('completion_tokens')->default(0);
            $table->integer('total_tokens')->default(0);
            $table->float('response_time')->default(0);
            $table->string('status', 50)->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_logs');
    }
};
