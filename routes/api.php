<?php

use App\Http\Controllers\v1\Controller;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('v1/document ',[Controller::class,'create']);
Route::get('v1/document/{id}', [Controller::class, 'getDocumentById']);
Route::patch('v1/document/{id}',[Controller::class,'editing']);
Route::post('v1/document/{id}/publish',[Controller::class,'publish']);
