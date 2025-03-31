<?php

//use Illuminate\Support\Facades\Route;

use PSW\Cinema\Shop\Http\Controllers\ReleaseController;
use PSW\Cinema\Shop\Http\Controllers\MasterController;

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {


    Route::get('/releases/{id}/{slug}', [ReleaseController::class, 'show'])->defaults('_config', [
        'view' => 'shop::cinema.release.list',
    ])->name('shop.cinema.release.list');

    Route::get('/masters/{id}/{slug}', [MasterController::class, 'show'])->defaults('_config', [
        'view' => 'shop::cinema.master.list',
    ])->name('shop.cinema.master.index'); // PWS#13-misc

    Route::get('/mastersearch', [MasterController::class, 'search'])
    ->name('shop.cinema.master.search.index')
    ->defaults('_config', [
        'view' => 'shop::search.searchmaster'
    ]);

    Route::get('/masters', [MasterController::class, 'list'])->defaults('_config', [
        'view' => 'shop::cinema.masters.index',
    ])->name('shop.cinema.master.list'); // PWS#master-list

    Route::post('/masters', [MasterController::class, 'list'])->defaults('_config', [
        'view' => 'shop::cinema.masters.index',
    ])->name('shop.cinema.master.list'); // PWS#230101

    Route::get('/releases', [ReleaseController::class, 'list'])->defaults('_config', [
        'view' => 'shop::cinema.releases.index',
    ])->name('shop.cinema.releases.list'); // PWS#release-list

    Route::post('/releases', [ReleaseController::class, 'list'])->defaults('_config', [
        'view' => 'shop::cinema.releases.index',
    ])->name('shop.cinema.releases.list'); // PWS#02-23

    Route::get('/vietato-minori', function () {
        return view('shop::errors.vt18');
    })->name('shop.cinema.vt18'); // PWS#13-vt18

    Route::get('/api/master/lightbox-imgs/{id}', [MasterController::class, 'getImgsLightbox'])->name('api.master.lightbox-imgs'); // PWS#13-lightbox
    Route::get('/api/release/lightbox-imgs/{id}', [ReleaseController::class, 'getImgsLightbox'])->name('api.release.lightbox-imgs'); // PWS#13-lightbox
});
