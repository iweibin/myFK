<?php
namespace core\lib;
use core\lib\conf;

class route 
{
	public $controller;

	public $action;

	public $params = array();

	public function __construct() {
		// xxx.com/index.index
		/**
		 *  1 隐藏index.php
		 *	2 获取url 参数部分
		 *	3 返回相应的 控制器/方法
		 */
		// dump($_SERVER);exit;

		if( isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'] != '/') {

			$request_uri = $_SERVER['PATH_INFO'];

			$url = explode('?' , $request_uri);

			$requestArr = explode('/', trim($url[0] ,'/'));

			if( isset($requestArr[0]) ) {

				$this->controller = $requestArr[0];

				unset($requestArr[0]);

			}

			if( isset($requestArr[1]) ) {

				$this->action = $requestArr[1];

				unset($requestArr[1]);

			} else {

				$this->action = conf::get('action','route');

				$_SERVER['PATH_INFO'] = $_SERVER['PATH_INFO'].'/'.$this->action;

			}

			// 参数多余部分 GET

			if( $_SERVER['QUERY_STRING'] ) {

				$paramArr = explode('&', $_SERVER['QUERY_STRING']);

				foreach ($paramArr as $value) {

					$str = explode('=', $value);

					$this->params[$str[0]] = $str[1];

					if( $_SERVER['REQUEST_METHOD'] == 'POST')

						$_POST[$str[0]] = urldecode($str[1]);

					else

						$_GET[$str[0]] = urldecode($str[1]);

				}
				 // dump(implode("','", $this->params));exit;
			} else {

				foreach ($requestArr as $value) {
					
					$this->params[] = $value;

				}
			
				
			}

		} else {

			// 返回默认控制器
			$this->controller = conf::get('controller','route');

			// 返回默认方法
			$this->action = conf::get('action','route');

			$_SERVER['PATH_INFO'] = '/'.$this->controller.'/'.$this->action;
		
		}

	}


}