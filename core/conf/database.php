<?php
return [
    // 必须配置项
    'database_type' => 'mysql',
    'database_name' => 'database_name',
    'server' => 'localhost',
    'username' => '',
    'password' => '',
    'charset' => 'utf8',
 
    // 可选参数
    'port' => 3306,
 
    // 可选，定义表的前缀
    'prefix' => 'th_',
 
    // 连接参数扩展, 更多参考 http://www.php.net/manual/en/pdo.setattribute.php
    'option' => [
        PDO::ATTR_CASE => PDO::CASE_NATURAL
    ]

];