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
        Schema::create('ai_api_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('ai_providers')->onDelete('cascade');
            $table->text('api_key');
            $table->enum('status', ['active', 'inactive', 'limited'])->default('active');
            $table->integer('priority')->default(1);
            $table->bigInteger('usage_count')->default(0);
            $table->bigInteger('error_count')->default(0);
            $table->timestamp('cooldown_until')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_api_keys');
    }
};
