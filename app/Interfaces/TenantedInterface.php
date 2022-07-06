<?php
namespace App\Interfaces;

interface TenantedInterface
{
    public function scopeTenanted($query);
    public function scopeFindTenanted($query, int $id);
}
