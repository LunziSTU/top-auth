# TopAuth
TP6 用户认证扩展包

目前还未发布稳定版本 🔥🔥🔥 广邀内测，欢迎提 issue ！
>交流群：920629735

文档地址：[https://www.kancloud.cn/newphper/top-auth](https://www.kancloud.cn/newphper/top-auth)

## 安装
> 本扩展为 TP6 开发， 请首先安装 ThinkPHP 6.0 及以上版本。

通过 Composer 安装

    composer require lunzi/top-auth

## 核心概念

### 看守器（Guard）
看守器决定如何对每个请求的用户进行身份验证。

### 提供者（Provider）
提供者决定如何从数据库中检索用户。

## 配置
若您的应用只有一种认证场景，可以使用默认设置，无需配置。
>此处文档有待完善

## 用户认证
### 路由中间件
    \Lunzi\TopAuth\Middleware\Authenticate::class
#### 例1：在路由定义中使用
* 简单模式
```
Route::group('admin', function(){
})->middleware(\Lunzi\TopAuth\Middleware\Authenticate::class);
```
* 指定看守器
```
Route::group('admin', function(){
})->middleware(\Lunzi\TopAuth\Middleware\Authenticate::class, 'admin');
```
#### 例2：在路由配置文件 (?应用/config/route.php) 中配置全局中间件
* 简单模式
```
'middleware' => [
    [\Lunzi\TopAuth\Middleware\Authenticate::class]
]
```
* 指定看守器
```
'middleware' => [
    [\Lunzi\TopAuth\Middleware\Authenticate::class, ['admin']]
]
```
### 在控制器中检查用户是否登录, 返回布尔值
你可以通过 Auth facade 来检查：
```
use Lunzi\TopAuth\Auth;
```
* 简单模式
```
Auth::check();
```
* 指定看守器
```
Auth::guard('admin')->check();
```

## 检索认证用户
你可以通过 Auth facade 来访问已认证的用户：
```
use Lunzi\TopAuth\Auth;
```
* 简单模式
```
获取用户信息：Auth::user();
获取用户ID：Auth::id();
```
* 指定看守器
```
获取用户信息：Auth::guard('admin')->user();
获取用户ID：Auth::guard('admin')->id();
```