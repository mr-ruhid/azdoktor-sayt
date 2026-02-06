<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MedicalApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Mobil tətbiqlər üçün endpointlər buradadır.
| Prefix: /api
|
*/

// --- Public Routes (Giriş tələb etməyən) ---

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Medical Data (Search App üçün idealdır)
Route::get('/doctors', [MedicalApiController::class, 'doctors']);
Route::get('/doctors/{id}', [MedicalApiController::class, 'doctorDetail']);
Route::get('/clinics', [MedicalApiController::class, 'clinics']);
Route::get('/specialties', [MedicalApiController::class, 'specialties']);
Route::get('/services', [MedicalApiController::class, 'services']);


// --- Private Routes (Token tələb edən) ---
Route::middleware('auth:sanctum')->group(function () {

    // İstifadəçi profil məlumatları
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Rezervasiya (Yalnız qeydiyyatlı istifadəçilər)
    Route::post('/reservations', [MedicalApiController::class, 'makeReservation']);

    // Gələcəkdə: Sifarişlər, Favoritlər və s.
});
