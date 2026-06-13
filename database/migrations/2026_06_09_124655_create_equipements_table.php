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
        Schema::create('equipements', function (Blueprint $table) {
            $table->id();
            $table->string('code_inventaire')->unique();
            $table->string('designation');
            $table->enum('type', ['ordinateur','imprimante','serveur','switch','routeur','camera','alarme','autre']);
            $table->string('marque')->nullable();
            $table->string('modele')->nullable();
            $table->string('numero_serie')->nullable();
            $table->enum('statut', ['disponible','attribue','en_reparation','hors_service'])->default('disponible');
            $table->foreignId('site_id')->constrained()->onDelete('cascade');
            $table->date('date_acquisition')->nullable();
            $table->decimal('valeur', 10, 2)->nullable();
            $table->string('qr_code')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipements');
    }
};
