<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ LoginController, CategoryController, ProduitController,
    ApprovisionController, SortieController, UserController, TypeperteController, PerteProductController,
    DetteController, RemboursementController, ClientController, FournisseurController };

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'backend'], function () {
    Route::post('login', [LoginController::class, 'login'])->name('login');

      // protected routes
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('logout', 'LoginController@logout')->name('logout');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('users.index');
        Route::post('/users', 'store')->name('users.store');
        Route::get('/users/{id}', 'show')->name('users.show');
        Route::post('/users/update', 'update')->name('users.update');
        Route::get('/users/{id}', 'destroy')->name('users.destroy');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categorie', 'index')->name('categorie.index');
        Route::post('/categorie', 'store')->name('categorie.store');
        Route::get('/categorie/{id}', 'show')->name('categorie.show');
        Route::post('/categorie/update', 'update')->name('categorie.update');
        Route::get('/categorie/{id}', 'destroy')->name('categorie.destroy');
    });

    Route::controller(ProduitController::class)->group(function () {
        Route::get('/product', 'index')->name('product.index');
        Route::post('/product', 'store')->name('product.store');
        Route::get('/product/{id}', 'show')->name('product.show');
        Route::post('/product/update', 'update')->name('product.update');
        Route::get('/product/{id}', 'destroy')->name('product.destroy');
    });

    Route::controller(ApprovisionController::class)->group(function () {
        Route::get('/supply', 'index')->name('supply.index');
        Route::post('/supply', 'store')->name('supply.store');
        Route::get('/supply/{id}', 'show')->name('supply.show');
        Route::put('/supply/{id}', 'update')->name('supply.update');
        Route::get('/supply/{id}', 'destroy')->name('supply.destroy');
    });

    Route::controller(SortieController::class)->group(function () {
        Route::get('/sales', 'index')->name('sales.index');
        Route::post('/sales', 'store')->name('sales.store');
        Route::post('/customer', 'customer')->name('customer');
        Route::get('/sales/{id}', 'show')->name('sales.show');
        Route::put('/sales/{id}', 'update')->name('sales.update');
        Route::get('/sales/{id}', 'destroy')->name('sales.destroy');
    });

    Route::controller(PerteProductController::class)->group(function () {
        Route::get('/product-loss', 'index')->name('product-loss.index');
        Route::post('/product-loss', 'store')->name('product-loss.store');
        Route::get('/product-loss/{id}', 'show')->name('product-loss.show');
        Route::put('/product-loss/{id}', 'update')->name('product-loss.update');
        Route::get('/product-loss/{id}', 'destroy')->name('product-loss.destroy');
    });

    Route::controller(TypeperteController::class)->group(function () {
        Route::get('/type-loss', 'index')->name('type-loss.index');
        Route::post('/type-loss', 'store')->name('type-loss.store');
        Route::get('/type-loss/{id}', 'show')->name('type-loss.show');
        Route::post('/type-loss/update', 'update')->name('type-loss.update');
        Route::get('/type-loss/{id}', 'destroy')->name('type-loss.destroy');
    });

    Route::controller(DetteController::class)->group(function () {
        Route::get('/dette', 'index')->name('dette.index');
        Route::post('/dette', 'store')->name('dette.store');
        Route::get('/dette/{id}', 'show')->name('dette.show');
        Route::post('/dette/update', 'update')->name('dette.update');
        Route::get('/dette/{id}', 'destroy')->name('dette.destroy');
    });

   Route::controller(RemboursementController::class)->group(function () {
        Route::get('/paiement', 'index')->name('paiement.index');
        Route::post('/paiement', 'store')->name('paiement.store');
        Route::get('/paiement/{id}', 'show')->name('paiement.show');
        Route::post('/paiement/update', 'update')->name('paiement.update');
        Route::get('/paiement/{id}', 'destroy')->name('paiement.destroy');
    });

    Route::controller(ClientController::class)->group(function () {
        Route::get('/client', 'index')->name('client.index');
        Route::post('/client', 'store')->name('client.store');
        Route::get('/client/{id}', 'show')->name('client.show');
        Route::post('/client/update', 'update')->name('client.update');
        Route::get('/client/{id}', 'destroy')->name('client.destroy');
    });

    
    Route::controller(FournisseurController::class)->group(function () {
        Route::get('/fournisseur', 'index')->name('client.index');
        Route::post('/fournisseur', 'store')->name('client.store');
        Route::get('/fournisseur/{id}', 'show')->name('client.show');
        Route::post('/fournisseur/update', 'update')->name('client.update');
        Route::get('/fournisseur/{id}', 'destroy')->name('client.destroy');
    });
});
