<?php

namespace App\Services;

use App\Classes\Menu;
use App\Classes\Submenu;

class MenuService
{
    public static function menu(): array
    {
        return [
            self::userManagement(),
            self::corporateManagement(),
            self::productManagement(),
            self::marketingManagement(),
            self::customerManagement(),
            self::transactionManagement(),
            self::inventoryManagement(),
        ];
    }

    protected static function userManagement()
    {
        $users = new Submenu('users_access', 'users', 'fa fa-users', 'Users');
        $roles = new Submenu('roles_access', 'roles', 'fa fa-users', 'Roles');
        $permissions = new Submenu('permissions_access', 'permissions', 'fa fa-users', 'Permissions');

        return new Menu('user_management_access', 'fa fa-users', 'User Management', ...[$users, $roles, $permissions]);
    }

    protected static function productManagement()
    {
        $products = new Submenu('products_access', 'products', 'fa fa-products', 'Products');
        $product_categories = new Submenu('product_categories_access', 'product-categories', 'fa fa-users', 'Product Categories');
        $product_brands = new Submenu('product_brands_access', 'product-brands', 'fa fa-users', 'Product Brand');
        $product_variants = new Submenu('product_variants_access', 'product-variants', 'fa fa-users', 'Product Variant');

        return new Menu('product_management_access', 'fa fa-shopping-cart', 'Product Management', ...[$products, $product_categories, $product_brands, $product_variants]);
    }

    protected static function marketingManagement()
    {
        $discounts = new Submenu('discounts_access', 'discounts', 'fa fa-dollar-sign', 'Discounts');
        $bank_accounts = new Submenu('bank_accounts_access', 'bank-accounts', 'fa fa-dollar-sign', 'Bank Accounts');

        return new Menu('marketing_management_access', 'fa fa-dollar-sign', 'Marketing', ...[$discounts, $bank_accounts]);
    }

    protected static function corporateManagement()
    {
        $companies = new Submenu('companies_access', 'companies', 'fa fa-building', 'Companies');
        $tenants = new Submenu('tenants_access', 'tenants', 'fa fa-store', 'Tenants');
        $paymentCategories = new Submenu('payments_access', 'payment-categories', 'fa fa-users', 'Payment Categories');
        $paymentTypes = new Submenu('payments_access', 'payment-types', 'fa fa-users', 'Payment Types');

        return new Menu('corporate_management_access', 'fa fa-building', 'Corporate Management', ...[$companies, $tenants, $paymentCategories, $paymentTypes]);
    }

    protected static function customerManagement()
    {
        $customers = new Submenu('customers_access', 'customers', 'fa fa-users', 'Customers');
        $customerGroups = new Submenu('customer_groups_access', 'customer-groups', 'fa fa-users', 'Customer Groups');

        return new Menu('customer_management_access', 'fa fa-users', 'Customer Management', ...[$customers, $customerGroups]);
    }

    // protected static function paymentManagement()
    // {
    //     $paymentCategories = new Submenu('payments_access', 'payment-categories', 'fa fa-users', 'Payment Categories');
    //     $paymentTypes = new Submenu('payments_access', 'payment-types', 'fa fa-users', 'Payment Types');

    //     return new Menu('payment_management_access', 'fa fa-dollar-sign', 'Payment Management', ...[$paymentCategories, $paymentTypes]);
    // }

    protected static function transactionManagement()
    {
        $orders = new Submenu('orders_access', 'orders', 'fa fa-users', 'Orders');
        $orderDetails = new Submenu('order_details_access', 'order-details', 'fa fa-users', 'Order Detail');
        $payments = new Submenu('payments_access', 'payments', 'fa fa-users', 'Payments');

        return new Menu('transaction_management_access', 'fa fa-shopping-cart', 'Transaction', ...[$orders, $orderDetails, $payments]);
    }

    protected static function inventoryManagement()
    {
        $stocks = new Submenu('stocks_access', 'stocks', 'fa fa-warehouse', 'Stocks');
        $stocksHistories = new Submenu('stock_histories_access', 'stock-histories', 'fa fa-warehouse', 'Stock Histories');
        $suppliers = new Submenu ('suppliers_access', 'suppliers', 'fa fa-warehouse', 'Suppliers');

        return new Menu('inventory_management_access', 'fa fa-warehouse', 'Inventory', ...[$stocks, $stocksHistories, $suppliers]);
    }

}
