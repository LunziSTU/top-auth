<?php

namespace Lunzi\TopAuth;

use think\Service as BaseService;

class Service extends BaseService
{
    public function boot()
    {
        $this->loadConfig();
    }

    /**
     * 加载配置
     */
    protected function loadConfig()
    {
        $config = include dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
        $customConfig = $this->app->config->get('topauth');
        if ( !is_null($customConfig) ) {
//            默认配置可被覆盖
            $config = array_merge($config, $customConfig);
        }
        $this->app->config->set($config,'topauth');
    }
}
