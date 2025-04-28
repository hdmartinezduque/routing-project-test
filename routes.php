<?php

use App\Routing\Route;

//require __DIR__ . '/vendor/autoload.php';

Route::resource('patients');
Route::resource('patients.metrics');

// Route::add('patients', 'PatientsController');
// Route::add('patients.metrics', 'PatientsMetricsController');
