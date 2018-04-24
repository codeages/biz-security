<?php

namespace Codeages\Biz\Role\Dao\Impl;

use Codeages\Biz\Framework\Dao\GeneralDaoImpl;
use Codeages\Biz\Role\Dao\RoleDao;

class RoleDaoImpl extends GeneralDaoImpl implements RoleDao
{
    protected $table = 'biz_security_role';

    public function getByCode($code)
    {
        return $this->getByFields(array('code' => $code));
    }

    public function findByCodes($codes)
    {
        if (empty($codes)) {
            return array();
        }

        return $this->findInField('code', $codes);
    }

    public function findByIds($ids)
    {
        if (empty($ids)) {
            return array();
        }
        
        return $this->findInField('id', $ids);
    }

    public function getByName($name)
    {
        return $this->getByFields(array('name' => $name));
    }

    public function declares()
    {
        $declares['conditions'] = array(
            'name = :name',
            'code = :code',
            'code LIKE :codeLike',
            'name LIKE :nameLike',
            'createdUserId = :createdUserId',
        );

        $declares['serializes'] = array(
            'data' => 'json',
        );

        $declares['orderbys'] = array(
            'createdTime',
            'updatedTime',
        );

        return $declares;
    }
}
