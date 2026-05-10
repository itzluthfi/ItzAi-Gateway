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
        Schema::create('ai_conversations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('session_id', 255)->nullable();
            $table->string('role', 50);
            $table->longText('message');
            $table->foreignId('provider_id')->nullable()->constrained('ai_providers')->onDelete('set null');
            $table->string('model', 255)->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_conversations');
    }
};
