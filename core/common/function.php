<?php
/**
 * 公共函数库
 */

function p( $var ) {

	if(is_bool($var)) {

		var_dump($var);

	} else if(is_null($var)) {

		var_dump(NULL);

	} else {

		echo "<pre srtle=' position:relative; z-index:1000; padding:10px; border:1px solid #000; border-radius: 5px; font-size:14px; line-height: 18px; background-color: #CCC;'>".print_r($var,true)."</pre>";
	}

}


/**
 * 返回 post 值
 *
 * @param [string] $name 对应值
 * @param [string] $default 默认值
 * @param [string] $fliter 过滤方法 'int'...
 * @return 
 */
function post( $name = false ,$default = false ,$filter = false ) {

	if( $name ) {

		if( $_POST[$name] ) {

			if( $filter ) {
				switch( $filter ) {
					case 'int':
						if( is_numeric($_POST[$name]) ) {
							return $_POST[$name];
						} else {
							return $default;
						}
						break;
					default: ;
				}
			}

		} else {
			return $default;
		}

	} else {
		return $_POST;
	}
}

/**
 * 返回 get 值
 *
 * @param [string] $name 对应值
 * @param [string] $default 默认值
 * @param [string] $fliter 过滤方法 'int'...
 * @return 
 */
function get( $name = false ,$default = false ,$filter = false ) {

	if( $name ) {

		if( $_GET[$name] ) {

			if( $fliter ) {
				switch( $filter ) {
					case 'int':
						if( is_numeric($_GET[$name]) ) {
							return $_GET[$name];
						} else {
							return $default;
						}
						break;
					default: ;
				}
			}

		} else {
			return $default;
		}

	} else {
		return $_GET;
	}

}

/**
 * 发起一个post请求到指定接口
 * 
 * @param string $api 请求的接口
 * @param array $params post参数
 * @param int $timeout 超时时间
 * @return string 请求结果
 */
function postRequest( $api, array $params = array(), $timeout = 30 ) {

	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $api );
	// 以返回的形式接收信息
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	// 设置为POST方式
	curl_setopt( $ch, CURLOPT_POST, 1 );
	curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
	// 不验证https证书
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
	curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
		'Accept: application/json',
	) ); 
	// 发送数据
	$response = curl_exec( $ch );
	// 不要忘记释放资源
	curl_close( $ch );
	
	return $response;
}



/**
 * 存储 session
 *
 * @param $var [string|array] 变量名称
 * @param $redirect [string|array] 变量值
 */
function session($var ,$value = null) {
	session_start();


	if( is_array($var) && $value == null) {
		foreach ($var as $key => $val) {
			$_SESSION[$key] = $val;	
		}
	} elseif($value == null) {
		if( isset($_SESSION[$var])) {
			return  $_SESSION[$var];
		} else {
			return null;
		}
	} else {
		$_SESSION[$var] = $value;
	}
	// dump($_SESSION);exit;
}

/**
 *  生成路径
 *
 * @param $var [string] 控制器/方法
 * @return [string]
 */
function site_url( $var = null ) {

	$url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']; 

	return $url.'/'.$var;
}

/**
 * 提示消息
 *
 * @param $msg [string] 消息内容
 * @param $redirect [string] 跳转
 */
function showNotice( $msg , $redirect = '') {

	if( $redirect ) {

		$url = site_url($redirect);
		echo "<script>alert('".$msg."');location.href='".$url."'</script>";

	} else {
		echo "<script>alert('".$msg."')</script>";
	}
}






























