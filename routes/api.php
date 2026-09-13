<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SamperinApi\SamperinApiController;

Route::post('/login', [SamperinApiController::class, 'login']);

Route::get('/pegawai', [SamperinApiController::class, 'allPegawai']);

Route::get('/bidang', [SamperinApiController::class, 'allBidang']);

Route::get('/pegawai/{id}', [SamperinApiController::class, 'pegawaiByID']);