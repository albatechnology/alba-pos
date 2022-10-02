<?php

namespace App\Console\Commands;

use App\Helpers\PermissionsHelper;
use App\Models\Role;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class GeneratePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:permissions {guard=web}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all/new permissions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $guard = $this->argument('guard');

        // 2 is admin ID
        $adminRole = Role::find(2);
        if (!$adminRole) {
            $this->error('Admin role not found');
            return;
        }

        $permissions = PermissionsHelper::getAllPermissions();

        $permissions->each(function ($permission, $key) use ($guard) {
            if (is_array($permission)) {
                $headSubPermissions = Permission::firstOrCreate([
                    'name' => $key,
                    'guard_name' => $guard
                ]);

                $this->generateChilds($headSubPermissions, $permission);
            } else {
                Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => $guard
                ]);
            }
        });

        $adminRole->syncPermissions(PermissionsHelper::getAdminPermissionsData());

        $this->info("Successfully generating all permissions");
    }

    public function generateChilds(Permission $headSubPermissions, array $subPermissions)
    {
        $guard = $this->argument('guard');
        collect($subPermissions)->each(function ($permission, $key) use ($headSubPermissions, $guard) {
            if (is_array($permission)) {
                $hsp = Permission::firstOrCreate([
                    'name' => $key,
                    'guard_name' => $guard,
                    'parent_id' => $headSubPermissions->id
                ]);

                $this->generateChilds($hsp, $permission);
            } else {
                $hsp = Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => $guard,
                    'parent_id' => $headSubPermissions->id
                ]);
            }

            return;
        });
    }

    // public function handle()
    // {
    //     // dd(PermissionsHelper::adminPermissions());
    //     $guard = $this->argument('guard');
    //     $permissions = PermissionsHelper::getAllPermissions();

    //     // 2 is admin ID
    //     $adminRole = Role::find(2);
    //     if (!$adminRole) {
    //         $this->error('Admin role not found');
    //         return;
    //     }

    //     collect($permissions)->map(function ($p) use ($guard) {
    //         Permission::firstOrCreate([
    //             'name' => $p,
    //             'guard_name' => $guard
    //         ]);
    //     });

    //     $adminRole->syncPermissions(PermissionsHelper::adminPermissions());

    //     $this->info("Successfully generating all permissions");
    // }
}
