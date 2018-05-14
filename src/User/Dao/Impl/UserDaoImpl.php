<?php

namespace Codeages\Biz\User\Dao\Impl;

use Codeages\Biz\User\Dao\UserDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class UserDaoImpl extends GeneralDaoImpl implements UserDao
{
    protected $table = 'biz_security_user';

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
                'created_ip = :created_ip',
                'created_time > :created_time_LT',
            ),
        );
    }

    public function getByUsername($username)
    {
        return $this->getByFields(array(
            'username' => $username
        ));
    }

    public function getByMobile($mobile)
    {
        return $this->getByFields(array(
            'mobile' => $mobile
        ));
    }

    public function getByEmail($email)
    {
        return $this->getByFields(array(
            'email' => $email
        ));
    }
}