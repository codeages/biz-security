<?php

namespace Codeages\Biz\Role\Service;

interface RoleService
{
    public function createRole($role);

    public function updateRole($id, array $fiedls);

    public function deleteRole($id);

    public function disableRole($id);

    public function enableRole($id);

    public function getRole($id);

    public function getRoleByCode($code);

    public function findRolesByCodes(array $codes);

    public function findRolesByIds(array $ids);

    public function searchRoles($conditions, $sort, $start, $limit);

    public function countRoles($conditions);
}
