<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FreelancerController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\LogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rota de teste padrão do Laravel (opcional, pode remover se quiser)
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas para todos os recursos da API (CRUD completo via apiResource)
// O Laravel mapeia automaticamente para os métodos index, store, show, update, destroy
Route::apiResource('users', UserController::class);
Route::apiResource('freelancers', FreelancerController::class);
Route::apiResource('companies', CompanyController::class);
Route::apiResource('job_vacancies', JobVacancyController::class);
Route::apiResource('skills', SkillController::class);
Route::apiResource('applications', ApplicationController::class);
Route::apiResource('logs', LogController::class); // Logs geralmente só terão GET/POST
