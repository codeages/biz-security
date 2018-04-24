<?php

namespace Codeages\Biz\Role\Service\Impl;

use Codeages\Biz\Framework\Service\Exception\InvalidArgumentException;
use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Biz\Role\Service\RoleService;
use Codeages\Biz\Framework\Service\BaseService;

class RoleServiceImpl extends BaseService implements RoleService
{
    public function createRole($role)
    {
        $role = ArrayToolkit::parts($role, array('name', 'code', 'data'));

        if (!ArrayToolkit::requireds($role, array('name', 'code'))) {
            throw new InvalidArgumentException('args is invalid.');
        }

        $savedRole = $this->getRoleDao()->getByCode($role['code']);
        if (!empty($savedRole)) {
            throw new InvalidArgumentException('role is exsit.');
        }

        return $this->getRoleDao()->create($role);
    }
    
    public function updateRole($id, array $fields)
    {
        $fields = ArrayToolkit::parts($fields, array('name', 'code', 'data'));

        if (isset($fields['code'])) {
            unset($fields['code']);
        }

        return $this->getRoleDao()->update($id, $fields);
    }

    public function deleteRole($id)
    {
        return $this->getRoleDao()->delete($id);
    }

    public function disableRole($id)
    {
        return $this->getRoleDao()->update($id, array(
            'enable' => 0
        ));
    }

    public function enableRole($id)
    {
        return $this->getRoleDao()->update($id, array(
            'enable' => 1
        ));
    }

    public function getRole($id)
    {
        return $this->getRoleDao()->get($id);
    }

    public function getRoleByCode($code)
    {
        return $this->getRoleDao()->getByCode($code);
    }

    public function findRolesByCodes(array $codes)
    {
        return $this->getRoleDao()->findByCodes($codes);
    }

    public function searchRoles($conditions, $sort, $start, $limit)
    {
        return $this->getRoleDao()->search($conditions, $sort, $start, $limit);
    }

    public function countRoles($conditions)
    {
        return $this->getRoleDao()->count($conditions);
    }

    protected function getRoleDao()
    {
        return $this->biz->dao('Role:RoleDao');
    }
}
