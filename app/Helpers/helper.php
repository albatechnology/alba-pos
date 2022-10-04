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

if (!function_exists('arrayFilterAndReindex')) {
    /**
     * remove array where value is null and reindex from 0
     */
    function arrayFilterAndReindex(array $values = []): array
    {
        return array_values(array_filter($values)) ?? [];
    }
}

if (!function_exists('rupiah')) {
    function rupiah(int|string $number): string
    {
        return "Rp " . number_format((float)$number, 0, ',', '.');
    }
}

if (!function_exists('filterPrice')) {
    function filterPrice($number)
    {
        $number = str_replace(',', '', $number);
        $number = str_replace('.', '', $number);
        return intval($number);
    }
}
