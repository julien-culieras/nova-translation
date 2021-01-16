<?php

use Illuminate\Support\Facades\Route;
use Sideapps\NovaTranslation\Http\Controllers\LanguageController;
use Sideapps\NovaTranslation\Http\Controllers\LanguageTranslationController;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::get('languages', LanguageController::class.'@index')
    ->name('languages.index');

Route::delete('languages/{language}', LanguageController::class.'@delete');

Route::post('languages', LanguageController::class.'@store')
    ->name('languages.store');

Route::get('languages/{language}/translations', LanguageTranslationController::class.'@index')
    ->name('languages.translations.index');

Route::put('languages/{language}/translations', LanguageTranslationController::class.'@update')
    ->name('languages.translations.update');

