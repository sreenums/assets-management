<?php

use App\Http\Controllers\AssetManagementController;
use App\Http\Controllers\AssetParameterController;
use App\Http\Controllers\AssetTypeController;
use App\Http\Controllers\HardwareStandardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\TechnicalSpecificationsController;
use App\Http\Controllers\UserController;
use App\Models\Type;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
        return view('home');
    })->name('home');

Route::get('/assets-parameters', function () {
    return view('assets-parameters');
})->name('asset.parameters');

Route::get('/assets-type-add', function () {
    return view('assets-parameters');
})->name('assets.type.add');

Route::get('/assets-type-home', function () {
    return view('type-home');
})->name('type.home');
// Route::get('/assets-type-home', [AssetParameterController::class, 'index'])->name('type.home');

// Route::post('/save-type', [AssetParameterController::class, 'saveTypeData'])->name('save.type');

Route::get('/assets-type-home', [AssetTypeController::class, 'index'])->name('type.home');
// Route::put('/assets-type-home', [AssetTypeController::class, 'index'])->name('type.home');
Route::resource('/assets-type', AssetTypeController::class);

/**
 * For Hardware Standard
 * 
 */
Route::resource('/hardware-standard', HardwareStandardController::class);

/**
 * For Technical Specs
 * 
 */
Route::resource('/technical-specs', TechnicalSpecificationsController::class);

/**
 * For Locations
 * 
 */
Route::resource('/locations', LocationController::class);

/**
 * For Users
 * 
 */
Route::resource('/users', UserController::class);

/**
 * For Assets management
 * 
 */
Route::resource('/assets', AssetManagementController::class);

//For Hardware with type
Route::post('/type-hardware-standard',[AssetManagementController::class, 'getHardwareStandardWithType'])->name('get.type.hardwares');
//For Technical spec with hardware standard
Route::post('/hardware-technical-spec',[AssetManagementController::class, 'getTechnicalSpecsWithHardwareStandard'])->name('get.hardware.technical.spec');

