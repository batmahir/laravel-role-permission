<?php
namespace App\Services\RolePermission;


use Illuminate\Support\Collection;

interface RoleContract
{
    /**
     * Show all available roles in the storage
     * @return array|null
     */
    public function allRoles():? Collection;

    public function assignRole(int $user_id,int $role_id): bool;

    /**
     * Get the assigned role(s) for certain user
     *
     * @param int $user_id
     * @return Collection|null
     */
    public function assignedRoles(int $user_id):? Collection;

    /**
     * Check if the array of role(s) is exist for certain user
     *
     * @param int $user_id
     * @param array $role_id
     * @return bool|null
     */
    public function hasRole(int $user_id,array $role_id) :? bool;


}
