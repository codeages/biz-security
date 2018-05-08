<?php

namespace Tests;

use Codeages\Biz\Framework\Util\ArrayToolkit;

class OrgServiceTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();
        $currentUser = array(
            'id' => 1
        );
        $this->biz['user'] = $currentUser;
    }

    public function testCreateOrg()
    {
        $org = $this->mockOrg();
        $savedOrg = $this->getOrgService()->createOrg($org);
        $this->assertOrg($org, $savedOrg);
    }

    protected function assertOrg($expectedOrg, $actrueOrg)
    {
        foreach ($expectedOrg as $key => $value) {
            $this->assertEquals($expectedOrg[$key], $actrueOrg[$key]);
        }
    }

    protected function mockOrg()
    {
        return array(
            'name' => '开发组',
            'code' => 'developer',
        );
    }

    protected function getOrgService()
    {
        return $this->biz->service('Org:OrgService');
    }
}
