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
            self::customerManagement(),
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

        return new Menu('product_management_access', 'fa fa-shopping-cart', 'Product Management', ...[$products, $product_categories, $product_brands]);
    }

    protected static function corporateManagement()
    {
        $companies = new Submenu('companies_access', 'companies', 'fa fa-building', 'Companies');
        $tenants = new Submenu('tenants_access', 'tenants', 'fa fa-store', 'Tenants');

        return new Menu('corporate_management_access', 'fa fa-building', 'Corporate Management', ...[$companies, $tenants]);
    }

    protected static function customerManagement()
    {
        $customers = new Submenu('customers_access', 'customers', 'fa fa-users', 'Customers');

        return new Menu('customer_management_access', 'fa fa-users', 'Customers Management', ...[$customers]);
    }
}
