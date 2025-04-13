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
        Schema::create('typing_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_typing')->default(false);
            $table->timestamps();
            
            // Creates a unique index to prevent duplicate entries
            $table->unique(['sender_id', 'recipient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typing_statuses');
    }
};
