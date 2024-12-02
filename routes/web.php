<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FridgeController;

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

Route::get('/', [FridgeController::class, 'index'])->name('fridge.index');
Route::get('/seasoning', [FridgeController::class, 'indexSeasoning'])->name('fridge.indexSeasoning');
Route::post('/', [FridgeController::class, 'store'])->name('fridge.store');
Route::post('/seasoning', [FridgeController::class, 'storeSeasoning'])->name('fridge.storeSeasoning');
Route::delete('/', [FridgeController::class, 'delete'])->name('fridge.delete');
Route::get('/search', [FridgeController::class, 'searchRecipes'])->name('recipe.search');
Route::get('/search/recipe_show/{id}', [FridgeController::class, 'show'])->name('recipe.show');
Route::get('/mypage', [FridgeController::class, 'mypage'])->name('fridge.mypage');
Route::get('/mypage/recipe_list', [FridgeController::class, 'recipe_list'])->name('recipe.list');
Route::get('/mypage/recipe_list/{id}/edit', [FridgeController::class, 'edit'])->name('recipe.edit');
Route::put('/mypage/recipe_list/{id}', [FridgeController::class, 'update'])->name('recipe.update');
Route::get('/mypage/recipe_list/recipe_create', [FridgeController::class, 'recipe_create'])->name('recipe.create');
Route::post('/mypage/recipe_list/recipe_create', [FridgeController::class, 'recipe_store'])->name('recipe.store');