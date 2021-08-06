<?php

namespace Lunzi\TopAuth\Middleware;

use Lunzi\TopAuth\Auth;

class Authenticate
{

    /**
     * 处理请求
     * @param $request
     * @param \Closure $next
     * @param null $guard 指定的看守器名称
     * @return mixed|\think\response\Redirect
     */
    public function handle($request, \Closure $next, $guard = null)
    {
        $route = $request->controller(1) . '/' . $request->action(1);

        if ( ! in_array($route, $this->withoutAuthRoute()) && ! Auth::guard($guard)->check() ) {
            return $this->redirectTo();
        }
        return $next($request);
    }

    /**
     * 获取不需验证的路由
     *
     * @return array
     */
    protected function withoutAuthRoute(): array
    {
        $routes = array_merge(
            array_values(config('topauth.route')),
            config('topauth.white_list_route')
        );
        foreach ($routes as &$route) {
            $route = strtolower($route);
        }
        return $routes;
    }

    /**
     * 未认证跳转
     *
     * @return \think\response\Redirect
     */
    protected function redirectTo()
    {
        $url = config('topauth.redirect_to') ?? (($appName = app()->http->getName()) ? '/' . $appName : '') . '/' . strtolower(config('topauth.route.login'));
        return redirect($url)->remember();
    }
}