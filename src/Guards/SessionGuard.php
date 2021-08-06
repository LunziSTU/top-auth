<?php

namespace Lunzi\TopAuth\Guards;

use Lunzi\TopAuth\Recaller;
use Lunzi\TopAuth\Contracts\UserProvider;
use Lunzi\TopAuth\GuardHelpers;
use Lunzi\TopAuth\Contracts\Authenticatable as AuthenticatableContract;
use think\facade\Cookie;
use think\facade\Request;
use think\helper\Str;

class SessionGuard
{
    use GuardHelpers;

    /**
     * 看守器名称
     * @var string
     */
    protected $name;

    /**
     * 当前认证用户
     * @var
     */
    protected $user;

    /**
     * 是否已调用注销方法
     * @var bool
     */
    protected $loggedOut = false;

    /**
     * 是否已尝试检索令牌用户
     * @var bool
     */
    protected $recallAttempted = false;

    public function __construct($name, UserProvider $provider)
    {
        $this->name = $name;
        $this->provider = $provider;
    }

    /**
     * 创建一个 session 标识
     * @return string
     */
    public function getName(): string
    {
        return 'login_'.$this->name.'_'.sha1(static::class);
    }

    /**
     * 创建一个 cookie 标识
     * @return string
     */
    public function getRecallerName(): string
    {
        return 'remember_'.$this->name.'_'.sha1(static::class);
    }

    /**
     * 获取当前用户
     * @return \Lunzi\TopAuth\Contracts\Authenticatable|void|null
     */
    public function user()
    {
        if ($this->loggedOut) {
            return;
        }

        if (! is_null($this->user)) {
            return $this->user;
        }

        $id = session($this->getName());

        ! is_null($id) && $this->user = $this->provider->retrieveById($id);


        if (is_null($this->user) && ! is_null($recaller = $this->recaller())) {
            $this->user = $this->userFromRecaller($recaller);

            if ($this->user) {
                $this->updateSession($this->user->getAuthIdentifier());
            }
        }

        return $this->user;
    }

    /**
     * 判断当前用户是否登录
     * @return bool
     */
    public function check()
    {
        return ! is_null($this->user());
    }

    /**
     * 登录
     * @param AuthenticatableContract $user
     * @param false $remember
     */
    public function login(AuthenticatableContract $user, $remember = false)
    {
        $this->updateSession($user->getAuthIdentifier());

        if ($remember) {
            $this->ensureRememberTokenIsSet($user);

            $this->queueRecallerCookie($user);
        }

        $this->setUser($user);
    }

    /**
     * 注销登录
     */
    public function logout()
    {
        $user = $this->user();
        $this->clearUserDataFromStorage();

        if (! is_null($this->user) && ! empty($user->getRememberToken())) {
            $this->cycleRememberToken($user);
        }

        $this->user = null;

        $this->loggedOut = true;
    }

    /**
     * 设置当前用户
     * @param $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        $this->loggedOut = false;

        return $this;
    }

    /**
     * 从 session 和 cookie 中移除用户数据
     *
     * @return void
     */
    protected function clearUserDataFromStorage()
    {
        session($this->getName(), null);

        if (! is_null($this->recaller())) {
            Cookie::delete($this->getRecallerName());
        }
    }

    /**
     * 如果未创建 “记住我” token, 新建一个
     * @param $user
     */
    protected function ensureRememberTokenIsSet($user)
    {
        if (empty($user->getRememberToken())) {
            $this->cycleRememberToken($user);
        }
    }

    /**
     * 刷新 “记住我” token
     *
     * @param  $user
     * @return void
     */
    protected function cycleRememberToken($user)
    {
        $user->setRememberToken($token = Str::random(60));

        $this->provider->updateRememberToken($user, $token);
    }

    /**
     * 设置 cookie
     * @param $user
     */
    protected function queueRecallerCookie($user)
    {
        Cookie::forever(
            $this->getRecallerName(),
            $user->getAuthIdentifier().'|'.$user->getRememberToken().'|'.$user->getAuthPassword()
        );
    }

    /**
     * 获取反序列化的 cookie
     * @return Recaller|void
     */
    protected function recaller()
    {
        if ($recaller = Request::cookie($this->getRecallerName())) {
            return new Recaller($recaller);
        }
    }

    /**
     * 通过 cookie 拉取用户
     * @param $recaller
     * @return \Lunzi\TopAuth\Contracts\Authenticatable|void|null
     */
    protected function userFromRecaller($recaller)
    {
        if (! $recaller->valid() || $this->recallAttempted) {
            return;
        }

        $this->recallAttempted = true;

        $this->viaRemember = ! is_null($user = $this->provider->retrieveByToken(
            $recaller->id(), $recaller->token()
        ));

        return $user;
    }

    /**
     * 更新 session
     *
     * @param string $id
     * @return void
     */
    protected function updateSession(string $id)
    {
        session($this->getName(), $id);
    }
}