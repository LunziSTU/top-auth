<?php

namespace Lunzi\TopAuth\Commands;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class Install extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('install')
            ->setDescription('安装 TopAuth 配置文件到应用');
    }

    protected function execute(Input $input, Output $output)
    {
        $targetFilename = $this->app->getConfigPath().'topauth.php';
        if (is_file($targetFilename)) {
            $confirm = $output->confirm($input,'配置文件已存在，是否覆盖？',false);
            if ($confirm != true) {
                return $output->writeln('取消安装。');
            }
        }
        $config = file_get_contents(dirname(__FILE__,3).DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php');
        file_put_contents(
            $this->app->getConfigPath().'topauth.php',
            $config
        );
        $output->writeln('安装完成!');
    }
}