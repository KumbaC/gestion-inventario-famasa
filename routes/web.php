<?php

declare(strict_types=1);

use App\Http\Controllers\Backend\ActionLogController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ModulesController;
use App\Http\Controllers\Backend\PermissionsController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Backend\SettingsController;
use App\Http\Controllers\Backend\ProfilesController;
use App\Http\Controllers\Backend\TranslationController;
use App\Http\Controllers\Backend\UserLoginAsController;
use App\Http\Controllers\Backend\LocaleController;
use App\Http\Controllers\Backend\PostsController;
use App\Http\Controllers\Backend\TermsController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@redirectAdmin')->name('index');
Route::get('/home', 'HomeController@index')->name('home');

/**
 * Admin routes.
 */
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('roles', RolesController::class);
    Route::delete('roles/delete/bulk-delete', [RolesController::class, 'bulkDelete'])->name('roles.bulk-delete');
    
    // Permissions Routes.
    Route::get('/permissions', [PermissionsController::class, 'index'])->name('permissions.index');
    Route::get('/permissions/{id}', [PermissionsController::class, 'show'])->name('permissions.show');

    // Modules Routes.
    Route::get('/modules', [ModulesController::class, 'index'])->name('modules.index');
    Route::post('/modules/toggle-status/{module}', [ModulesController::class, 'toggleStatus'])->name('modules.toggle-status');
    Route::post('/modules/upload', [ModulesController::class, 'store'])->name('modules.store');
    Route::delete('/modules/{module}', [ModulesController::class, 'destroy'])->name('modules.delete');

    // Settings Routes.
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'store'])->name('settings.store');

    // Translation Routes
    Route::get('/translations', [TranslationController::class, 'index'])->name('translations.index');
    Route::post('/translations', [TranslationController::class, 'update'])->name('translations.update');
    Route::post('/translations/create', [TranslationController::class, 'create'])->name('translations.create');

    // Login as & Switch back
    Route::resource('users', UsersController::class);
    Route::delete('users/delete/bulk-delete', [UsersController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::get('users/{id}/login-as', [UserLoginAsController::class, 'loginAs'])->name('users.login-as');
    Route::post('users/switch-back', [UserLoginAsController::class, 'switchBack'])->name('users.switch-back');

    Route::get('users/salir', [UserLoginAsController::class, 'logout'])->name('users.logout');

    // Position Routes.
    Route::get('/position', [App\Http\Controllers\Backend\PositionController::class, 'index'])->name('position.index');
    Route::get('/position/create', [App\Http\Controllers\Backend\PositionController::class, 'create'])->name('position.create');
    Route::post('/position', [App\Http\Controllers\Backend\PositionController::class, 'store'])->name('position.store');
    Route::get('/position/{id}', [App\Http\Controllers\Backend\PositionController::class, 'show'])->name('position.show');
    Route::get('/position/{id}/edit', [App\Http\Controllers\Backend\PositionController::class, 'edit'])->name('position.edit');
    Route::put('/position/{id}', [App\Http\Controllers\Backend\PositionController::class, 'update'])->name('position.update');
    Route::delete('/position/{id}', [App\Http\Controllers\Backend\PositionController::class, 'destroy'])->name('position.destroy');

    // Type Coin Routes.
    Route::get('/type-coins', [App\Http\Controllers\Backend\TypeCoinController::class, 'index'])->name('type-coins.index');
    Route::get('/type-coins/create', [App\Http\Controllers\Backend\TypeCoinController::class, 'create'])->name('type-coins.create');
    Route::post('/type-coins', [App\Http\Controllers\Backend\TypeCoinController::class, 'store'])->name('type-coins.store');
    Route::get('/type-coins/{id}', [App\Http\Controllers\Backend\TypeCoinController::class, 'show'])->name('type-coins.show');
    Route::get('/type-coins/{id}/edit', [App\Http\Controllers\Backend\TypeCoinController::class, 'edit'])->name('type-coins.edit');
    Route::put('/type-coins/{id}', [App\Http\Controllers\Backend\TypeCoinController::class, 'update'])->name('type-coins.update');
    Route::delete('/type-coins/{id}', [App\Http\Controllers\Backend\TypeCoinController::class, 'destroy'])->name('type-coins.destroy');

    // Client Routes.
    Route::get('/clients', [App\Http\Controllers\Backend\ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/create', [App\Http\Controllers\Backend\ClientController::class, 'create'])->name('clients.create');
    Route::post('/clients', [App\Http\Controllers\Backend\ClientController::class, 'store'])->name('clients.store');
    Route::get('/clients/{id}', [App\Http\Controllers\Backend\ClientController::class, 'show'])->name('clients.show');
    Route::get('/clients/{id}/edit', [App\Http\Controllers\Backend\ClientController::class, 'edit'])->name('clients.edit');
    Route::put('/clients/{id}', [App\Http\Controllers\Backend\ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clients/{id}', [App\Http\Controllers\Backend\ClientController::class, 'destroy'])->name('clients.destroy');

    // Inventory Routes.
    Route::get('/inventories', [App\Http\Controllers\Backend\InventoryController::class, 'index'])->name('inventories.index');
    Route::get('/inventories/create', [App\Http\Controllers\Backend\InventoryController::class, 'create'])->name('inventories.create');
    Route::post('/inventories', [App\Http\Controllers\Backend\InventoryController::class, 'store'])->name('inventories.store');
    Route::get('/inventories/{id}', [App\Http\Controllers\Backend\InventoryController::class, 'show'])->name('inventories.show');
    Route::get('/inventories/{id}/edit', [App\Http\Controllers\Backend\InventoryController::class, 'edit'])->name('inventories.edit');
    Route::put('/inventories/{id}', [App\Http\Controllers\Backend\InventoryController::class, 'update'])->name('inventories.update');
    Route::delete('/inventories/{id}', [App\Http\Controllers\Backend\InventoryController::class, 'destroy'])->name('inventories.destroy');

    // Purchase Routes.
    Route::get('/purchases', [App\Http\Controllers\Backend\PurchaseController::class, 'index'])->name('purchases.index');
    Route::get('/purchases/create', [App\Http\Controllers\Backend\PurchaseController::class, 'create'])->name('purchases.create');
    Route::post('/purchases', [App\Http\Controllers\Backend\PurchaseController::class, 'store'])->name('purchases.store');
    Route::get('/purchases/{id}', [App\Http\Controllers\Backend\PurchaseController::class, 'show'])->name('purchases.show');
    Route::get('/purchases/{id}/edit', [App\Http\Controllers\Backend\PurchaseController::class, 'edit'])->name('purchases.edit');
    Route::put('/purchases/{id}', [App\Http\Controllers\Backend\PurchaseController::class, 'update'])->name('purchases.update');
    Route::delete('/purchases/{id}', [App\Http\Controllers\Backend\PurchaseController::class, 'destroy'])->name('purchases.destroy');   


    // Supplier Routes.
    Route::get('/suppliers', [App\Http\Controllers\Backend\SupplierController::class, 'index'])->name('suppliers.index');
    Route::get('/suppliers/create', [App\Http\Controllers\Backend\SupplierController::class, 'create'])->name('suppliers.create');
    Route::post('/suppliers', [App\Http\Controllers\Backend\SupplierController::class, 'store'])->name('suppliers.store');
    Route::get('/suppliers/{id}', [App\Http\Controllers\Backend\SupplierController::class, 'show'])->name('suppliers.show');
    Route::get('/suppliers/{id}/edit', [App\Http\Controllers\Backend\SupplierController::class, 'edit'])->name('suppliers.edit');
    Route::put('/suppliers/{id}', [App\Http\Controllers\Backend\SupplierController::class, 'update'])->name('suppliers.update');
    Route::delete('/suppliers/{id}', [App\Http\Controllers\Backend\SupplierController::class, 'destroy'])->name('suppliers.destroy');

    //Sales Routes.
    Route::get('/sales', [App\Http\Controllers\Backend\SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [App\Http\Controllers\Backend\SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [App\Http\Controllers\Backend\SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{id}', [App\Http\Controllers\Backend\SaleController::class, 'show'])->name('sales.show');
    Route::get('/sales/{id}/edit', [App\Http\Controllers\Backend\SaleController::class, 'edit'])->name('sales.edit');
    Route::put('/sales/{id}', [App\Http\Controllers\Backend\SaleController::class, 'update'])->name('sales.update');
    Route::delete('/sales/{id}', [App\Http\Controllers\Backend\SaleController::class, 'destroy'])->name('sales.destroy');

    //Box Routes.
    Route::get('/box', [App\Http\Controllers\Backend\BoxController::class, 'index'])->name('box.index');
    Route::get('/box/create', [App\Http\Controllers\Backend\BoxController::class, 'create'])->name('box.create');
    Route::post('/box', [App\Http\Controllers\Backend\BoxController::class, 'store'])->name('box.store');
    Route::get('/box/{id}', [App\Http\Controllers\Backend\BoxController::class, 'show'])->name('box.show');
    Route::get('/box/{id}/edit', [App\Http\Controllers\Backend\BoxController::class, 'edit'])->name('box.edit');
    Route::put('/box/{id}', [App\Http\Controllers\Backend\BoxController::class, 'update'])->name('box.update');
    Route::delete('/box/{id}', [App\Http\Controllers\Backend\BoxController::class, 'destroy'])->name('box.destroy');

    // Action Log Routes.
    Route::get('/action-log', [ActionLogController::class, 'index'])->name('actionlog.index');
    
    // Content Management Routes
    
    // Posts/Pages Routes - Dynamic post types
/*     Route::get('/posts/{postType?}', [PostsController::class, 'index'])->name('posts.index');
    Route::get('/posts/{postType}/create', [PostsController::class, 'create'])->name('posts.create');
    Route::post('/posts/{postType}', [PostsController::class, 'store'])->name('posts.store');
    Route::get('/posts/{postType}/{id}', [PostsController::class, 'show'])->name('posts.show');
    Route::get('/posts/{postType}/{id}/edit', [PostsController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{postType}/{id}', [PostsController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{postType}/{id}', [PostsController::class, 'destroy'])->name('posts.destroy');
    Route::delete('/posts/{postType}/delete/bulk-delete', [PostsController::class, 'bulkDelete'])->name('posts.bulk-delete');
    
    // Terms Routes (Categories, Tags, etc.)
    Route::get('/terms/{taxonomy}', [TermsController::class, 'index'])->name('terms.index');
    Route::get('/terms/{taxonomy}/{term}/edit', [TermsController::class, 'edit'])->name('terms.edit');
    Route::post('/terms/{taxonomy}', [TermsController::class, 'store'])->name('terms.store');
    Route::put('/terms/{taxonomy}/{id}', [TermsController::class, 'update'])->name('terms.update');
    Route::delete('/terms/{taxonomy}/{id}', [TermsController::class, 'destroy'])->name('terms.destroy');
    Route::delete('/terms/{taxonomy}/delete/bulk-delete', [TermsController::class, 'bulkDelete'])->name('terms.bulk-delete');

    // Editor Upload Route
    Route::post('/editor/upload', [App\Http\Controllers\Backend\EditorController::class, 'upload'])->name('editor.upload'); */
});

/**
 * Profile routes.
 */
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['auth']], function () {
    Route::get('/edit', [ProfilesController::class, 'edit'])->name('edit');
    Route::put('/update', [ProfilesController::class, 'update'])->name('update');
});

Route::get('/locale/{lang}', [LocaleController::class, 'switch'])->name('locale.switch');
