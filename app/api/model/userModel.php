<?php
namespace app\api\model;
use core\lib\model;

class userModel extends model {

	public $_table = 'users';

	public function __construct($table = '') {
		parent::__construct();
		if( $table )
			$this->_table = $table;
	}

	public function is_exists( $username ) {
		return $this->get($this->_table,'*',['username'=>$username]);
	}

	public function data_insert( $data ) {
		return $this->insert($this->_table ,$data);
	}

	public function loginInfo( $username ) {
		return $this->get($this->_table ,"*" ,['username'=>$username]);
	}

	public function getInfo($uid ,$feild = "*") {
		$info =  $this->get($this->_table, $feild,['uid'=>$uid]);
		if( $info ) {

			$info['avatar'] = $info['avatar'] ? SITE_URL.$info['avatar'] : "";
		}
		return $info;
	}
	public function updateInfo($uid,$data) {
		return $this->update($this->_table,$data,['uid'=>$uid]);
	}

	public function setLoginTime( $username ,$data) {
		return $this->update($this->_table ,$data ,['username'=>$username]);
	}


	public function friendsList( $uid ) {
		$ret = $this->select($this->_table ,"*" ,['AND'=>['uid'=>$uid,'status'=>1]]);
		$fuid = array();
		foreach ($ret as $key => $val) {
			$fuid[] = $val['fuid'];
		}

		$list = $this->select('users' ,['uid','username','nick_name','avatar','sign','gender'] ,['uid'=>$fuid]);

		for ($i=0; $i < count($list); $i++) { 
			$list[$i]['avatar'] = $list[$i]['avatar'] ? SITE_URL.$list[$i]['avatar'] : "";
		}

		return $list ? $list : null;	
	}


	public function searchFriednsList($uid ,$keyword ) {

		$list = $this->select($this->_table ,[
				'[>]users' => [
					'fuid'=>'uid'
					]
				],['users.uid','users.username','users.nick_name','users.avatar','users.sign','users.gender'],[
					'AND' => [
						'friends.uid'=>$uid,
						'OR' => [
							'username' => $keyword,
							'nick_name[~]' => $keyword
						]
					]
				]);
		
		for ($i=0; $i < count($list); $i++) { 
			$list[$i]['avatar'] = $list[$i]['avatar'] ? SITE_URL.$list[$i]['avatar'] : "";
		}

		return $list ? $list : null;	
	}



	public function addFriend( $uid ,$fuid) {

		if( $this->get($this->_table ,"*" ,['AND'=>['uid'=>$uid ,'fuid'=>$fuid]]) ) {
			return false;
		}

		$dateline = date('Y/m/d',time());

		if( $this->insert($this->_table ,['uid'=>$fuid,'fuid'=>$uid,'dateline'=>$dateline ,'status'=>1]) && $this->insert($this->_table ,['uid'=>$uid,'fuid'=>$fuid,'dateline'=>$dateline ,'status'=>1]) ) {
			return true;
		}
		return false;

	}

	public function isFriend( $uid ,$token ) {
		$user = $this->get('token',"*" ,['tokenid'=>$token]);

		return $this->select('friends' ,"*" ,[
			'OR #Actually, this comment feature can be used on every AND and OR relativity condition' => [
				'AND #the first condition' => [
					'uid' => $uid,
					'fuid' => $user['uid']
					],
				'AND #the second condition' => [
					'uid' => $user['uid'],
					'fuid' => $uid
					]
				]
			]);
	}

	public function requestList( $uid ) {
		$list = $this->select($this->_table ,"*" ,['AND'=>['fuid'=>$uid,'status'=>0]]);

		for ($i=0; $i < count($list); $i++) { 

			$list[$i]['applicant'] = $this->get('users' ,['username','nick_name','avatar','sign','gender'] ,['uid'=>$list[$i]['uid']]);

			$list[$i]['applicant']['avatar'] = $list[$i]['applicant']['avatar'] ? SITE_URL.$list[$i]['applicant']['avatar'] : "";
		}
		return $list ? $list : null;
	}

	public function agree( $uid ,$fid ) {
		return $this->update($this->_table ,['status'=>1] ,['AND'=>['fid'=>$fid ,'fuid'=>$uid]]);
	}


	public function getNearbyList( $uid ) {
		$user = $this->get($this->_table ,['province', 'city', 'location'],['uid'=>$uid]);


		$nearbyList = $this->select($this->_table ,['uid','username','nick_name','avatar','sign','gender','province', 'city', 'location'],[
				'AND' => [
					'OR' => [
						'location[~]' => $user['location'],
						'city'	=> $user['city'],
						'province'	=> $user['province'] 
					],
					'uid[!]' => $uid
				]
			]);

		for ($i=0; $i < count($nearbyList); $i++) { 
			$nearbyList[$i]['avatar'] = $nearbyList[$i]['avatar'] ? SITE_URL.$nearbyList[$i]['avatar'] : "";
		}
		return $nearbyList ? $nearbyList : null;
	}
}
