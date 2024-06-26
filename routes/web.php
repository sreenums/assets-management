<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetManagementController;
use App\Http\Controllers\AssetParameterController;
use App\Http\Controllers\AssetTypeController;
use App\Http\Controllers\CSVExportController;
use App\Http\Controllers\CSVUploadController;
use App\Http\Controllers\HardwareStandardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TechnicalSpecificationsController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
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

// Route::get('/', function () {
//         return view('home');
//     })->name('assets.index');



// Route::get('/assets-type-add', function () {
//     return view('assets-parameters');
// })->name('assets.type.add');

// Route::get('/assets-type-home', function () {
//     return view('type-home');
// })->name('type.home');



//Login, Logout
Route::get('/', [LoginController::class, 'loginForm'])->name('login.index');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logot', [LoginController::class, 'logout'])->name('logout');


Route::middleware([Authenticate::class])->group(function () {

    Route::get('/assets-parameters', function () {
            return view('assets-parameters');
    })->name('asset.parameters');

    Route::get('/assets-type-home', [AssetTypeController::class, 'index'])->name('type.home');
    Route::resource('/assets-type', AssetTypeController::class);

    /**
     * For Hardware Standard
     * 
     */
    Route::get('/hardware-standard-home', [HardwareStandardController::class, 'index'])->name('hardware-standard.home');
    Route::resource('/hardware-standard', HardwareStandardController::class)->except(['show']);

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
    Route::resource('/assets', AssetController::class);

    //For Hardware standard with type
    Route::post('/type-hardware-standard',[AssetController::class, 'getHardwareStandardWithType'])->name('get.type.hardwares');

    //For Technical spec with hardware standard
    Route::post('/hardware-technical-spec',[AssetController::class, 'getTechnicalSpecsWithHardwareStandard'])->name('get.hardware.technical.spec');

    //For users list
    Route::post('/users-list',[UserController::class, 'getUsers'])->name('get.users');

    //For locations list
    Route::post('/locations-list',[AssetController::class, 'getLocations'])->name('get.locations');

    Route::get('/assets-list',[AssetController::class, 'assetsList'])->name('list.asset');

    //For status update
    Route::put('/status-update/{id}',[AssetController::class, 'updateStatus'])->name('asset.update-status');

    // Route for Status History
    Route::get('/assets/{asset}/history', [HistoryController::class, 'showHistory'])->name('asset.history');

    //Route for CSV Export
    Route::get('/export-csv', [CSVExportController::class, 'exportCsv'])->name('export.csv');

    //Route for Asset CSV Upload
    Route::get('/upload-form-csv', [CSVUploadController::class, 'index'])->name('upload.form.csv');
    Route::post('/upload-asset-csv', [CSVUploadController::class, 'uploadAssetCsv'])->name('upload.asset.csv');
    Route::post('/upload-save-csv', [CSVUploadController::class, 'saveUploadData'])->name('save.upload.csv');

});


// // Fallback route for 404 handling
// Route::fallback(function () {
//     return view('page-error'); // Load the custom 404 page
// });