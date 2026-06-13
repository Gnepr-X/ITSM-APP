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
        Schema::create('restitutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attribution_id')->constrained()->onDelete('cascade');
            $table->date('date_restitution');
            $table->string('recu_par');
            $table->enum('etat_retour', ['bon','acceptable','endommagé','hors_service']);
            $table->text('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restitutions');
    }
};
