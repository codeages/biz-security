<?php

namespace Codeages\Biz\User\Service\SecureLevel;

class HighLevel extends AbstractSecureLevel 
{
	protected function checkEmailMode($user)
	{
		$lowLevel = $this->biz['user_register_secure_level']['low'];
		if (!$lowLevel->checkEmail($user) && !$this->checkRegistedUsers($user)) {
			return false;
		}
		return true;
	}

	protected function checkMobileMode($user)
	{
		$lowLevel = $this->biz['user_register_secure_level']['low'];
		if (!$lowLevel->checkMobileMode($user) && !$this->checkRegistedUsers($user)) {
			return false;
		}
		return true;
	}

	protected function checkEmailOrMobileMode($user)
	{
		$lowLevel = $this->biz['user_register_secure_level']['low'];
		if (!$lowLevel->checkEmailOrMobileMode($user) && !$this->checkRegistedUsers($user)) {
			return false;
		}
		return true;
	}

	protected function checkRegistedUsers($user)
	{
		$condition = array(
            'startTime' => time() - 24 * 3600,
            'createdIp' => $user['created_ip'],
        );
        $registerCount = $this->getUserService()->countUsers($condition);

        if ($registerCount > 10) {
            return false;
        }

        $registerCount = $this->getUserService()->countUsers(array(
            'startTime' => time() - 3600,
            'createdIp' => $user['created_ip'],
        ));

        if ($registerCount >= 1) {
            return false;
        }

        return true;
	}
}
