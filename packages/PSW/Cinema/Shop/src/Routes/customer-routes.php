<?php

use Illuminate\Support\Facades\Route;

//use PSW\Cinema\Customer\Http\Controllers\ReleaseController;
use PSW\Cinema\Http\Controllers\Admin\Customer\ReleaseController;
use PSW\Cinema\Http\Controllers\Admin\Customer\MasterController; // PWS#chiusura
use PSW\Cinema\Http\Controllers\Admin\Customer\CustomerController; // PWS#13-release
use PSW\Cinema\Http\Controllers\Admin\Customer\ProductController; // PWS#prodotti



Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {
    /**
     * Cart merger middleware. This middleware will take care of the items
     * which are deactivated at the time of buy now functionality. If somehow
     * user redirects without completing the checkout then this will merge
     * full cart.
     *
     * If some routes are not able to merge the cart, then place the route in this
     * group.
     */
    Route::group(['middleware' => ['cart.merger']], function () {
        Route::prefix('customer')->group(function () {
             /**
             * Customer authenticated routes. All the below routes only be accessible
             * if customer is authenticated.
             */
            Route::group(['middleware' => ['customer']], function () {
                /**
                 * Customer account. All the below routes are related to
                 * customer account details.
                 */
                Route::prefix('account')->group(function () {
                    /**
                     * Release.
                     */
                    Route::get('releases', [ReleaseController::class, 'index'])->defaults('_config', [
                        'view' => 'shop::customers.account.release.index',
                    ])->name('customer.release.index');

                    /**
                     * Favorites.
                     */
                    Route::get('favorites', [ReleaseController::class, 'favorites'])->defaults('_config', [
                        'view' => 'shop::customers.account.release.favorites',
                    ])->name('customer.release.favorites'); // PWS#finale

                    Route::get('releases/create/{id?}', [ReleaseController::class, 'create'])->defaults('_config', [
                        'view' => 'shop::customers.account.release.create',
                    ])->name('customer.release.create'); // PWS#230101

                    Route::post('customer/addtocollection', [CustomerController::class, 'addToCollection'])
                    ->name('customer.addToCollection'); // PWS#13-release

                    Route::post('releases/create', [ReleaseController::class, 'store'])->defaults('_config', [
                        //'view'     => 'shop::customers.account.release.release',
                        'redirect' => 'customer.release.index',
                    ])->name('customer.release.store');

                    Route::get('master/create', [MasterController::class, 'create'])->defaults('_config', [
                        'view' => 'shop::customers.account.master.create',
                    ])->name('customer.master.create'); // PWS#chiusura

                    Route::post('master/send', [MasterController::class, 'send'])->defaults('_config', [
                        'redirect' => 'customer.release.index',
                    ])->name('customer.master.send');

                    // Route::get('releases/edit/{id}', [ReleaseController::class, 'edit'])->defaults('_config', [
                    //     'view' => 'shop::customers.account.release.edit',
                    // ])->name('customer.release.edit');

                    // Route::put('releases/edit/{id}', [ReleaseController::class, 'update'])->defaults('_config', [
                    //     'redirect' => 'customer.release.index',
                    // ])->name('customer.release.update');

                    //  Route::delete('releases/delete/{id}', [ReleaseController::class, 'destroy'])->defaults('_config', [
                    //     'redirect' => 'customer.release.index',
                    //  ])->name('customer.release.delete'); // PWS#13-release-2

                     Route::delete('releases/delete/{id}', [ReleaseController::class, 'destroy'])->name('release.delete');


                    // Route::get('products', [ProductController::class, 'index'])->defaults('_config', [
                    //     'view' => 'shop::customers.account.product.index',
                    // ])->name('customer.product.index'); // PWS#prod

                    // Route::get('products/create', [ProductController::class, 'create'])->defaults('_config', [
                    //     'view' => 'shop::customers.account.product.create',
                    // ])->name('customer.product.create'); // PWS#prod

                    // Route::get('products/create/{id}', [ProductController::class, 'createFromRelease'])->defaults('_config', [
                    //     'view' => 'shop::customers.account.product.create-step-two',
                    // ])->name('customer.product.create-step-two'); // PWS#prod

                    // Route::post('products/create/{id}', [ProductController::class, 'store'])->defaults('_config', [
                    //     'redirect' => 'customer.product.index',
                    // ])->name('customer.product.store');

                });
            });
        });
    });
});
