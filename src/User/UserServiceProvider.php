<?php

namespace Codeages\Biz\User;

use Codeages\Biz\User\Service\RegisterStrategy\EmailOrMobileRegisterMode;
use Codeages\Biz\User\Service\RegisterStrategy\EmailRegisterMode;
use Codeages\Biz\User\Service\RegisterStrategy\MobileRegisterMode;
use Codeages\Biz\User\Service\RegisterStrategy\UsernameRegisterMode;
use Codeages\Biz\User\Service\SecureLevel\NoneLevel;
use Codeages\Biz\User\Service\SecureLevel\LowLevel;
use Codeages\Biz\User\Service\SecureLevel\MiddleLevel;
use Codeages\Biz\User\Service\SecureLevel\HighLevel;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class UserServiceProvider implements ServiceProviderInterface
{
    public function register(Container $biz)
    {
        $biz['migration.directories'][] = dirname(dirname(__DIR__)).'/migrations/user';
        $biz['autoload.aliases']['User'] = 'Codeages\Biz\User';

        $biz['console.commands'][] = function () use ($biz) {
            return new \Codeages\Biz\User\Command\TableCommand($biz);
        };

        $biz['user.options'] = array();

        $biz['user.options.final'] = $biz->factory(function () use ($biz) {
            return array_merge(array(
                'register_mode' => 'username',      // username, email, mobile, email_or_mobile
                'register_secure_level' => 'none',  // none, low, middle, high
            ), $biz['user.options']);
        });

        $this->registerModes($biz);
        $this->registerSecureLevels($biz);
    }

    protected function registerModes($biz)
    {
        $registerModes = array(
            'email' => EmailRegisterMode::class,
            'mobile' => MobileRegisterMode::class,
            'username' => UsernameRegisterMode::class,
            'email_or_mobile' => EmailOrMobileRegisterMode::class,
        );

        $modeKeys = array_keys($registerModes);
        foreach ($modeKeys as $registerMode) {
            $class = $registerModes[$registerMode];
            $biz['user_register_mode.'.$registerMode] = function () use ($biz, $class) {
                return new $class($biz);
            };
        }
    }

    protected function registerSecureLevels($biz)
    {
        $levels = array(
            'none' => NoneLevel::class,
            'low' => LowLevel::class,
            'middle' => MiddleLevel::class,
            'high' => HighLevel::class,
        );

        $levelKeys = array_keys($levels);
        foreach ($levelKeys as $levelKey) {
            $class = $levels[$levelKey];
            $biz['user_register_secure_level.'.$levelKey] = function () use ($biz, $class) {
                return new $class($biz);
            };
        }
    }
}
