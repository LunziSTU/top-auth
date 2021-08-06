<?php

namespace Lunzi\TopAuth;

use Closure;
use InvalidArgumentException;
use Lunzi\TopAuth\Guards\SessionGuard;
use Lunzi\TopAuth\Providers\ModelUserProvider;

class AuthManager
{

    /**
     * 看守器驱动列表
     *
     * @var array
     */
    protected $guards = [];

    /**
     * 用户决策器
     */
    protected $userResolver;


    public function __construct()
    {
        $this->userResolver = function ($guard = null) {
            return $this->guard($guard)->user();
        };
    }

    /**
     * 获取看守器
     *
     * @param string|null $name
     * @return mixed
     * @throws \think\Exception
     */
    public function guard(string $name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->guards[$name] ?? $this->guards[$name] = $this->resolve($name);
    }

    /**
     * 执行给定的看守器
     *
     * @param string $name
     * @throws \think\Exception
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new \think\Exception("看守器 [{$name}] 未定义");
        }

        $driverMethod = 'create'.ucfirst($config['driver']).'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($name, $config);
        }

        throw new \think\Exception(
            "看守器 [{$name}] 的驱动程序 [{$config['driver']}] 未定义"
        );
    }

    /**
     * 创建基于 Session 的看守器驱动
     *
     * @param  string  $name
     * @param  array  $config
     */
    public function createSessionDriver($name, $config)
    {
        $providerConfig = config("topauth.providers.{$config['provider']}");
        $provider = new ModelUserProvider($providerConfig['model']);

        $guard = new SessionGuard($name, $provider);

        return $guard;
    }

    /**
     * 获取扩展配置
     * @param $name
     * @return mixed
     */
    protected function getConfig($name)
    {
        return config("topauth.guards.{$name}");
    }

    /**
     * 获取默认验证驱动名称
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return config("topauth.defaults.guard");
    }

    /**
     * 动态调用默认驱动实例
     * @param $method
     * @param $parameters
     * @return mixed
     * @throws \think\Exception
     */
    public function __call($method, $parameters)
    {
        return $this->guard()->{$method}(...$parameters);
    }
}
