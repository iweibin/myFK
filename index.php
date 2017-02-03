<?php

// 开启调试模式
define('DEBUG',TRUE);

// 定义应用路径
define('APP_PATH','./app');

// 定义应用名称
define('APP_NAME','app');

// 模块名称
define('MODULE','index');

// 应用根目录
define('BASE_PATH',realpath('./'));

// 静态文件目录
define('__PUBLIC__', 'public');

// 加载框架核心文件
include __DIR__.'/core/myFK.php';
