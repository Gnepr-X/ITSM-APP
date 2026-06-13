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
        Schema::create('acces_wifis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->onDelete('cascade');
            $table->enum('operateur', ['ORANGE','MTN','AUTRE']);
            $table->string('nom_box');
            $table->string('ssid');
            $table->string('mot_de_passe');
            $table->string('adresse_ip')->nullable();
            $table->string('adresse_mac')->nullable();
            $table->date('date_installation')->nullable();
            $table->enum('statut', ['actif','inactif'])->default('actif');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acces_wifis');
    }
};
