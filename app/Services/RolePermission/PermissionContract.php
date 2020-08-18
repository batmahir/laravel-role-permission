<?php
namespace App\Services\RolePermission;

use Illuminate\Support\Collection;

interface PermissionContract
{
    public function allPermission();

    public function assignedPermission(int $user_id);

    public function hasPermission(int $user_id, array $permission_ids);

    public function allPermissionByRole($role_id):Collection;
}
