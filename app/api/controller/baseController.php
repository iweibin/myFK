<?php
namespace app\api\controller;
use core\controller;

class baseController extends controller {

	public function phone_verify( $phone_num ,$code ) {
		$api_url = 'https://webapi.sms.mob.com/sms/verify';
		$appkey = '1997c92a491d4';

		$response = postRequest( $api_url , array(
			'appkey' => $appkey,
		    'phone' => $phone_num,
		    'zone' => '86',
			'code' => $code
		) );
		// if($code == '1234')
		// 	$response = 200;
		// else 
		// 	$response = 468;
		return $response;			
	}

	public function jsonReturn($result_code ,$result_desc = '', $data = null) {
		
		header('Content-Type:application/json; charset=utf-8');

		exit(json_encode(array(
				'data' => $data ,
				'request_id' => time(),
				'result_code' => $result_code,
				'result_desc' => $result_desc,
				'timestamp'	=> date('Y-m-d H:i:s',time())
				)));
	}

	public function randnum() {
		$str = '';
		for( $i = 0; $i < 4; $i++ ) {
			$str .= rand(0,9);
		}
		return $str;
	}
}