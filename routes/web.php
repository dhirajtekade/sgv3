<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MhtController;
use App\Http\Controllers\RerportController;
use App\Http\Controllers\SgdataController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
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

    //printpage
    Route::get('generateFinalPrint', [SgdataController::class, 'generateFinalPrint'])->name('generateFinalPrint');

    //Edit Mht
    Route::post('updateMht', [MhtController::class, 'updateMht'])->name('updateMht');

    //Checkout
    Route::post('checkout', [SgdataController::class, 'checkout'])->name('checkout');
    Route::post('checkoutallinone', [SgdataController::class, 'checkoutallinone'])->name('checkoutallinone');
    Route::post('partialcheckout', [SgdataController::class, 'partialcheckout'])->name('partialcheckout');

    Route::get('scanbymachine', [SgdataController::class, 'scanbymachine'])->name('users.scanbymachine');
    Route::get('scanqr3', [SgdataController::class, 'scanqr3'])->name('users.scanqr3');

    //import
    // Route::get('/import-form', [ImportExportController::class, 'importForm']);
    // Route::post('/import', [ImportExportController::class, 'import'])->name('mhtdata-import');

    //coordinator
    Route::get('eveninfo', [SettingController::class, 'eveninfo'])->name('eveninfo');
    Route::post('store_eveninfo', [SettingController::class, 'store_eveninfo'])->name('store_eveninfo');

    Route::get('setting_form', [SettingController::class, 'setting_form'])->name('setting_form');
    Route::post('store_setting_form', [SettingController::class, 'store_setting_form'])->name('store_setting_form');


    //report
    Route::get('/report_page', [RerportController::class, 'report_page'])->name('report_page');
    Route::post('/mhtdata_import', [RerportController::class, 'mhtdata_import'])->name('mhtdata_import');
    Route::post('/export_excel', [RerportController::class, 'exportIntoExcel'])->name('export_excel');
    Route::post('/export_report', [RerportController::class, 'export_report'])->name('export_report');



});
