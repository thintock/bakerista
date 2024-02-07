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
   Route::resource('users', UsersController::class, ['only' => ['index', 'show', 'edit', 'update']]); 
   Route::resource('materials', MaterialsController::class)->except(['show']);
   Route::resource('millMachines', MillMachinesController::class)->except(['show']);
   Route::resource('millPurchaseMaterials', MillPurchaseMaterialsController::class);
   Route::resource('millPolishedMaterials', MillPolishedMaterialsController::class);
   Route::resource('millFlourProductions', MillFlourProductionsController::class)->except(['show']);
   Route::resource('customerRelationCategories', CustomerRelationCategoriesController::class, ['only' => ['index','store','update','destroy']]);
   Route::resource('customerRelations', CustomerRelationsController::class)->except(['show']);
});