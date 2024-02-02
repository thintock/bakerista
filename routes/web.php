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


Route::get('/', function () {
    return view('dashboard');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::group(['middleware' => ['auth', 'approved']], function() { // , 'approved'を追加するとユーザー制御がONになる。
   Route::get('/users/manage', [UsersController::class, 'manage'])->name('users.manage');
   Route::patch('/users/{user}/updateStatus', [UsersController::class, 'updateStatus'])->name('users.updateStatus');
   Route::resource('users', UsersController::class, ['only' => ['index', 'show', 'edit', 'update', 'destroy']]); 
   Route::resource('materials', MaterialsController::class)->except(['show']);
   Route::resource('millMachines', MillMachinesController::class)->except(['show']);
   Route::resource('millPurchaseMaterials', MillPurchaseMaterialsController::class);
   Route::resource('millPolishedMaterials', MillPolishedMaterialsController::class);
   Route::resource('millFlourProductions', MillFlourProductionsController::class)->except(['show']);
   Route::resource('customerRelationCategories', CustomerRelationCategoriesController::class, ['only' => ['index','store','update','destroy']]);
   Route::resource('customerRelations', CustomerRelationsController::class)->except(['show']);
});