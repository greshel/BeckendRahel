<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\KelasController;
use Illuminate\Support\Facades\Route;

// Route Publik
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login'])->name('login'); 

Route::get('/kelas', [KelasController::class, 'index']);
Route::get('/kelas/{id}', [KelasController::class, 'show']);

// Route Terproteksi Token Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Profil User & Logout
    Route::get('/user/profile', [UserController::class, 'profile']); 
    Route::post('/logout', [UserController::class, 'logout']);
    
    // Management Kelas
    Route::post('/kelas', [KelasController::class, 'store']); 
    
    // Fitur Pendaftaran
    Route::post('/kelas/daftar', [KelasController::class, 'daftar']);
    Route::get('/user/kelas-saya', [KelasController::class, 'kelasSaya']);
});