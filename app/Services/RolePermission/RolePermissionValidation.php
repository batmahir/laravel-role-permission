<?php
namespace App\Services\RolePermission;

use Illuminate\Support\Facades\DB;

class RolePermissionValidation
{
    public function __construct()
    {
        $this->validate();
    }

    public function attribute()
    {
        [
            'user_id' => function(){

                DB::table('users')
                    ->where('')
            },
            'role_id' =>,
            'permission_id' =>
        ];
    }

    public function validate()
    {
        []
    }
}
