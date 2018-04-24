<?php

namespace Tests;

use Codeages\Biz\Framework\Util\ArrayToolkit;

class RoleServiceTest extends IntegrationTestCase
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
    public function testInvalidParameterExceptionWhenCreateRole()
    {
        $role = $this->mockRole();
        unset($role['name']);

        $savedRole = $this->getRoleService()->createRole($role);
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\InvalidArgumentException
     */
    public function testCreateRoleWhenRoleIsExsits()
    {
        $role = $this->mockRole();
        $this->getRoleService()->createRole($role);
        $this->getRoleService()->createRole($role);
    }

    public function testCreateRole()
    {
        $role = $this->mockRole();
        $savedRole = $this->getRoleService()->createRole($role);
        $this->assertRole($role, $savedRole);
    }

    public function testUpdateRole()
    {
        $role = $this->mockRole();
        $savedRole = $this->getRoleService()->createRole($role);
        $role = array(
            'name' => '超级管理员',
            'code' => 'SuperAdmin',
            'data' => array('org:manage','org:create')
        );
        $savedRole = $this->getRoleService()->updateRole($savedRole['id'], $role);
        $role['code'] = 'Admin';
        $this->assertRole($role, $savedRole);
    }

    public function testDeleteRole()
    {
        $role = $this->mockRole();
        $savedRole = $this->getRoleService()->createRole($role);
        $this->getRoleService()->deleteRole($savedRole['id']);
        $savedRole = $this->getRoleService()->getRole($savedRole['id']);
        $this->assertEmpty($savedRole);
    }

    public function testDisableRole()
    {
        $role = $this->mockRole();
        $savedRole = $this->getRoleService()->createRole($role);
        $this->getRoleService()->disableRole($savedRole['id']);
        $savedRole = $this->getRoleService()->getRole($savedRole['id']);
        $this->assertEquals(0, $savedRole['enable']);
    }

    public function testEnableRole()
    {
        $role = $this->mockRole();
        $savedRole = $this->getRoleService()->createRole($role);
        $this->getRoleService()->enableRole($savedRole['id']);
        $savedRole = $this->getRoleService()->getRole($savedRole['id']);
        $this->assertEquals(1, $savedRole['enable']);
    }

    public function testFindRoles()
    {
        $role = $this->mockRole();
        $savedRole = $this->getRoleService()->createRole($role);
        
        $savedRole = $this->getRoleService()->getRole($savedRole['id']);
        $this->assertRole($role, $savedRole);

        $savedRole = $this->getRoleService()->getRoleByCode($savedRole['code']);
        $this->assertRole($role, $savedRole);

        $savedRoles = $this->getRoleService()->findRolesByCodes(array($savedRole['code']));
        $this->assertRole($role, $savedRoles[0]);
    }

    protected function mockRole()
    {
        return array(
            'code' => 'Admin',
            'name' => '超级管理员',
            'data' => array('org:manage')
        );
    }

    protected function assertRole($expectedRole, $actrueRole)
    {
        foreach ($expectedRole as $key => $value) {
            $this->assertEquals($expectedRole[$key], $actrueRole[$key]);
        }
    }

    protected function getRoleService()
    {
        return $this->biz->service('Role:RoleService');
    }
}
