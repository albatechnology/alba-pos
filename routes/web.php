<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PermissionController;
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

Route::group(['middleware' => 'auth'], function($route){
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    $route->resource('users', UserController::class);
    $route->resource('roles', RoleController::class);
    $route->resource('permissions', PermissionController::class)->except('show');
    $route->post('tenants/set-active', [TenantController::class, 'setActiveTenant']);
    $route->get('tenants/get-tenants', [TenantController::class, 'ajaxGetTenants']);
    $route->resource('tenants', TenantController::class)->except('show');
    $route->resource('companies', CompanyController::class)->except('show');

    $route->delete('customers/massDestroy', [CustomerController::class, 'massDestroy'])->name('customers.massDestroy');
    $route->resource('customers', CustomerController::class);
});
