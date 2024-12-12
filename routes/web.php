<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FridgeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::controller(FridgeController::class)->middleware(['auth'])->group(function(){
    Route::get('/', 'index')->name('fridge.index');
    Route::get('/seasoning','indexSeasoning')->name('fridge.indexSeasoning');
    Route::post('/', 'store')->name('fridge.store');
    Route::post('/seasoning', 'storeSeasoning')->name('fridge.storeSeasoning');
    Route::delete('/', 'delete')->name('fridge.delete');
    Route::get('/search', 'searchRecipes')->name('recipe.search');
    Route::get('/search/recipe_show/{id}', 'show')->name('recipe.show');
    Route::get('/mypage', 'mypage')->name('fridge.mypage');
    Route::get('/mypage/recipe_list', 'recipe_list')->name('recipe.list');
    Route::get('/mypage/recipe_list/{id}/edit', 'edit')->name('recipe.edit');
    Route::put('/mypage/recipe_list/{id}', 'update')->name('recipe.update');
    Route::get('/mypage/recipe_list/recipe_create', 'recipe_create')->name('recipe.create');
    Route::post('/mypage/recipe_list/recipe_create', 'recipe_store')->name('recipe.store');
    Route::post('/shoplist/add/{id}', 'addToShopList')->name('shopList.add');
    Route::get('/shoplist', 'showShopList')->name('shopList.show');
    Route::delete('/shoplist/delete/{id}', 'deleteFromShopList')->name('shopList.delete');
});

require __DIR__.'/auth.php';
