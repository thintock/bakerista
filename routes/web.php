<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\MaterialsController;
use App\Http\Controllers\MillMachinesController;
use App\Http\Controllers\MillPurchaseMaterialsController;
use App\Http\Controllers\MillPolishedMaterialsController;
use App\Http\Controllers\MillFlourProductionsController;
use App\Http\Controllers\CustomerRelationCategoriesController;
use App\Http\Controllers\CustomerRelationsController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\SupplyItemsController;
use App\Http\Controllers\SupplyOrdersController;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

// Authルーティング定義読み込み
require __DIR__.'/auth.php';

// auth認証のみ。管理者承認なしで表示させる。
Route::group(['middleware' => ['auth']],function() {
   Route::delete('/users/{user}',[UsersController::class,'destroy'])->name('users.destroy');
   Route::get('/unapproved', function () {return view('unapproved');})->middleware(['auth'])->name('unapproved');
});

// auth認証＋is_approved認証
Route::group(['middleware' => ['auth', 'approved']], function() { // , 'approved'を追加するとユーザー制御がONになる。
   Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard'); 
   Route::get('/', function () {return view('dashboard');})->name('home');
   Route::get('/users/manage', [UsersController::class, 'manage'])->name('users.manage');
   Route::patch('/users/{user}/updateStatus', [UsersController::class, 'updateStatus'])->name('users.updateStatus');
   Route::get('/supplyItems/{id}/generateQr', [SupplyItemsController::class, 'generateQr'])->name('supplyItems.generateQr');
   Route::get('/supplyOrders/orderRequest', [SupplyOrdersController::class, 'orderRequest'])->name('supplyOrders.orderRequest');
   Route::post('/supplyOrders/storeRequest', [SupplyOrdersController::class, 'storeRequest'])->name('supplyOrders.storeRequest');
   Route::get('/supplyOrders/orderEntry', [SupplyOrdersController::class, 'orderEntry'])->name('supplyOrders.orderEntry');
   Route::post('/supplyOrders/storeEntry', [SupplyOrdersController::class, 'storeEntry'])->name('supplyOrders.storeEntry');
   Route::post('/supplyOrders/updateEntry', [SupplyOrdersController::class, 'updateEntry'])->name('supplyOrders.updateEntry');
   Route::get('/supplyOrders/orderExecute', [SupplyOrdersController::class, 'orderExecute'])->name('supplyOrders.orderExecute');
   Route::post('/supplyOrders/storeExecute', [SupplyOrdersController::class, 'storeExecute'])->name('supplyOrders.storeExecute');
   // 以下resource
   Route::resource('users', UsersController::class, ['only' => ['index', 'show', 'edit', 'update']]); 
   Route::resource('materials', MaterialsController::class)->except(['show']);
   Route::resource('millMachines', MillMachinesController::class)->except(['show']);
   Route::resource('millPurchaseMaterials', MillPurchaseMaterialsController::class);
   Route::resource('millPolishedMaterials', MillPolishedMaterialsController::class);
   Route::resource('millFlourProductions', MillFlourProductionsController::class)->except(['show']);
   Route::resource('customerRelationCategories', CustomerRelationCategoriesController::class, ['only' => ['index','store','update','destroy']]);
   Route::resource('customerRelations', CustomerRelationsController::class)->except(['show']);
   Route::resource('locations', LocationsController::class, ['only' => ['index','store','update','destroy']]);
   Route::resource('companies', CompaniesController::class)->except(['show']);
   Route::resource('supplyItems', SupplyItemsController::class)->except(['show']);
   Route::resource('supplyOrders', SupplyOrdersController::class);
});