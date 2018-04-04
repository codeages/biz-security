<?php

namespace Tests;



use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Biz\User\Service\UserService;

class UserServiceTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();
        $currentUser = array(
            'id' => 1
        );
        $this->biz['user'] = $currentUser;
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\InvalidArgumentException
     */
    public function testInvalidParameterExceptionWhenCreateUser()
    {
        $user = $this->mockUser();
        $registerType = $this->biz['user.options']['register_type'];
        unset($user[$registerType]);

        $this->getUserService()->register($user);
    }

    public function testCreateUser()
    {
        unset($this->biz['user']);
        $fields = array('username', 'email', 'mobile');

        foreach ($fields as $registerType) {
            $user = $this->mockUser();
            $this->biz['user.options.register_type'] = $registerType;

            $diffTypes = array_diff($fields, array($registerType));

            foreach ($diffTypes as $diffType) {
                unset($user[$diffType]);
            }

            $savedUser = $this->getUserService()->register($user);
            $this->expectedUser($user, $savedUser);
        }
    }



    protected function expectedUser($expectedUser, $actualUser)
    {
        foreach (array('username', 'nickname', 'email', 'mobile', 'created_user_id', 'created_ip', 'created_source') as $key) {
            $this->assertArrayHasKey($key, $actualUser);
        }

        $this->assertArrayNotHasKey('password', $actualUser);
        $this->assertArrayNotHasKey('salt', $actualUser);

        foreach (array_keys($expectedUser) as $key) {
            if ($key != 'password') {
                $this->assertEquals($expectedUser[$key], $actualUser[$key]);
            }
        }
    }

    protected function mockUser()
    {
        return array(
            'username' => 'test',
            'email' => 'test@qq.com',
            'mobile' => '18911111111',
            'password' => '123456',
            'created_source' => 'web',
            'created_ip' => '127.0.0.1'
        );
    }

    /**
     * @return UserService
     */
    protected function getUserService()
    {
        return $this->biz->service('User:UserService');
    }

}