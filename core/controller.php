<?php
namespace core;

class controller {

	public $params;

	public function __construct() {

		$this->params['__PUBLIC__'] = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']).'/'.__PUBLIC__;


		// dump($_SERVER); exit;
		$request_uri = $_SERVER['PATH_INFO'];
		$requestArr = explode('/', trim($request_uri,'/'));
		
		$this->params['__CONTROLLER__'] = $requestArr[0];
		$this->params['__ACTION__'] = $requestArr[1];
		// dump($this->params['__PUBLIC__']);exit;

	}
	/**
	 *  变量赋值
	 *
	 * @param $var [string|array] 变量名称
	 * @param $value [string|array] 变量值
	 */
	public function assign($var ,$value = null ) {

		if( is_array($var) ) {
			foreach ($var as $key => $val) {
				$this->params[$key] = $val;
			}
		} else {
			$this->params[$var] = $value;
		}

	}

	public function display( $file = 'index' ) {


		$file_path = APP_PATH.'/'.MODULE.'/views/'.$file.'.php';

		if( is_file($file_path) ) {

			// extract($this->params);
			// include $file;


			/*引入 twig 模板引擎*/
			\Twig_Autoloader::register();

			$loader = new \Twig_Loader_Filesystem(APP_PATH.'/'.MODULE.'/views');

			$twig = new \Twig_Environment($loader, array(

			    'cache' => BASE_PATH.'/cache/log/twig',

			    'debug' => DEBUG

			));

			// 添加 site_url 模板函数
			$twig->addFunction(new \Twig_SimpleFunction('site_url','site_url'));

			$template = $twig->loadTemplate($file.'.php');

			$template->display($this->params ? $this->params : array());
		}

	}
}