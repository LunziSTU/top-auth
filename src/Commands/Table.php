<?php

namespace Lunzi\TopAuth\Commands;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\facade\Db;

class Table extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('table')
            ->addArgument('name', Argument::OPTIONAL, '表名', 'users')
            ->setDescription('新建用户表');
    }

    protected function execute(Input $input, Output $output)
    {
        $tableName = $input->getArgument('name');
        $tables = Db::query("show tables like '{$tableName}'");
        if (! empty($tables)) {
            $confirm = $output->confirm($input,'数据表已存在，是否覆盖？',false);
            if ($confirm != true) {
                return $output->writeln('取消创建。');
            }
        }

        $sql = file_get_contents(dirname(__FILE__,3).DIRECTORY_SEPARATOR.'stubs'.DIRECTORY_SEPARATOR.'user-table.stub');
        $sql = str_replace('{{table_name}}',$tableName,$sql);
        Db::execute($sql);
        $output->writeln("用户表 {$tableName} 创建完成!");
    }
}