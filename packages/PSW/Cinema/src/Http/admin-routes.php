<?php
use Illuminate\Support\Facades\Route;
use PSW\Cinema\Http\Controllers\Admin\Film\ReleaseController;
use PSW\Cinema\Http\Controllers\Admin\Film\MastersController;
use PSW\Cinema\Http\Controllers\Admin\Film\GeneriController;
use PSW\Cinema\Http\Controllers\Admin\Film\SubgeneriController;
use PSW\Cinema\Http\Controllers\Admin\Film\ActoryController;
use PSW\Cinema\Http\Controllers\Admin\Film\SceneggiatoriController;
use PSW\Cinema\Http\Controllers\Admin\Film\RegistiController;
use PSW\Cinema\Http\Controllers\Admin\Film\CompositoriController;
use PSW\Cinema\Http\Controllers\Admin\Film\CasaproduzioniController;
use PSW\Cinema\Http\Controllers\Admin\Film\LingueController;
use PSW\Cinema\Http\Controllers\Admin\CinemaController;

Route::group([
        'prefix'        => 'admin/cinema',
        'middleware'    => ['web', 'admin']
    ], function () {

       //Route::get('', 'PSW\Cinema\Http\Controllers\Admin\CinemaController@index')->defaults('_config', [
        // Route::get('',  [CinemaController::class,'index'])->defaults('_config', [
        //     'redirect' => 'admin.cinema.master.index',
        // ])->name('admin.cinema.master.index');

        /**
         * Release routes.
         */
        Route::get('/releases', [ReleaseController::class, 'index'])->defaults('_config', [
            'view' => 'cinema::admin.release.index',
        ])->name('admin.cinema.release.index');

        Route::get('/releases/create', [ReleaseController::class, 'create'])->defaults('_config', [
            'view' => 'cinema::admin.release.create',
        ])->name('admin.cinema.release.create');

        Route::post('/releases/create', [ReleaseController::class, 'store'])->defaults('_config', [
            'redirect' => 'admin.cinema.release.edit',
        ])->name('admin.cinema.release.store');

        Route::get('releases/copy/{id}', [ReleaseController::class, 'copy'])->defaults('_config', [
            'view' => 'cinema::admin.release.edit',
        ])->name('admin.cinema.release.copy');

        Route::get('/releases/edit/{id}', [ReleaseController::class, 'edit'])->defaults('_config', [
            'view' => 'cinema::admin.release.edit',
        ])->name('admin.cinema.release.edit');

        Route::put('/releases/edit/{id}', [ReleaseController::class, 'update'])->defaults('_config', [
            'redirect' => 'admin.cinema.release.index',
        ])->name('admin.cinema.release.update');

        Route::post('/releases/delete/{id}', [ReleaseController::class, 'destroy'])->name('admin.cinema.release.delete');

        Route::post('releases/massaction', [ReleaseController::class, 'massActionHandler'])->name('admin.cinema.release.massaction');

        Route::post('releases/massdelete', [ReleaseController::class, 'massDestroy'])->defaults('_config', [
            'redirect' => 'admin.cinema.release.index',
        ])->name('admin.cinema.release.massdelete');

        Route::post('releases/massupdate', [ReleaseController::class, 'massUpdate'])->defaults('_config', [
            'redirect' => 'admin.cinema.release.index',
        ])->name('admin.cinema.release.massupdate');

        Route::post('/releases/upload-file/{id}', [ReleaseController::class, 'uploadLink'])->name('admin.cinema.release.upload_link');
        Route::post('/releases/upload-sample/{id}', [ReleaseController::class, 'uploadSample'])->name('admin.cinema.release.upload_sample');


        // /**
        //  * Master routes.
        //  */
        Route::get('/masters', [MastersController::class, 'index'])->defaults('_config', [
            'view' => 'cinema::admin.master.index',
        ])->name('admin.cinema.master.index');

        Route::get('/masters/create', [MastersController::class, 'create'])->defaults('_config', [
            'view' => 'cinema::admin.master.create',
        ])->name('admin.cinema.master.create');

        Route::post('/masters/create', [MastersController::class, 'store'])->defaults('_config', [
         //  'view'     => 'cinema::admin.master.master',
           'redirect' => 'admin.cinema.master.edit',
        ])->name('admin.cinema.master.store');

        Route::get('masters/copy/{id}', [MastersController::class, 'copy'])->defaults('_config', [
            'view' => 'cinema::admin.master.edit',
        ])->name('admin.cinema.master.copy');

        Route::get('/masters/edit/{id}', [MastersController::class, 'edit'])->defaults('_config', [
            'view' => 'cinema::admin.master.edit',
        ])->name('admin.cinema.master.edit');

        Route::put('/masters/edit/{id}', [MastersController::class, 'update'])->defaults('_config', [
            'redirect' => 'admin.cinema.master.index',
        ])->name('admin.cinema.master.update');

        Route::post('/masters/delete/{id}', [MastersController::class, 'destroy'])->name('admin.cinema.master.delete');

        Route::post('masters/massaction', [MastersController::class, 'massActionHandler'])->name('admin.cinema.master.massaction');

        Route::post('masters/massdelete', [MastersController::class, 'massDestroy'])->defaults('_config', [
            'redirect' => 'admin.cinema.master.index',
        ])->name('admin.cinema.master.massdelete');

        Route::post('masters/massupdate', [MastersController::class, 'massUpdate'])->defaults('_config', [
            'redirect' => 'admin.cinema.master.index',
        ])->name('admin.cinema.master.massupdate');

        Route::get('releas/search', [ReleaseController::class, 'masterLinkSearch'])->defaults('_config', [
            'view' => 'cinema::admin.release.create',
        ])->name('admin.cinema.release.masterLinkSearch');

        Route::post('/masters/upload-file/{id}', [MastersController::class, 'uploadLink'])->name('admin.cinema.master.upload_link');

        Route::post('/masters/upload-sample/{id}', [MastersController::class, 'uploadSample'])->name('admin.cinema.master.upload_sample');

         /**
         * Generi routes.
         */
        Route::get('/generi', [GeneriController::class, 'index'])->defaults('_config', [
            'view' => 'cinema::admin.genere.index',
        ])->name('admin.cinema.genere.index');

        Route::get('/generi/create', [GeneriController::class, 'create'])->defaults('_config', [
            'view' => 'cinema::admin.genere.create',
        ])->name('admin.cinema.genere.create');

        Route::post('/generi/create', [GeneriController::class, 'store'])->defaults('_config', [
          // 'view'     => 'cinema::admin.genere.genere',
           'redirect' => 'admin.cinema.genere.index',
        ])->name('admin.cinema.genere.store');

            Route::get('generi/copy/{id}', [GeneriController::class, 'copy'])->defaults('_config', [
                'view' => 'cinema::admin.genere.edit',
            ])->name('admin.cinema.genere.copy');

            Route::get('/generi/edit/{id}', [GeneriController::class, 'edit'])->defaults('_config', [
                'view' => 'cinema::admin.genere.edit',
            ])->name('admin.cinema.genere.edit');

            Route::put('/generi/edit/{id}', [GeneriController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.cinema.genere.index',
            ])->name('admin.cinema.genere.update');

        // Route::post('/masters/delete/{id}', [MastersController::class, 'destroy'])->name('admin.cinema.master.delete');

        // Route::post('masters/massaction', [MastersController::class, 'massActionHandler'])->name('admin.cinema.master.massaction');

        // Route::post('masters/massdelete', [MastersController::class, 'massDestroy'])->defaults('_config', [
        //     'redirect' => 'admin.cinema.master.index',
        // ])->name('admin.cinema.master.massdelete');

        // Route::post('masters/massupdate', [MastersController::class, 'massUpdate'])->defaults('_config', [
        //     'redirect' => 'admin.cinema.master.index',
        // ])->name('admin.cinema.master.massupdate');


     /**
         * Subgener routes.
         */
        Route::get('/subgeneri', [SubgeneriController::class, 'index'])->defaults('_config', [
            'view' => 'cinema::admin.subgenere.index',
        ])->name('admin.cinema.subgenere.index');

        Route::get('/subgeneri/create', [SubgeneriController::class, 'create'])->defaults('_config', [
            'view' => 'cinema::admin.subgenere.create',
        ])->name('admin.cinema.subgenere.create');

        Route::post('/subgeneri/create', [SubgeneriController::class, 'store'])->defaults('_config', [
          // 'view'     => 'cinema::admin.subgenere.subgenere',
           'redirect' => 'admin.cinema.subgenere.index',
        ])->name('admin.cinema.subgenere.store');

            Route::get('subgeneri/copy/{id}', [SubgeneriController::class, 'copy'])->defaults('_config', [
                'view' => 'cinema::admin.subgenere.edit',
            ])->name('admin.cinema.subgenere.copy');

            Route::get('/subgeneri/edit/{id}', [SubgeneriController::class, 'edit'])->defaults('_config', [
                'view' => 'cinema::admin.subgenere.edit',
            ])->name('admin.cinema.subgenere.edit');

            Route::put('/subgeneri/edit/{id}', [SubgeneriController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.cinema.subgenere.index',
            ])->name('admin.cinema.subgenere.update');

        // Route::post('/masters/delete/{id}', [MastersController::class, 'destroy'])->name('admin.cinema.master.delete');

        // Route::post('masters/massaction', [MastersController::class, 'massActionHandler'])->name('admin.cinema.master.massaction');

        // Route::post('masters/massdelete', [MastersController::class, 'massDestroy'])->defaults('_config', [
        //     'redirect' => 'admin.cinema.master.index',
        // ])->name('admin.cinema.master.massdelete');

        // Route::post('masters/massupdate', [MastersController::class, 'massUpdate'])->defaults('_config', [
        //     'redirect' => 'admin.cinema.master.index',
        // ])->name('admin.cinema.master.massupdate');
        /**
         * attori routes.
         */
        Route::get('/attori', [ActoryController::class, 'index'])->defaults('_config', [
            'view' => 'cinema::admin.attore.index',
        ])->name('admin.cinema.attore.index');

        Route::get('/attori/create', [ActoryController::class, 'create'])->defaults('_config', [
            'view' => 'cinema::admin.attore.create',
        ])->name('admin.cinema.attore.create');

        Route::post('/attori/create', [ActoryController::class, 'store'])->defaults('_config', [
          // 'view'     => 'cinema::admin.attore.attore',
           'redirect' => 'admin.cinema.attore.index',
        ])->name('admin.cinema.attore.store');

            Route::get('attori/copy/{id}', [ActoryController::class, 'copy'])->defaults('_config', [
                'view' => 'cinema::admin.attore.edit',
            ])->name('admin.cinema.attore.copy');

            Route::get('/attori/edit/{id}', [ActoryController::class, 'edit'])->defaults('_config', [
                'view' => 'cinema::admin.attore.edit',
            ])->name('admin.cinema.attore.edit');

            Route::put('/attori/edit/{id}', [ActoryController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.cinema.attore.index',
            ])->name('admin.cinema.attore.update');

        /**
         * sceneggiatori routes.
         */
        Route::get('/sceneggiatori', [SceneggiatoriController::class, 'index'])->defaults('_config', [
            'view' => 'cinema::admin.sceneggiatore.index',
        ])->name('admin.cinema.sceneggiatore.index');

        Route::get('/sceneggiatori/create', [SceneggiatoriController::class, 'create'])->defaults('_config', [
            'view' => 'cinema::admin.sceneggiatore.create',
        ])->name('admin.cinema.sceneggiatore.create');

        Route::post('/sceneggiatori/create', [SceneggiatoriController::class, 'store'])->defaults('_config', [
          // 'view'     => 'cinema::admin.sceneggiatore.sceneggiatore',
           'redirect' => 'admin.cinema.sceneggiatore.index',
        ])->name('admin.cinema.sceneggiatore.store');

            Route::get('sceneggiatori/copy/{id}', [SceneggiatoriController::class, 'copy'])->defaults('_config', [
                'view' => 'cinema::admin.sceneggiatore.edit',
            ])->name('admin.cinema.sceneggiatore.copy');

            Route::get('/sceneggiatori/edit/{id}', [SceneggiatoriController::class, 'edit'])->defaults('_config', [
                'view' => 'cinema::admin.sceneggiatore.edit',
            ])->name('admin.cinema.sceneggiatore.edit');

            Route::put('/sceneggiatori/edit/{id}', [SceneggiatoriController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.cinema.sceneggiatore.index',
            ])->name('admin.cinema.sceneggiatore.update');

        /**
         * registi routes.
         */
        Route::get('/registi', [RegistiController::class, 'index'])->defaults('_config', [
            'view' => 'cinema::admin.regista.index',
        ])->name('admin.cinema.regista.index');

        Route::get('/registi/create', [RegistiController::class, 'create'])->defaults('_config', [
            'view' => 'cinema::admin.regista.create',
        ])->name('admin.cinema.regista.create');

        Route::post('/registi/create', [RegistiController::class, 'store'])->defaults('_config', [
          // 'view'     => 'cinema::admin.regista.regista',
           'redirect' => 'admin.cinema.regista.index',
        ])->name('admin.cinema.regista.store');

            Route::get('registi/copy/{id}', [RegistiController::class, 'copy'])->defaults('_config', [
                'view' => 'cinema::admin.regista.edit',
            ])->name('admin.cinema.regista.copy');

            Route::get('/registi/edit/{id}', [RegistiController::class, 'edit'])->defaults('_config', [
                'view' => 'cinema::admin.regista.edit',
            ])->name('admin.cinema.regista.edit');

            Route::put('/registi/edit/{id}', [RegistiController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.cinema.regista.index',
            ])->name('admin.cinema.regista.update');

              /**
         * compositore routes.
         */
        Route::get('/compositori', [CompositoriController::class, 'index'])->defaults('_config', [
            'view' => 'cinema::admin.compositore.index',
        ])->name('admin.cinema.compositore.index');

        Route::get('/compositori/create', [CompositoriController::class, 'create'])->defaults('_config', [
            'view' => 'cinema::admin.compositore.create',
        ])->name('admin.cinema.compositore.create');

        Route::post('/compositori/create', [CompositoriController::class, 'store'])->defaults('_config', [
          // 'view'     => 'cinema::admin.compositore.compositore',
           'redirect' => 'admin.cinema.compositore.index',
        ])->name('admin.cinema.compositore.store');

            Route::get('compositori/copy/{id}', [CompositoriController::class, 'copy'])->defaults('_config', [
                'view' => 'cinema::admin.compositore.edit',
            ])->name('admin.cinema.compositore.copy');

            Route::get('/compositori/edit/{id}', [CompositoriController::class, 'edit'])->defaults('_config', [
                'view' => 'cinema::admin.compositore.edit',
            ])->name('admin.cinema.compositore.edit');

            Route::put('/compositori/edit/{id}', [CompositoriController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.cinema.compositore.index',
            ])->name('admin.cinema.compositore.update');


        /**
         * Casaproduzioni routes.
         */
        Route::get('/casaproduzioni', [CasaproduzioniController::class, 'index'])->defaults('_config', [
            'view' => 'cinema::admin.casaproduzione.index',
        ])->name('admin.cinema.casaproduzione.index');

        Route::get('/casaproduzioni/create', [CasaproduzioniController::class, 'create'])->defaults('_config', [
            'view' => 'cinema::admin.casaproduzione.create',
        ])->name('admin.cinema.casaproduzione.create');

        Route::post('/casaproduzioni/create', [CasaproduzioniController::class, 'store'])->defaults('_config', [
           //'view'     => 'cinema::admin.casaproduzione.casaproduzione',
           'redirect' => 'admin.cinema.casaproduzione.index',
        ])->name('admin.cinema.casaproduzione.store');

            Route::get('casaproduzioni/copy/{id}', [CasaproduzioniController::class, 'copy'])->defaults('_config', [
                'view' => 'cinema::admin.casaproduzione.edit',
            ])->name('admin.cinema.casaproduzione.copy');

            Route::get('/casaproduzioni/edit/{id}', [CasaproduzioniController::class, 'edit'])->defaults('_config', [
                'view' => 'cinema::admin.casaproduzione.edit',
            ])->name('admin.cinema.casaproduzione.edit');

            Route::put('/casaproduzioni/edit/{id}', [CasaproduzioniController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.cinema.casaproduzione.index',
            ])->name('admin.cinema.casaproduzione.update');

             /**
         * Lingue routes.
         */
        Route::get('/lingue', [LingueController::class, 'index'])->defaults('_config', [
            'view' => 'cinema::admin.lingua.index',
        ])->name('admin.cinema.lingua.index');

        Route::get('/lingue/create', [LingueController::class, 'create'])->defaults('_config', [
            'view' => 'cinema::admin.lingua.create',
        ])->name('admin.cinema.lingua.create');

        Route::post('/lingue/create', [LingueController::class, 'store'])->defaults('_config', [
           //'view'     => 'cinema::admin.lingua.lingua',
           'redirect' => 'admin.cinema.lingua.index',
        ])->name('admin.cinema.lingua.store');

            Route::get('lingue/copy/{id}', [LingueController::class, 'copy'])->defaults('_config', [
                'view' => 'cinema::admin.lingua.edit',
            ])->name('admin.cinema.lingua.copy');

            Route::get('/lingue/edit/{id}', [LingueController::class, 'edit'])->defaults('_config', [
                'view' => 'cinema::admin.lingua.edit',
            ])->name('admin.cinema.lingua.edit');

            Route::put('/lingue/edit/{id}', [LingueController::class, 'update'])->defaults('_config', [
                'redirect' => 'admin.cinema.lingua.index',
            ])->name('admin.cinema.lingua.update');






        // Route::get('/releases', 'PSW\Cinema\Http\Controllers\Admin\cinema\ReleaseController@index')->defaults('_config', [
        //     'view' => 'cinema::admin.release.index',
        // ])->name('admin.cinema.release.index'); // PWS#02-23

        // Route::get('/masters', 'PSW\Cinema\Http\Controllers\Admin\cinema\MastersController@index')->defaults('_config', [
        //     'view' => 'admin.cinema.master.index',
        // ])->name('admin.cinema.master.index');


});
