<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCsvController;
use App\Http\Controllers\AdminHtmlController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

Route::group( ['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('home');
    Route::get('/load-html', [AdminHtmlController::class, 'loadHtml'])->name('load.html');
    Route::get('/parse-html-csv', [AdminHtmlController::class, 'parseHtmlToCsv'])->name('parse.html-csv');
    Route::get('/parse-csv-db', [AdminCsvController::class, 'parseCsvToDb'])->name('parse.csv-db');
    Route::post('process-upload', [AdminCsvController::class, 'processUpload'])->name('upload.process');
});

// Save data from the modal box submitted from the front-end
Route::post('/save-clf-data/{clfrecord}', [HomeController::class, 'processFormData'])->name('process-form');
Route::post('/delete-clf-data/{clfrecord}', [HomeController::class, 'processDeleteData'])->name('process-delete');
