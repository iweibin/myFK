<?php
namespace app\api\model;
use core\lib\model;

class tokenModel extends model {

	public $_table = 'token';

	public function __construct($table = '') {
		parent::__construct();
		if( $table )
			$this->_table = $table;
	}

	public function createToken( $uid ) {

		$time = time();

		$token = md5(date('YmdHis',$time).$uid);

		$data = array(
			'uid'	=> $uid,
			'tokenid' => $token,
			'create_time' => $time,
			'deadline'	=> $time + 7*24*60*60
			);
		if( $this->get($this->_table , "*",['uid'=>$uid]) ) {

			$this->update($this->_table ,$data ,['uid'=>$uid]);
		} else {

			$this->insert($this->_table ,$data);
		}
		
		return $token;
	}

	public function checkToken( $token ) {

		$ret = $this->get($this->_table ,"*" ,['tokenid'=>$token]);

		if( $ret['deadline'] > time() ) {

			return true;
		}
		return false;
	}

}