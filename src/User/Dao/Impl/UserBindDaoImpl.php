<?php

namespace Codeages\Biz\User\Dao\Impl;

use Codeages\Biz\User\Dao\UserBindDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;
use Codeages\Biz\Framework\Dao\DaoException;

class UserBindDaoImpl extends GeneralDaoImpl implements UserBindDao
{
    protected $table = 'biz_security_user_bind';

    public function declares()
    {
        return array(
            'timestamps' => array('created_time', 'updated_time'),
            'serializes' => array(
            ),
            'orderbys' => array(
                'id',
                'created_time',
            ),
            'conditions' => array(
            ),
        );
    }

    public function getByTypeAndBindId($type, $bindId)
    {
        if (empty($type) || empty($bindId)) {
            throw new DaoException('args is invalid.');
        }

        return $this->getByFields(array(
            'type' => $type,
            'bind_id' => $bindId
        ));
    }

    public function deleteByTypeAndBindId($type, $bindId)
    {
        if (empty($type) || empty($bindId)) {
            throw new DaoException('args is invalid.');
        }
        
        return $this->db()->delete($this->table(), array('type' => $type, 'bind_id' => $bindId));
    }
}
