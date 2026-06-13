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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipement_id')->constrained()->onDelete('cascade');
            $table->enum('mouvement', ['entree','sortie']);
            $table->integer('quantite')->default(1);
            $table->string('motif');
            $table->foreignId('site_id')->constrained()->onDelete('cascade');
            $table->string('effectue_par');
            $table->date('date_mouvement');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
