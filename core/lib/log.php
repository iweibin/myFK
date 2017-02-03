<?php
namespace core\lib;
use core\lib\conf;

class log
{
	
	static $class;

	/**
	 * 确定日志存储方式 文件/数据库
	 */
	static public function init() {
		// 确定存储方式
		$driver = conf::get('driver','log');

		$class = '\core\lib\driver\log\\'.$driver;

		self::$class = new $class;
 
	}

	/**
	 * 记录日志
	 * @param [string] $message 日志内容
	 * @param [srting] $file 日志文件名 默认 log
	 */
	static public function log( $message ,$file = 'log' ) {

		self::$class->log($message ,$file);
	
	}
}