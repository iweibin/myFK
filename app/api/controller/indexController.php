<?php
namespace app\api\controller;
use app\api\model\tableModel;
use app\api\controller\baseController;



class indexController extends baseController {

	public function index() {

		p('it is api mudole');
		dump(site_url());
		
	}
	public function test() {
		echo "test";
	}

	public function sendText() {
		// 配置项
		$api_url = 'https://webapi.sms.mob.com/sms/verify';
		$appkey = '1997c92a491d4';

		// 发送验证码
		$response = postRequest( $api_url , array(
			'appkey' => $appkey,
		    'phone' => '15659751525',
		    'zone' => '86',
			'code' => '4048',
		) );
		dump($response);
	}

}
