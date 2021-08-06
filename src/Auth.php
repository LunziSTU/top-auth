<?php

namespace Lunzi\TopAuth;

use think\Facade;

/**
 * @method static bool check()
 * @method static int|string|null id()
 * @method static \Lunzi\TopAuth\Contracts\Authenticatable|null user()
 * @method static void login(\Lunzi\TopAuth\Contracts\Authenticatable $user, bool $remember = false)
 * @method static \Lunzi\TopAuth\Contracts\Guard|\Lunzi\TopAuth\Contracts\StatefulGuard guard(string|null $name = null)
 * @method static void logout()
 *
 * @see \Lunzi\TopAuth\AuthManager
 * @see \Lunzi\TopAuth\Contracts\Guard
 */
class Auth extends Facade
{
    /**
     * 获取当前Facade对应类名
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'Lunzi\TopAuth\AuthManager';
    }
}