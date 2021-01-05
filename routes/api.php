<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    // 品牌
    Route::prefix('brands')->group(function () {
        // 看全部品牌
        Route::get('/', 'App\Http\Controllers\BrandController@index');
        // 新增品牌
        Route::post('/', 'App\Http\Controllers\BrandController@store');
        // 更新品牌
        Route::put('/{id}', 'App\Http\Controllers\BrandController@update');
        // 上傳品牌圖片
        Route::post('/pic/{id}', 'App\Http\Controllers\BrandController@update');
        // 刪除品牌
        Route::delete('/{id}', 'App\Http\Controllers\BrandController@destroy');
        // 顯示品牌底下所有的商品
        Route::get('product/{brandname}', 'App\Http\Controllers\BrandController@findProduct');
    });

    // 商品瀏覽
    Route::get('getproduct', 'App\Http\Controllers\api\MerchandiseUserController@getProduct');
    Route::get('product/{id}', 'App\Http\Controllers\api\MerchandiseUserController@getProductbyId');

    // 測試綠界
    Route::post('/callback', 'App\Http\Controllers\api\MerchandiseUserController@callback');
    Route::get('/success', 'App\Http\Controllers\api\MerchandiseUserController@redirectFromECpay');

    Route::prefix('user')->group(function () {
        // 註冊
        Route::post('register', 'App\Http\Controllers\api\UserController@register');
        // 登入
        Route::post('login', 'App\Http\Controllers\api\UserController@login');
        // 取得資訊
        Route::middleware('auth:sanctum')->get('/getuser', 'App\Http\Controllers\api\UserController@getUser');

        Route::group(['middleware' => ['auth:sanctum']], function () {
            // 代購單
            Route::prefix('daigouorder')->group(function () {
                // 新增代購單
                Route::post('/', [DaigouorderController::class, 'store']);
                // 取得代購單
                Route::get('/', [DaigouorderController::class, 'index']);
                // // 刪除代購單
                Route::delete('/{dgid}',  [DaigouorderController::class, 'destroy']);
                // 取得代購單下所有品項
                Route::get('/{dgid}', [DaigouitemController::class, 'index']);
                // 新增代品項
                Route::post('/{dgid}', [DaigouitemController::class, 'store']);
                // 修改品項數量
                Route::put('/item/{itemid}',  [DaigouitemController::class, 'update']);
                // // 刪除品項
                Route::delete('/item/{itemid}',  [DaigouitemController::class, 'destroy']);

                
            });

            Route::prefix('products')->group(function () {
                // 指定商品
                Route::post('/uploadimg', 'App\Http\Controllers\api\MerchandiseUserController@uploadImg');
            });

            Route::prefix('cart')->group(function () {
                // 新增購物車
                Route::post('/', 'App\Http\Controllers\api\MerchandiseUserController@createCart');
                // 刪除購物車
                Route::delete('/', 'App\Http\Controllers\api\MerchandiseUserController@deleteCart');
                // 更新購物車
                Route::put('/', 'App\Http\Controllers\api\MerchandiseUserController@updateCart');
                // 取得購物車
                Route::get('/', 'App\Http\Controllers\api\MerchandiseUserController@getCart');
            });

            // 訂單處理
            Route::prefix('order')->group(function () {
                // 自己的訂單
                Route::get('/', 'App\Http\Controllers\api\MerchandiseUserController@ownOrder');
                // 新增訂單
                Route::post('/', 'App\Http\Controllers\api\MerchandiseUserController@createOrder');

            });

        });
    });

    //管理者
    Route::prefix('admin')->group(function () {

        // 指定商品
        // Route::post('/uploadimg', 'App\Http\Controllers\api\AdminsController@uploadImg');
        // 取得資訊
        Route::group(['middleware' => ['auth:sanctum', 'auth.admin']], function () {

            Route::prefix('products')->group(function () {
                // 新增產品
                Route::post('/', 'App\Http\Controllers\api\MerchandiseController@createProduct');
                // 刪除產品
                Route::delete('/', 'App\Http\Controllers\api\MerchandiseController@deleteProduct');
                // 更新產品
                Route::put('/', 'App\Http\Controllers\api\MerchandiseController@updateProduct');
                // 取得產品
                Route::get('/', 'App\Http\Controllers\api\MerchandiseController@getProduct');
                // 上傳圖片
                Route::post('/uploadimg', 'App\Http\Controllers\api\MerchandiseController@uploadImg');
            });

            Route::prefix('cart')->group(function () {
                // 新增購物車
                Route::post('/', 'App\Http\Controllers\api\MerchandiseController@createCart');
                // 刪除購物車
                Route::delete('/', 'App\Http\Controllers\api\MerchandiseController@deleteCart');
                // 更新購物車
                Route::put('/', 'App\Http\Controllers\api\MerchandiseController@updateCart');
                // 取得購物車
                Route::get('/', 'App\Http\Controllers\api\MerchandiseController@getCart');
            });

            Route::prefix('daigouparameter')->group(function () {
                // 新增參數
                Route::post('/', [DaigouparameterController::class, 'store']);
                // 取得參數
                Route::get('/', [DaigouparameterController::class, 'index']);
                // 更新參數
                Route::put('/{id}', [DaigouparameterController::class, 'update']);
                // 刪除參數
                Route::delete('/{id}', [DaigouparameterController::class, 'destroy']);

            });

            // 模擬類
            Route::prefix('order')->group(function () {

                // 取得訂單
                Route::get('/', 'App\Http\Controllers\api\MerchandiseController@getOrder');

                // 模擬結帳
                Route::post('/pay', 'App\Http\Controllers\api\MerchandiseController@Simulationpay');
            });

        });
    });
});
