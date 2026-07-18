<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ApiAuthController;
use App\Http\Controllers\Api\V1\ApiContactController;
use App\Http\Controllers\Api\V1\ApiUserController;
use App\Http\Controllers\Api\V1\ApiPagesController;
use App\Http\Controllers\Api\V1\ApiProductController;

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

Route::middleware('api_key')->prefix('v1')->group(function () {
    //auth apis
    Route::middleware(['guest:sanctum', 'throttle:5, 1'])->post('/send_otp', [ApiAuthController::class, 'send_otp']);
    Route::middleware(['guest:sanctum', 'throttle:10, 1'])->post('/check_otp', [ApiAuthController::class, 'check_otp']);
    Route::middleware('auth:sanctum')->post('/register', [ApiAuthController::class, 'register']);
    Route::middleware('auth:sanctum')->get('/logout', [ApiAuthController::class, 'logout']);
    //pages apis
    Route::middleware('auth:sanctum')->get('/home', [ApiPagesController::class, 'home']);
    Route::middleware('auth:sanctum')->get('/nuts', [ApiPagesController::class, 'nuts']);
    Route::middleware(['auth:sanctum', 'throttle:10, 1'])->get('/app_version', [ApiPagesController::class, 'app_version']);
    //common apis
    Route::middleware('auth:sanctum')->get('/user', [ApiUserController::class, 'index']);
    Route::middleware('auth:sanctum')->post('/user/update', [ApiUserController::class, 'update']);
    Route::middleware(['auth:sanctum', 'throttle:5, 1'])->get('/user/repeat_send_otp', [ApiUserController::class, 'repeat_send_otp']);
    Route::middleware('auth:sanctum')->post('/user/update_mobile', [ApiUserController::class, 'update_mobile']);
    Route::middleware('auth:sanctum')->get('/user/count_cart', [ApiUserController::class, 'count_cart']);
    Route::middleware('auth:sanctum')->post('/user/add_cart', [ApiUserController::class, 'add_cart']);
    Route::middleware('auth:sanctum')->get('/user/favorits', [ApiUserController::class, 'favorits']);
    Route::middleware('auth:sanctum')->get('/user/add_favorits/{id}', [ApiUserController::class, 'add_favorits']);
    Route::middleware('auth:sanctum')->get('/user/remove_favorits/{id}', [ApiUserController::class, 'remove_favorits']);
    Route::middleware('auth:sanctum')->get('/user/records', [ApiUserController::class, 'records']);
    Route::middleware('auth:sanctum')->post('/user/records/show', [ApiUserController::class, 'show_record']);
    Route::middleware('auth:sanctum')->get('/user/shopping_cart', [ApiUserController::class, 'shopping_cart']);
    Route::middleware('auth:sanctum')->get('/user/remove_shopping_cart/{id}', [ApiUserController::class, 'remove_shopping_cart']);
    Route::middleware('auth:sanctum')->post('/user/check_transaction', [ApiUserController::class, 'check_transaction']);
    Route::middleware('auth:sanctum')->post('/user/create_pay_token', [ApiUserController::class, 'create_pay_token'])->middleware('auth_api_pay');
    Route::middleware('auth:sanctum')->post('/user/create_nuts_pay_token', [ApiUserController::class, 'create_nuts_pay_token'])->middleware('auth_api_pay');
    //tikets api
    Route::middleware('auth:sanctum')->get('/tiket', [ApiContactController::class, 'tiket']);
    Route::middleware('auth:sanctum')->post('/tiket', [ApiContactController::class, 'store']);
    Route::middleware('auth:sanctum')->get('/tiket/delete', [ApiContactController::class, 'delete']);
    //products apis
    Route::middleware('auth:sanctum')->get('/category', [ApiProductController::class, 'category']);
    Route::middleware('auth:sanctum')->get('/category/{id}', [ApiProductController::class, 'category_products']);
    Route::middleware('auth:sanctum')->get('/get_product/{id}', [ApiProductController::class, 'get_product']);
    Route::middleware('auth:sanctum')->get('/products/names', [ApiProductController::class, 'names']);
    Route::middleware('auth:sanctum')->post('/products/search', [ApiProductController::class, 'search']);
    Route::middleware('auth:sanctum')->get('/products/discounts', [ApiProductController::class, 'discounts']);
    Route::middleware('auth:sanctum')->get('/products/bestsellings', [ApiProductController::class, 'bestsellings']);
    Route::middleware('auth:sanctum')->get('/products/populars', [ApiProductController::class, 'populars']);
    Route::middleware('auth:sanctum')->get('/products/most_visiteds', [ApiProductController::class, 'most_visiteds']);
    Route::middleware('auth:sanctum')->get('/products/comments/{id}', [ApiProductController::class, 'comments']);
    Route::middleware('auth:sanctum')->get('/products/all', [ApiProductController::class, 'all']);
    Route::middleware('auth:sanctum')->post('/products/send_comment', [ApiProductController::class, 'send_comment']);
});
