<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Génération automatique de l'inventaire chaque mois
Schedule::command('itsm:inventaire')->monthly();

// Optionnel : rapport hebdomadaire des interventions ouvertes
Schedule::command('itsm:inventaire --mail=admin@itsm.local')->weekly()->mondays()->at('08:00');