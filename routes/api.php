<?php

use Aliraza371\MlPublicationCreation\Controllers\MLAPublicationCreationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. You're free to add
| as many additional routes to this file as your tool may require.
|
*/

Route::get('/get-mla-categories', MLAPublicationCreationController::class.'@getMLACategories');
Route::get('/get-category-attributes/{categoryId}', MLAPublicationCreationController::class.'@getCategoryAttributes');
Route::post('/create_ml_publication', MLAPublicationCreationController::class.'@createMLPublication');
