<?php

if (!function_exists('tenancy')) {
    function tenancy(): \App\Services\TenancyService
    {
        return app(\App\Services\TenancyService::class);
    }
}

if (!function_exists('activeTenant')) {
    function activeTenant(): ?\App\Models\Tenant
    {
        return app(\App\Services\TenancyService::class)->getActiveTenant();
    }
}

if (!function_exists('user')) {
    function user(): ?\App\Models\User
    {
        return tenancy()->checkUserLogin();
    }
}
