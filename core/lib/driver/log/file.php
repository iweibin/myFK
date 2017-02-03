<?php
namespace core\lib\driver\log;
use core\lib\conf;

class file 
{
	public $option;// 日志其他配置项

	public function __construct() {

		$this->option = conf::get('option','log');
	
	}

	/**
	 * 写入日志
	 * @param [string] $message 日志内容
	 * @param [srting] $file 日志文件名 默认 log
	 */
	public function log($message ,$file = 'log') {
		
		/**
		 * 1 日志存储位置是否存在（否 ：新建目录）
		 * 
		 * 2 写入日志
		 */
		if( !is_dir($this->option['path']) ) {

			mkdir($this->option['path'] ,0777 ,true);
		
		}

		$message = date('[Y-m-d H:i:s]') .'  '. $message ;

		return file_put_contents($this->option['path'].$file ,json_encode($message).PHP_EOL ,FILE_APPEND);

	}
	
}
//文件系统存储日志
