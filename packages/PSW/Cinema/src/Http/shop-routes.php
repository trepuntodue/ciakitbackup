<?php

Route::group([
        'prefix'     => 'cinema',
        'middleware' => ['web', 'theme', 'locale', 'currency']
    ], function () {

        Route::get('/', 'PSW\Cinema\Http\Controllers\Shop\CinemaController@index')->defaults('_config', [
            'view' => 'cinema::shop.index',
        ])->name('shop.cinema.index');

});