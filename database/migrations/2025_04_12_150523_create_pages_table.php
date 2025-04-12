<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome della persona
            $table->date('birth_date'); // Data di nascita
            $table->date('death_date'); // Data di morte
            $table->string('location'); // Luogo
            $table->text('biography'); // Biografia
            $table->json('key_traits')->nullable(); // Tratti chiave (JSON)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
