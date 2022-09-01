<?php

namespace App\Helpers;

class PermissionsHelper
{
    public static function getAllPermissions(): array
    {
        return [
            ...static::adminPermissions(),
            ...static::superAdminPermissions(),
        ];
    }

    public static function adminPermissions(): array
    {
        return [
            'dashboard_access',
            'user_management_access',
            'roles_access',
            'roles_view',
            'roles_create',
            'roles_edit',
            'roles_delete',
            'users_access',
            'users_view',
            'users_create',
            'users_edit',
            'users_delete',

            'corporate_management_access',
            'tenants_access',
            'tenants_view',
            'tenants_create',
            'tenants_edit',
            'tenants_delete',

            'customer_management_access',
            'customers_access',
            'customers_view',
            'customers_create',
            'customers_edit',
            'customers_delete',

            'product_management_access',
            'products_access',
            'products_view',
            'products_create',
            'products_edit',
            'products_delete',
            'product_categories_access',
            'product_categories_view',
            'product_categories_create',
            'product_categories_edit',
            'product_categories_delete',
            'product_brands_access',
            'product_brands_view',
            'product_brands_create',
            'product_brands_edit',
            'product_brands_delete',
        ];
    }

    public static function superAdminPermissions(): array
    {
        return [
            'permissions_access',
            'permissions_view',
            'permissions_create',
            'permissions_edit',
            'permissions_delete',

            'companies_access',
            'companies_view',
            'companies_create',
            'companies_edit',
            'companies_delete',
        ];
    }
}
