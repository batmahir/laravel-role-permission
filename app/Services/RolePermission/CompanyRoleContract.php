<?php
namespace App\Services\RolePermission;

interface CompanyRoleContract
{
    public function allCompanyRole(int $role_id);

    public function assignedCompanyRole(int $company_id);

    public function hasCompanyRole(int $user_id, array $permission_ids);
}
