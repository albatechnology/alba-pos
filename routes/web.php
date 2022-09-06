<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\PaymentCategoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductBrandController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => 'auth'], function ($route) {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    $route->delete('users/massDestroy', [UserController::class, 'massDestroy'])->name('users.massDestroy');
    $route->resource('users', UserController::class);

    $route->delete('roles/massDestroy', [RoleController::class, 'massDestroy'])->name('roles.massDestroy');
    $route->resource('roles', RoleController::class);

    $route->delete('permissions/massDestroy', [PermissionController::class, 'massDestroy'])->name('permissions.massDestroy');
    $route->resource('permissions', PermissionController::class)->except('show');

    $route->post('tenants/set-active', [TenantController::class, 'setActiveTenant']);
    $route->get('tenants/get-tenants', [TenantController::class, 'ajaxGetTenants']);
    $route->delete('tenants/massDestroy', [TenantController::class, 'massDestroy'])->name('tenants.massDestroy');
    $route->resource('tenants', TenantController::class)->except('show');

    $route->delete('companies/massDestroy', [CompanyController::class, 'massDestroy'])->name('companies.massDestroy');
    $route->resource('companies', CompanyController::class)->except('show');

    $route->delete('customers/massDestroy', [CustomerController::class, 'massDestroy'])->name('customers.massDestroy');
    $route->resource('customers', CustomerController::class);

    $route->delete('products/massDestroy', [ProductController::class, 'massDestroy'])->name('products.massDestroy');
    $route->resource('products', ProductController::class);

    $route->delete('product-categories/massDestroy', [ProductCategoryController::class, 'massDestroy'])->name('product-categories.massDestroy');
    $route->resource('product-categories', ProductCategoryController::class);

    $route->delete('product-brands/massDestroy', [ProductBrandController::class, 'massDestroy'])->name('product-brands.massDestroy');
    $route->resource('product-brands', ProductBrandController::class);

    $route->delete('payment-categories/massDestroy', [PaymentCategoryController::class, 'massDestroy'])->name('payment-categories.massDestroy');
    $route->resource('payment-categories', PaymentCategoryController::class);

    $route->delete('payment-types/massDestroy', [PaymentTypeController::class, 'massDestroy'])->name('payment-types.massDestroy');
    $route->resource('payment-types', PaymentTypeController::class);

    $route->delete('payments/massDestroy', [PaymentController::class, 'massDestroy'])->name('payments.massDestroy');
    $route->resource('payments', PaymentController::class);

    $route->delete('orders/massDestroy', [OrderController::class, 'massDestroy'])->name('orders.massDestroy');
    $route->resource('orders', OrderController::class);

    $route->delete('order-details/massDestroy', [OrderDetailController::class, 'massDestroy'])->name('order-details.massDestroy');
    $route->resource('order-details', OrderDetailController::class);
});
