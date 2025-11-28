<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->uuid('slug')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('host_id')->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // Store bandwidth settings
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};