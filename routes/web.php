<?php

use App\Http\Controllers\BankAccountController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\PaymentCategoryController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductBrandController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTenantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockHistoryController;
use App\Http\Controllers\SupplierController;
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
    Route::get('/product-report', [App\Http\Controllers\HomeController::class, 'productReport'])->name('productReport');

    $route->group(['prefix' => 'cashier', 'as' => 'cashier.'], function ($route) {
        $route->get('/', [CashierController::class, 'index']);
        $route->get('/cart', [CashierController::class, 'cart'])->name('cart');
        $route->get('/invoice/{order}', [CashierController::class, 'invoice']);
        $route->get('/order-list', [CashierController::class, 'orderList']);
        $route->get('/set-order/{code}', [CashierController::class, 'setOrder']);
        $route->post('/save-cart', [CashierController::class, 'saveCart'])->name('saveCart');
        $route->post('/setDiscount/{discount?}', [CashierController::class, 'setDiscount']);
        $route->post('/proceed-payment', [CashierController::class, 'proceedPayment'])->name('proceedPayment');
        $route->post('/delete-cart-detail/{id}', [CashierController::class, 'deleteCartDetail']);
        $route->post('/plus-minus/{product_id}/{qty}', [CashierController::class, 'plusMinus']);


        $route->get('/payment', [CashierController::class, 'payment'])->name('payment');
        $route->get('/cart-list', [CashierController::class, 'cartList'])->name('cartList');
    });

    $route->patch('users/restore', [UserController::class, 'restore'])->name('users.restore');
    $route->delete('users/forceDestroy', [UserController::class, 'forceDestroy'])->name('users.forceDestroy');
    $route->delete('users/massDestroy', [UserController::class, 'massDestroy'])->name('users.massDestroy');
    $route->resource('users', UserController::class);

    $route->patch('roles/restore', [RoleController::class, 'restore'])->name('roles.restore');
    $route->delete('roles/forceDestroy', [RoleController::class, 'forceDestroy'])->name('roles.forceDestroy');
    $route->delete('roles/massDestroy', [RoleController::class, 'massDestroy'])->name('roles.massDestroy');
    $route->resource('roles', RoleController::class);

    $route->delete('permissions/massDestroy', [PermissionController::class, 'massDestroy'])->name('permissions.massDestroy');
    $route->resource('permissions', PermissionController::class)->except('show');

    $route->post('tenants/set-active', [TenantController::class, 'setActiveTenant']);
    $route->get('tenants/get-tenants', [TenantController::class, 'ajaxGetTenants']);
    $route->patch('tenants/restore', [TenantController::class, 'restore'])->name('tenants.restore');
    $route->delete('tenants/forceDestroy', [TenantController::class, 'forceDestroy'])->name('tenants.forceDestroy');
    $route->delete('tenants/massDestroy', [TenantController::class, 'massDestroy'])->name('tenants.massDestroy');
    $route->resource('tenants', TenantController::class)->except('show');

    $route->patch('companies/restore', [CompanyController::class, 'restore'])->name('companies.restore');
    $route->delete('companies/forceDestroy', [CompanyController::class, 'forceDestroy'])->name('companies.forceDestroy');
    $route->delete('companies/massDestroy', [CompanyController::class, 'massDestroy'])->name('companies.massDestroy');
    $route->resource('companies', CompanyController::class)->except('show');

    $route->patch('customers/restore', [CustomerController::class, 'restore'])->name('customers.restore');
    $route->delete('customers/forceDestroy', [CustomerController::class, 'forceDestroy'])->name('customers.forceDestroy');
    $route->delete('customers/massDestroy', [CustomerController::class, 'massDestroy'])->name('customers.massDestroy');
    $route->resource('customers', CustomerController::class);

    $route->get('customer-groups/get-customer-groups', [CustomerGroupController::class, 'ajaxGetCustomerGroups']);
    $route->patch('customer-groups/restore', [CustomerGroupController::class, 'restore'])->name('customer-groups.restore');
    $route->delete('customer-groups/forceDestroy', [CustomerGroupController::class, 'forceDestroy'])->name('customer-groups.forceDestroy');
    $route->delete('customer-groups/massDestroy', [CustomerGroupController::class, 'massDestroy'])->name('customer-groups.massDestroy');
    $route->resource('customer-groups', CustomerGroupController::class);

    $route->patch('products/restore', [ProductController::class, 'restore'])->name('products.restore');
    $route->delete('products/forceDestroy', [ProductController::class, 'forceDestroy'])->name('products.forceDestroy');
    $route->delete('products/massDestroy', [ProductController::class, 'massDestroy'])->name('products.massDestroy');
    $route->resource('products', ProductController::class);

    $route->group(['prefix' => 'products/{product}', 'as' => 'products.'], function ($route) {
        $route->resource('tenants', ProductTenantController::class)->only(['index', 'edit', 'update']);
    });

    $route->patch('product-categories/restore', [ProductCategoryController::class, 'restore'])->name('product-categories.restore');
    $route->delete('product-categories/forceDestroy', [ProductCategoryController::class, 'forceDestroy'])->name('product-categories.forceDestroy');
    $route->delete('product-categories/massDestroy', [ProductCategoryController::class, 'massDestroy'])->name('product-categories.massDestroy');
    $route->resource('product-categories', ProductCategoryController::class);

    $route->patch('product-brands/restore', [ProductBrandController::class, 'restore'])->name('product-brands.restore');
    $route->delete('product-brands/forceDestroy', [ProductBrandController::class, 'forceDestroy'])->name('product-brands.forceDestroy');
    $route->delete('product-brands/massDestroy', [ProductBrandController::class, 'massDestroy'])->name('product-brands.massDestroy');
    $route->resource('product-brands', ProductBrandController::class);

    $route->patch('payment-categories/restore', [PaymentCategoryController::class, 'restore'])->name('payment-categories.restore');
    $route->delete('payment-categories/forceDestroy', [PaymentCategoryController::class, 'forceDestroy'])->name('payment-categories.forceDestroy');
    $route->delete('payment-categories/massDestroy', [PaymentCategoryController::class, 'massDestroy'])->name('payment-categories.massDestroy');
    $route->resource('payment-categories', PaymentCategoryController::class);

    $route->patch('payment-types/restore', [PaymentTypeController::class, 'restore'])->name('payment-types.restore');
    $route->delete('payment-types/forceDestroy', [PaymentTypeController::class, 'forceDestroy'])->name('payment-types.forceDestroy');
    $route->delete('payment-types/massDestroy', [PaymentTypeController::class, 'massDestroy'])->name('payment-types.massDestroy');
    $route->resource('payment-types', PaymentTypeController::class);

    $route->patch('payments/restore', [PaymentController::class, 'restore'])->name('payments.restore');
    $route->delete('payments/forceDestroy', [PaymentController::class, 'forceDestroy'])->name('payments.forceDestroy');
    $route->delete('payments/massDestroy', [PaymentController::class, 'massDestroy'])->name('payments.massDestroy');
    $route->resource('payments', PaymentController::class);

    $route->get('orders/invoice/{order}', [OrderController::class, 'invoice'])->name('orders.invoice');
    $route->patch('orders/restore', [OrderController::class, 'restore'])->name('orders.restore');
    $route->delete('orders/forceDestroy', [OrderController::class, 'forceDestroy'])->name('orders.forceDestroy');
    $route->delete('orders/massDestroy', [OrderController::class, 'massDestroy'])->name('orders.massDestroy');
    $route->resource('orders', OrderController::class);

    $route->patch('order-details/restore', [OrderDetailController::class, 'restore'])->name('order-details.restore');
    $route->delete('order-details/forceDestroy', [OrderDetailController::class, 'forceDestroy'])->name('order-details.forceDestroy');
    $route->delete('order-details/massDestroy', [OrderDetailController::class, 'massDestroy'])->name('order-details.massDestroy');
    $route->resource('order-details', OrderDetailController::class);

    $route->delete('stocks/massDestroy', [StockController::class, 'massDestroy'])->name('stocks.massDestroy');
    $route->resource('stocks', StockController::class)->only(['index', 'show', 'edit', 'update']);

    $route->delete('stock-histories/massDestroy', [StockHistoryController::class, 'massDestroy'])->name('stock-histories.massDestroy');
    $route->resource('stock-histories', StockHistoryController::class)->only(['index']);

    $route->resource('profiles', ProfileController::class)->only(['index', 'edit', 'update']);

    $route->resource('reports', ReportController::class)->only(['index']);

    $route->delete('discounts/massDestroy', [DiscountController::class, 'massDestroy'])->name('discounts.massDestroy');
    $route->resource('discounts', DiscountController::class);

    $route->resource('suppliers', SupplierController::class);

    $route->group(['prefix' => 'suppliers/{supplier}', 'as' => 'suppliers.'], function ($route) {
        $route->resource('bank-accounts', BankAccountController::class)->except('destroy');
    });
    $route->delete('bank-accounts/massDestroy', [BankAccountController::class, 'massDestroy'])->name('bank-accounts.massDestroy');
    $route->delete('bank-accounts/{id}', [BankAccountController::class, 'destroy'])->name('bank-accounts.destroy');

    // $route->resource('bank-accounts', BankAccountController::class);

    $route->get('get-regions/city/{id}', function($id){
        return response()->file(public_path('regions/city/'.$id));
    });

    $route->get('get-regions/district/{id}', function($id){
        return response()->file(public_path('regions/district/'.$id));
    });

    $route->get('get-regions/village/{id}', function($id){
        return response()->file(public_path('regions/village/'.$id));
    });
});
