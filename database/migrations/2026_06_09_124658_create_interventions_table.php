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
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->string('numero_ticket')->unique();
            $table->foreignId('site_id')->constrained()->onDelete('cascade');
            $table->foreignId('ressource_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('equipement_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['depannage','installation','maintenance','support','autre']);
            $table->string('titre');
            $table->text('description');
            $table->enum('priorite', ['basse','normale','haute','critique'])->default('normale');
            $table->enum('statut', ['ouvert','en_cours','resolu','ferme'])->default('ouvert');
            $table->date('date_ouverture');
            $table->date('date_resolution')->nullable();
            $table->text('solution')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
