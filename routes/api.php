<?php

use App\Http\Controllers\DocController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/assign-reviewer', [DocController::class, 'assignUserToDocument']);
Route::post('/assign-registrant', [DocController::class, 'assignUserToDocument']);
Route::get('/assigned-docs', [DocController::class, 'getAssignedDocs']);
