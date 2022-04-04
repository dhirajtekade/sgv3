<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MhtController;
use App\Http\Controllers\SgdataController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

Auth::routes();
Route::get('codetest',[SgdataController::class, 'codetest'])->name('codetest');

Route::group(['middleware' => ['auth']], function() {
    //home page
    Route::get('/', [SgdataController::class, 'sglist'])->name('sglist');
    Route::get('/home', [SgdataController::class, 'sglist'])->name('sglist');

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);

    //CheckIn
    Route::post('addNewMht', [MhtController::class, 'addNewMht'])->name('addNewMht');
    Route::get('searchResult/{mhtid?}/{mobile?}/{any?}', [SgdataController::class, 'searchResult'])->name('searchResult');



    //import
    // Route::get('/import-form', [ImportExportController::class, 'importForm']);
    // Route::post('/import', [ImportExportController::class, 'import'])->name('mhtdata-import');
});
