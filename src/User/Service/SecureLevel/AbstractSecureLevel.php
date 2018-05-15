<?php

namespace Codeages\Biz\User\Service\SecureLevel;

abstract class AbstractSecureLevel 
{
	protected $biz;

	public function __construct($biz)
	{
		$this->biz = $biz;
	}

	protected function getRegisterMode()
	{
		$userOptions = $this->biz['user.options.final'];
        return $userOptions['register_mode'];
    }

    protected function convert($str , $ucfirst = true)
	{
    	$str = explode('_' , $str);
	    foreach ($str as $key=>$val) {
	        $str[$key] = ucfirst($val);
	    }
	 
	    if (!$ucfirst) {
	        $str[0] = strtolower($str[0]);
	    }
	 
	    return implode('' , $str);
	}

	protected function checkEmailMode($user) 
	{
		return true;
	}

	protected function checkMobileMode($user) 
	{
		return true;
	}

	protected function checkUsernameMode($user) 
	{
		return true;
	}

	protected function checkEmailOrMobileMode($user) 
	{
		return true;
	}

	protected function getUserService()
	{
		return $this->biz->service('User:UserService');
	}

	public function check($user)
	{
		if ($this->inWhiteList($user)) {
			return;
		}

		$registerMode = $this->getRegisterMode();
		$registerMode = $this->convert($registerMode);
		$method = "check{$registerMode}Mode";
		$this->$method($user);
	}

	protected function inWhiteList($user)
	{
		return false;
	}
}
