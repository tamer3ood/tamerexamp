<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Tamers\TamerV1Controller;
use App\Http\Controllers\API\Categories\CategoryController;
use App\Http\Controllers\API\Categories\SubCategoryController;
use App\Http\Controllers\API\Categories\CategoryTypeController;
use App\Http\Controllers\API\Categories\CategoryAttributeController;
use App\Http\Controllers\API\Categories\CategoryAttributeItemController;
use App\Http\Controllers\API\Categories\CategoryElementController;
use App\Http\Controllers\API\Categories\CategoryServiceController;
use App\Http\Controllers\API\Tamers\Orders\TamerOrderV1Controller;

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
//Auth
Route::post('tamers/auth/LOGIN64b4001b47c78', [AuthController::class, 'login']);
Route::post('tamers/auth/REG64b4005b933ce', [AuthController::class, 'register']);
//STORE TAMER
Route::post('tamers/store/STP1TMR6491706fcb506', [TamerV1Controller::class, 'store_stp1']);
Route::post('tamers/store/STP2TMR64947bf287268', [TamerV1Controller::class, 'store_stp2']);
Route::post('tamers/store/STP3TMR6496e7ff131d1', [TamerV1Controller::class, 'store_stp3']);
Route::post('tamers/store/STP4TMR6496e9b767aef', [TamerV1Controller::class, 'store_stp4']);
Route::post('tamers/store/STP5TMR6496e9d30854e', [TamerV1Controller::class, 'store_stp5']);
Route::post('tamers/store/STP6TMR6496e9e159c0b', [TamerV1Controller::class, 'store_stp6']);

//Categories
Route::get('categories/categories', [CategoryController::class, 'index']);
Route::get('categories/sub-categories/{category_id}', [SubCategoryController::class, 'index']);
Route::get('categories/category-type/{sub_category_id}', [CategoryTypeController::class, 'index']);

Route::get('categories/category-attributes/{category_id}', [CategoryAttributeController::class, 'getAttributes']);
Route::get('categories/category-attribute-items', [CategoryAttributeItemController::class, 'index']);
Route::get('categories/category-elements/{category_id}', [CategoryElementController::class, 'index']);
Route::get('categories/category-services/{category_id}', [CategoryServiceController::class, 'index']);

//tamer order
Route::post('tamers/orders/ORD64b162e4a89ab/{tamer_id}', [TamerOrderV1Controller::class, 'store']);
Route::post('tamers/orders/REQ64b37d917dc30/{tamer_id}/{tamer_order_id}', [TamerOrderV1Controller::class, 'addOrderRequirements']);

Route::get('tamers/orders/SHOW64b40a338c436/{tamerOrder}', [TamerOrderV1Controller::class, 'show']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
