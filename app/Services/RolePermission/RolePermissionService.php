<?php
namespace App\Services\RolePermission;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RolePermissionService implements RoleContract
{

    public function allRoles(): ?Collection
    {
        $all_roles = DB::table('roles')->get();

        return $all_roles;
    }

    /**
     * @param int $user_id
     * @param int $role_id
     * @return bool
     */
    public function assignRole(int $user_id, int $role_id): bool
    {
        $validator =
            Validator::make(
                [
                    'user_id' => $user_id,
                    'role_id' => $role_id
                ],
                [
                    'user_id' => 'required|exists:users,id',
                    'role_id' => 'required|exists:role,id'
                ]
            );

        if($validator->fails())
        {
            return false;
        }

        $this->insertUserRole($user_id,$role_id);

        $this->assignAllPermissionOfRoleToUser($user_id,$role_id);

        return true;
    }

    public function assignedRoles(int $user_id): ?Collection
    {
        // validate the parameter first
        $validator =
            Validator::make(
                [
                    'user_id' => $user_id
                ],
                [
                    'user_id' => 'required|exists:users,id'
                ]
            );

        if($validator->fails())
        {
            throw new \Exception("{$validator->errors()}");
        }

        $assigned_role =
            DB::table('users')
                ->join('user_role','users.id','=','user_role.user_id')
                ->join('role','user_role.role_id','=','role.id')
                ->selectRaw("
                    users.id as user_id
                    role.id as role_id,
                    role.role_name,
                    role.role_display_name
                ")
                ->get();

        return $assigned_role;

    }

    public function hasRole(int $user_id, array $roles_id): ?bool
    {
        Validator::make(
            [
                'user_id' => $user_id
            ],
            [
                'users_id' => 'exists:users,id'
            ]
        );

        return $this->checkRole($roles_id);

    }


    public function allPermissionsByRole(int $role_id)
    {
        $all_permissions_by_role =
            DB::table('role')
                ->join('role_permission','role.id','=','role_permission.role_id')
                ->join('permission','role_permission.permission_id','=','permission.id')
                ->selectRaw("
                    role.id as role_id,
                    permission.id as permission_id,
                    permission.permission_name,
                    permission.permission_display_name
                ")
            ->get();

        return $all_permissions_by_role;
    }

    private function checkRole(array $roles_id)
    {
        $hasRole =
            DB::table('user_role')
                ->whereIn('user_role.role_id',$roles_id)
                ->get();

        count($hasRole) ? $hasRole = true : $hasRole = false;

        return $hasRole;
    }

    private function insertUserRole(int $user_id, int $role_id) : bool
    {
        DB::table('user_role')
            ->insert(
                [
                    'user_id' => $user_id ,
                    'role_id' => $role_id
                ]
            );

        return true;
    }

    private function insertAllPermissionOfRole(int $user_id,array $roles_id) :bool
    {
        DB::beginTransaction();

        foreach($roles_id as $role_id)
        {
           DB::table('user_permission')
               ->insert(
                   [
                       'user_id' => $user_id,
                       'role_id' => $role_id
                   ]
               );
        }

        DB::commit();

        return true;
    }

    private function assignAllPermissionOfRoleToUser($user_id,$role_id)
    {
        $roles = $this->allPermissionsByRole($role_id)->toArray();

        $this->insertAllPermissionOfRole($user_id,$roles);

    }




}
