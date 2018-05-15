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
        unset($user['login_name']);

        $this->getUserService()->register($user);
    }

    public function testCreateUserWhenUsernameMode()
    {
        $this->createUserByRegisterMode('username');
    }

    public function testCreateUserWhenEmailMode()
    {
        $this->createUserByRegisterMode('email');
    }

    public function testCreateUserWhenMobileMode()
    {
        $this->createUserByRegisterMode('mobile');
    }

    protected function createUserByRegisterMode($mode)
    {
        $this->biz['user.options']['register_mode'] = $mode;
        unset($this->biz['user']);
        $user = $this->mockUser();
        $savedUser = $this->getUserService()->register($user);
        $this->expectedUser($user, $savedUser);
    }

    public function testCreateUserWithBindType()
    {
        $user = $this->mockUser();
        $userBind = $this->mockUserBind();
        $bind = array_merge($user, $userBind);
        $savedUser = $this->getUserService()->bindUser($bind);
        $this->expectedUser($user, $savedUser);
        $this->expectedUserBind($userBind, $savedUser['bind']);
    }

    public function testUnbindUser()
    {
        $user = $this->mockUser();
        $userBind = $this->mockUserBind();
        $bind = array_merge($user, $userBind);
        $savedUser = $this->getUserService()->bindUser($bind);

        $this->getUserService()->unbindUser($userBind['type'], $userBind['bind_id']);

        $bind = $this->getUserBindDao()->get($savedUser['bind']['id']);
        $this->assertEmpty($bind);
    }

    public function testChangePassword()
    {
        $user = $this->mockUser();
        $userBind = $this->mockUserBind();
        $bind = array_merge($user, $userBind);
        $savedUser = $this->getUserService()->bindUser($bind);

        $savedUser = $this->getUserService()->changePassword($savedUser['id'], '123456', $user['password']);
        $this->expectedUser($user, $savedUser);
    }

    public function testlockUser()
    {
        $user = $this->mockUser();
        $userBind = $this->mockUserBind();
        $bind = array_merge($user, $userBind);
        $savedUser = $this->getUserService()->bindUser($bind);
        $this->assertEquals(0, $savedUser['locked']);

        $savedUser = $this->getUserService()->lockUser($savedUser['id']);
        $this->assertEquals(1, $savedUser['locked']);

        $savedUser = $this->getUserService()->unlockUser($savedUser['id']);
        $this->assertEquals(0, $savedUser['locked']);
    }

    public function testVerifyEmail()
    {
        $user = $this->mockUser();
        $userBind = $this->mockUserBind();
        $bind = array_merge($user, $userBind);
        $savedUser = $this->getUserService()->bindUser($bind);
        $this->assertEquals(0, $savedUser['email_verified']);

        $savedUser = $this->getUserService()->verifyEmail($savedUser['id']);
        $this->assertEquals(1, $savedUser['email_verified']);
    }

    public function testVerifyMobile()
    {
        $user = $this->mockUser();
        $userBind = $this->mockUserBind();
        $bind = array_merge($user, $userBind);
        $savedUser = $this->getUserService()->bindUser($bind);
        $this->assertEquals(0, $savedUser['mobile_verified']);

        $savedUser = $this->getUserService()->verifyMobile($savedUser['id']);
        $this->assertEquals(1, $savedUser['mobile_verified']);
    }

    public function testRenameUsername()
    {
        $user = $this->mockUser();
        $userBind = $this->mockUserBind();
        $bind = array_merge($user, $userBind);
        $savedUser = $this->getUserService()->bindUser($bind);

        $savedUser = $this->getUserService()->renameUsername($savedUser['id'], 'hello_edusoho');
        $this->assertEquals('hello_edusoho', $savedUser['username']);
    }

    public function testRenameNickname()
    {
        $user = $this->mockUser();
        $userBind = $this->mockUserBind();
        $bind = array_merge($user, $userBind);
        $savedUser = $this->getUserService()->bindUser($bind);

        $savedUser = $this->getUserService()->renameNickname($savedUser['id'], '张三丰');
        $this->assertEquals('张三丰', $savedUser['nickname']);
    }

    public function testLogin()
    {
        $user = $this->mockUser();
        $userBind = $this->mockUserBind();
        $bind = array_merge($user, $userBind);
        $savedUser = $this->getUserService()->bindUser($bind);

        $savedUser = $this->getUserService()->login($user['login_name'], $user['password']);
        $this->expectedUser($user, $savedUser);
    }

    public function testClearRoles()
    {
        $user = $this->mockUser();
        $userBind = $this->mockUserBind();
        $bind = array_merge($user, $userBind);
        $savedUser = $this->getUserService()->bindUser($bind);

        $role1 = $this->mockRole();
        $savedRole1 = $this->getRoleService()->createRole($role1);

        $role2 = $this->mockRole();
        $role2['code'] = 'SuperAdmin';
        $savedRole2 = $this->getRoleService()->createRole($role2);

        $this->getUserService()->reBindRolesByUserId($savedUser['id'], array($savedRole1['id'], $savedRole2['id']));

        $roles = $this->getUserService()->findRolesByUserId($savedUser['id']);
        $this->assertRole($role1, $roles[0]);
        $this->assertRole($role2, $roles[1]);

        $this->getUserService()->clearRolesByUserId($savedUser['id']);
        $roles = $this->getUserService()->findRolesByUserId($savedUser['id']);
        $this->assertEmpty($roles);
    }

    public function testHasPermissions()
    {
        $user = $this->mockUser();
        $userBind = $this->mockUserBind();
        $bind = array_merge($user, $userBind);
        $savedUser = $this->getUserService()->bindUser($bind);

        $role1 = $this->mockRole();
        $savedRole1 = $this->getRoleService()->createRole($role1);

        $role2 = $this->mockRole();
        $role2['code'] = 'SuperAdmin';
        $role2['data'] = array('user:manage', 'user:create');
        $savedRole2 = $this->getRoleService()->createRole($role2);

        $this->getUserService()->reBindRolesByUserId($savedUser['id'], array($savedRole1['id'], $savedRole2['id']));

        $this->assertTrue($this->getUserService()->hasPermissions($savedUser['id'], $role2['data']));
        $this->assertFalse($this->getUserService()->hasPermissions($savedUser['id'], array('user:delete')));

        $this->getRoleService()->deleteRole($savedRole2['id']);
        $this->assertFalse($this->getUserService()->hasPermissions($savedUser['id'], $role2['data']));
    }

    protected function expectedUserBind($expectedBind, $actualBind, $unAssertKeys = array())
    {
        foreach (array('type', 'type_alias', 'bind_id') as $key) {
            $this->assertArrayHasKey($key, $actualBind);
        }

        $this->assertArrayHasKey('user_id', $actualBind);

        foreach (array_keys($expectedBind) as $key) {
            if (!empty($unAssertKeys) && !in_array($key, $unAssertKeys)) {
                $this->assertEquals($expectedBind, $actualBind);
            }
        }
    }

    protected function assertRole($expectedRole, $actrueRole)
    {
        foreach ($expectedRole as $key => $value) {
            $this->assertEquals($expectedRole[$key], $actrueRole[$key]);
        }
    }

    protected function expectedUser($expectedUser, $actualUser, $unAssertKeys = array())
    {
        foreach (array('username', 'nickname', 'email', 'mobile', 'created_user_id', 'created_ip', 'created_source') as $key) {
            $this->assertArrayHasKey($key, $actualUser);
        }

        $this->assertArrayNotHasKey('password', $actualUser);
        $this->assertArrayNotHasKey('salt', $actualUser);

        foreach (array_keys($expectedUser) as $key) {
            if ($key != 'password' && (!empty($unAssertKeys) && !in_array($key, $unAssertKeys))) {
                $this->assertEquals($expectedUser[$key], $actualUser[$key]);
            }
        }
    }

    protected function mockUserBind()
    {
        return array(
            'type' => 'wechat_app',
            'type_alias' => 'wechat',
            'bind_id' => '12345',
            'bind_ext' => '23456333',
        );
    }

    protected function mockUser()
    {
        $org = $this->mockOrg();
        $savedOrg = $this->getOrgService()->createOrg($org);

        return array(
            'login_name' => 'test',
            'password' => '123456',
            'created_source' => 'web',
            'created_ip' => '127.0.0.1',
            'org_id' => $savedOrg['id']
        );
    }

    protected function mockRole()
    {
        return array(
            'code' => 'Admin',
            'name' => '超级管理员',
            'data' => array('org:manage')
        );
    }

    protected function mockOrg()
    {
        return array(
            'name' => '开发组',
            'code' => 'developer',
        );
    }

    /**
     * @return UserService
     */
    protected function getUserService()
    {
        return $this->biz->service('User:UserService');
    }

    protected function getRoleService()
    {
        return $this->biz->service('Role:RoleService');
    }

    protected function getOrgService()
    {
        return $this->biz->service('Org:OrgService');
    }

    protected function getUserBindDao()
    {
        return $this->biz->dao('User:UserBindDao');
    }
}
