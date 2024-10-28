<?php

use App\Http\Controllers\JobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/jobs', [JobController::class, 'create']);
Route::get('/jobs/{scrapeJob}', [JobController::class, 'show']);
Route::delete('/jobs/{scrapeJob}', [JobController::class, 'delete']);
