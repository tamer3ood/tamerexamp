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
use App\Http\Controllers\API\Categories\CategoryElementAddOnAttributeController;
use App\Http\Controllers\API\Categories\CategoryServiceAddOnAttributeController;
use App\Http\Controllers\API\Jobs\JobV1Controller;
use App\Http\Controllers\API\Jobs\JobProposalController;

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
Route::post('tamers/store/TMR6491706fcb506', [TamerV1Controller::class, 'store']);
Route::post('tamers/store/STP1TMR6491706fcb506', [TamerV1Controller::class, 'store_stp1']);
Route::post('tamers/store/STP2TMR64947bf287268', [TamerV1Controller::class, 'store_stp2']);
Route::post('tamers/store/STP3TMR6496e7ff131d1', [TamerV1Controller::class, 'store_stp3']);
Route::post('tamers/store/STP4TMR6496e9b767aef', [TamerV1Controller::class, 'store_stp4']);
Route::post('tamers/store/STP5TMR6496e9d30854e', [TamerV1Controller::class, 'store_stp5']);
Route::post('tamers/store/STP6TMR6496e9e159c0b', [TamerV1Controller::class, 'store_stp6']);

//SHOW TAMER
Route::get('tamers/show/TMR649d590f0d7f3', [TamerV1Controller::class, 'index']);
Route::get('tamers/show/TMR649d590f0d7f3/{tamer}', [TamerV1Controller::class, 'show']);

Route::get('tamers/show/SHW1TMR649d590f0d7f3/{tamer}', [TamerV1Controller::class, 'show_shw1']);
Route::get('tamers/show/SHW2TMR649d591a6f0b3/{tamer}', [TamerV1Controller::class, 'show_shw2']);
Route::get('tamers/show/SHW3TMR649d5926bb125/{tamer}', [TamerV1Controller::class, 'show_shw3']);
Route::get('tamers/show/SHW4TMR649d593891e5a/{tamer}', [TamerV1Controller::class, 'show_shw4']);

Route::get('tamers/show/SHW5TMR649d594e76f74/{tamer}', [TamerV1Controller::class, 'show_shw5']);

Route::get('tamers/show/SHW6TMR649d59679ec73/{tamer}', [TamerV1Controller::class, 'show_shw6']);


//UPDATE TAMER

Route::put('tamers/update/STP1TMR649d57b4aafc7/{tamer}', [TamerV1Controller::class, 'update_stp1']);
Route::put('tamers/update/STP2TMR649d57c95449e/{tamer}', [TamerV1Controller::class, 'update_stp2']);
Route::post('tamers/update/STP3TMR649d57d67f2df/{tamer}', [TamerV1Controller::class, 'update_stp3']);
Route::put('tamers/update/STP4TMR649d57ec1b5e3/{tamer}', [TamerV1Controller::class, 'update_stp4']);

Route::put('tamers/update/STP5TMR649d580117390/{tamer}', [TamerV1Controller::class, 'update_stp5']);


Route::put('tamers/update/STP6TMR649d581086b09/{tamer}', [TamerV1Controller::class, 'update_stp6']);
Route::put('tamers/update/STUSTMR64b0225f2d0a4/{tamer}', [TamerV1Controller::class, 'update_status']);


Route::delete('tamers/deletetamersinglefile/STP3DELFile64a97a4df17d7/{tamer_file_id}/{tamer_id}', [TamerV1Controller::class, 'deleteSingleTamerFile']);



Route::delete('tamers/destroy/DELTMR64b035045d6ef/{tamer}', [TamerV1Controller::class, 'destroy']);




//Categories
Route::get('categories/categories', [CategoryController::class, 'index']);
Route::get('categories/sub-categories/{category_id}', [SubCategoryController::class, 'index']);
Route::get('categories/category-type/{sub_category_id}', [CategoryTypeController::class, 'index']);

Route::get('categories/category-attributes/{category_id}', [CategoryAttributeController::class, 'getAttributes']);
Route::get('categories/category-attribute-items', [CategoryAttributeItemController::class, 'index']);
Route::get('categories/category-elements/{category_id}', [CategoryElementController::class, 'index']);
Route::get('categories/category-services/{category_id}', [CategoryServiceController::class, 'index']);


Route::get('categories/category-service-add-on-atts/{category_service_id}', [CategoryServiceAddOnAttributeController::class, 'index']);
Route::get('categories/category-element-add-on-atts/{category_element_id}', [CategoryElementAddOnAttributeController::class, 'index']);

//tamer order
Route::post('tamers/orders/ORD64b162e4a89ab/{tamer_id}', [TamerOrderV1Controller::class, 'store']);
Route::post('tamers/orders/REQ64b37d917dc30/{tamer_id}/{tamer_order_id}', [TamerOrderV1Controller::class, 'addOrderRequirements']);

Route::get('tamers/orders/SHOW64b40a338c436/{tamerOrder}', [TamerOrderV1Controller::class, 'show']);



//UPDATE TAMER

Route::put('tamers/update/STP1TMR649d57b4aafc7/{tamer}', [TamerV1Controller::class, 'update_stp1']);
Route::put('tamers/update/STP2TMR649d57c95449e/{tamer}', [TamerV1Controller::class, 'update_stp2']);
Route::post('tamers/update/STP3TMR649d57d67f2df/{tamer}', [TamerV1Controller::class, 'update_stp3']);
Route::put('tamers/update/STP4TMR649d57ec1b5e3/{tamer}', [TamerV1Controller::class, 'update_stp4']);

Route::put('tamers/update/STP5TMR649d580117390/{tamer}', [TamerV1Controller::class, 'update_stp5']);


Route::put('tamers/update/STP6TMR649d581086b09/{tamer}', [TamerV1Controller::class, 'update_stp6']);
Route::put('tamers/update/STUSTMR64b0225f2d0a4/{tamer}', [TamerV1Controller::class, 'update_status']);



Route::resource('jobs', JobV1Controller::class);
Route::post('job/proposals/{job_id}', [JobProposalController::class, 'store']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
