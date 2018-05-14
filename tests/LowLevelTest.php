<?php

namespace Tests;

use Codeages\Biz\Framework\Util\ArrayToolkit;
use Codeages\Biz\User\Service\SecureLevel\LowLevel;

class LowLevelTest extends IntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();
        $currentUser = array(
            'id' => 1
        );
        $this->biz['user'] = $currentUser;

        $this->biz['user.options'] = array(
    		'register_mode' => 'email',      // username, email, mobile, email_or_mobile
            'register_secure_level' => 'low',  // none, low, middle, high
    	);
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\InvalidArgumentException
     */
    public function testCheckWhenCapthaEmpty()
    {
    	$lowLevel = new LowLevel($this->biz);
    	$user = $this->mockUser();
    	unset($user['captcha']);
    	$lowLevel->check($user);
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\InvalidArgumentException
     */
    public function testCheckWhenCapthaIsError()
    {
    	$lowLevel = new LowLevel($this->biz);
    	$user = $this->mockUser();
    	$user['captcha']['data'] = '123';
    	$lowLevel->check($user);
    }

    public function testCheck()
    {
    	$lowLevel = new LowLevel($this->biz);
    	$user = $this->mockUser();
    	$lowLevel->check($user);
    }

    protected function mockUser()
    {
    	$token = $this->getTokenService()->generate('user.register', 60, 1, array('captcha'=>'123456'));
    	$user = array(
    		'login_name' => 'test',
            'password' => '123456',
            'created_source' => 'web',
            'created_ip' => '127.0.0.1',
            'captcha' => array(
            	'key' => $token['key'],
            	'data' => '123456'
            )
    	);
    	return $user;
    }

    protected function getTokenService()
    {
    	return $this->biz->service('Token:TokenService');
    }
}