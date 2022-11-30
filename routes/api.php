<?php

use App\Http\Controllers\Api\Admin\CompanyController;
use App\Http\Controllers\Api\Admin\CustomerController;
use App\Http\Controllers\Api\Admin\DiscountController;
use App\Http\Controllers\Api\Admin\PaymentCategoryController;
use App\Http\Controllers\Api\Admin\PaymentTypeController;
use App\Http\Controllers\Api\Admin\ProductBrandController;
use App\Http\Controllers\Api\Admin\ProductCategoryController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\ProductTenantController;
use App\Http\Controllers\Api\Admin\StockController;
use App\Http\Controllers\Api\Admin\StockHistoryController;
use App\Http\Controllers\Api\Admin\TenantController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;

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

Route::post('auth/token', [AuthController::class, 'token']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['middleware' => 'auth:sanctum'], function ($route) {
    $route->get('users/me', [UserController::class, 'me']);
    $route->resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);

    Route::group(['prefix' => 'admin'], function ($route) {
        $route->resource('users', AdminUserController::class);
        $route->resource('payment-categories', PaymentCategoryController::class);
        $route->resource('payment-types', PaymentTypeController::class);
        $route->resource('companies', CompanyController::class);
        $route->resource('tenants', TenantController::class);
        $route->resource('product-brands', ProductBrandController::class);
        $route->resource('product-categories', ProductCategoryController::class);
        $route->resource('products', AdminProductController::class);
        $route->resource('product-tenants', ProductTenantController::class);
        $route->resource('customers', CustomerController::class);
        $route->resource('discounts', DiscountController::class);
        $route->resource('stocks', StockController::class);
        $route->resource('stock-histories', StockHistoryController::class);
    });
});
