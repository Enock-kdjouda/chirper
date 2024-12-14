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
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chirp_id')->constrained()->onDelete('cascade'); // Clé étrangère vers les chirps
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Clé étrangère vers les utilisateurs
            $table->timestamps();
    
            $table->unique(['chirp_id', 'user_id']); // Empêcher les doublons
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
