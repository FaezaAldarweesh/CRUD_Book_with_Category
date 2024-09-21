<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;

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


Route::controller(AuthController::class)->group(function () {
    Route::post('register',[AuthController::class, 'register']);
    Route::post('login', 'login');
});

Route::group(['middleware' => ['auth:api']], function () {
    // protected routes go here
    Route::post('logout',[AuthController::class ,'logout']); 
    Route::post('refresh', [AuthController::class ,'refresh']);
    
    //book routes
    Route::apiResource('books', BookController::class);
    Route::get('all_trashed_book', [BookController::class, 'all_trashed_book']);
    Route::get('restore_book/{book_id}', [BookController::class, 'restore']);
    Route::delete('forceDelete_book/{book_id}', [BookController::class, 'forceDelete']);

    //category routes
    Route::apiResource('categories', CategoryController::class);
    Route::get('all_trashed_category', [CategoryController::class, 'all_trashed_category']);
    Route::get('restore_category/{category_id}', [CategoryController::class, 'restore']);
    Route::delete('forceDelete_category/{category_id}', [CategoryController::class, 'forceDelete']);
});
