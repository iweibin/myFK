<?php
namespace core;
header("Content-type: text/html; charset=utf8");
date_default_timezone_set('PRC');

include BASE_PATH.'/vendor/autoload.php'; // 加载扩展类库 vendor

/*是否开启调试模式*/
if(DEBUG) {

	$whoops = new \Whoops\Run;
	$errorTitle = "框架出错了";
	$option = new \Whoops\Handler\PrettyPageHandler();
	$option->setPageTitle($errorTitle);
	$whoops->pushHandler($option);
	$whoops->register();

	ini_set('display_error','On');
} else {
	ini_set('display_error','Off');
}

// 加载函数库
include BASE_PATH.'/core/common/function.php';


class myFK 
{

	public static $classMap = array();//类库缓存

	// 框架启动
	static public function run() {

		\core\lib\log::init();// 日志类初始化

		$route = new \core\lib\route();// 路由类实例化

		$controller = $route->controller;

		$action = $route->action;

		$params = $route->params;

		$ctrlFile = APP_PATH.'/'.MODULE.'/controller/'.$controller.'Controller.php';// 控制器文件

		$ctrlClass = '\\'.APP_NAME.'\\'.MODULE.'\controller\\'.$controller.'Controller';// 控制器命名空间


		if( is_file($ctrlFile) ) {

			include $ctrlFile;

			$ctrl = new $ctrlClass();// 实例化控制器

			// $ctrl->$action(); // 调用控制器中的方法
			
			if( !empty($params) ) {

				return eval('return $ctrl->$action("'.implode('","', $params).'");');

			}

			return $ctrl->$action();

		} else {

			throw new \Exception("找不到控制器".$controller, 1);

		}

	}

	// 自动加载类库
	static public function load($class) {	

		if( isset($classMap[$class]) ) {

			return TRUE;

		} else {

			$class = str_replace('\\', '/', $class);

			$classfile = BASE_PATH.'/'.$class.'.php';

			if(is_file($classfile)) {

				include $classfile;

				self::$classMap[$class] = $class;

			} else {

				return FALSE;

			}

		}

	}

}      

//当实例化一个不存在的类时自动调用 myFK::load 进行自动加载类库
spl_autoload_register('\core\myFK::load');

// 启动框架
\core\myFK::run();