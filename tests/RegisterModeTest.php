<?php

namespace Tests;

use Codeages\Biz\User\Service\RegisterStrategy\EmailRegisterMode;
use Codeages\Biz\User\Service\RegisterStrategy\MobileRegisterMode;
use Codeages\Biz\User\Service\RegisterStrategy\UsernameRegisterMode;

class RegisterModeTest extends IntegrationTestCase
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
            'register_secure_level' => 'high',  // none, low, middle, high
    	);


    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\InvalidArgumentException
     */
    public function testFillUnRegisterUserWhenLoginnameIsInvalidByEmailMode()
    {
        $emailRegisterMode = $this->createEmailRegisterMode();
        $user = $this->mockUser();
        $unregisterUser = $emailRegisterMode->fillUnRegisterUser($user);

    }

    public function testFillUnRegisterUserByEmailMode()
    {
        $emailRegisterMode = $this->createEmailRegisterMode();
        $user = $this->mockUser();
        $user['login_name'] = 'test@qq.com';
        $unregisterUser = $emailRegisterMode->fillUnRegisterUser($user);
        $this->assertEquals($user['login_name'], $unregisterUser['email']);
        $this->assertEquals('test', $unregisterUser['nickname']);
        $this->assertEmpty($unregisterUser['mobile']);
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\InvalidArgumentException
     */
    public function testFillUnRegisterUserWhenLoginnameIsInvalidByMobileMode()
    {
        $registerMode = $this->createMobileRegisterMode();
        $user = $this->mockUser();
        $unregisterUser = $registerMode->fillUnRegisterUser($user);

    }

    public function testFillUnRegisterUserByMobileMode()
    {
        $registerMode = $this->createMobileRegisterMode();
        $user = $this->mockUser();
        $user['login_name'] = '18765467896';
        $unregisterUser = $registerMode->fillUnRegisterUser($user);

        $this->assertEquals($user['login_name'], $unregisterUser['mobile']);
        $this->assertNotEmpty($unregisterUser['nickname']);
        $this->assertEmpty($unregisterUser['email']);
    }

    /**
     * @expectedException Codeages\Biz\Framework\Service\Exception\InvalidArgumentException
     */
    public function testFillUnRegisterUserWhenLoginnameIsInvalidByUsernameMode()
    {
        $registerMode = $this->createUsernameRegisterMode();
        $user = $this->mockUser();
        $user['login_name'] = '18765467896';
        $unregisterUser = $registerMode->fillUnRegisterUser($user);

    }

    public function testFillUnRegisterUserByUsernameMode()
    {
        $registerMode = $this->createUsernameRegisterMode();
        $user = $this->mockUser();
        $unregisterUser = $registerMode->fillUnRegisterUser($user);

        $this->assertEquals($user['login_name'], $unregisterUser['username']);
        $this->assertEquals($user['login_name'], $unregisterUser['nickname']);
        $this->assertEmpty($unregisterUser['email']);
        $this->assertEmpty($unregisterUser['mobile']);
    }

    protected function mockUser()
    {
        return array(
            'login_name' => 'test',
            'password' => '123456',
            'created_source' => 'web',
            'created_ip' => '127.0.0.1',
            'captcha' => array(
                'key' => $token['key'],
                'data' => '123456'
            )
        );
    }

    protected function createMobileRegisterMode()
    {
        $mobileRegisterMode = new MobileRegisterMode($this->biz);
        return $mobileRegisterMode;
    }

    protected function createUsernameRegisterMode()
    {
        $mobileRegisterMode = new UsernameRegisterMode($this->biz);
        return $mobileRegisterMode;
    }

    protected function createEmailRegisterMode()
    {
        $emailRegisterMode = new EmailRegisterMode($this->biz);
        return $emailRegisterMode;
    }
}
