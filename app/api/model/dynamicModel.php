<?php
namespace app\api\model;
use core\lib\model;

class dynamicModel extends model {

	public $_table = 'dynamics';

	public function __construct($table = '') {
		parent::__construct();
		if( $table )
			$this->_table = $table;
	}

	public function getListById( $journey_id ) {
		return $this->select($this->_table ,"*" ,['journey_id'=>$journey_id]);
	}

	public function insertOne( $data ) {
		return $this->insert($this->_table ,$data);
	}


	public function getCommentList( $dynamic_id) { 
		$comment = $this->select($this->_table ,"*" ,['dynamic_id'=>$dynamic_id]);

		for ($i=0; $i < count($comment); $i++) { 
			
			$commentator = $this->get('users' ,['uid','nick_name','avatar'] ,['uid'=>$comment[$i]['uid']]);

			$comment[$i]['nick_name'] = $commentator['nick_name']; 
			$comment[$i]['avatar'] = $commentator['avatar'] ? SITE_URL.$commentator['avatar'] : "";
		}

		return $comment;
	}


	public function search( $uid, $page ,$page_size ,$keyword = false ) {
		$offset = ($page == 1) ? 0 : ($page-1)*$page_size;
		$result = array();

		if( $keyword ) {
			$result =  $this->select($this->_table ,"*" ,[
					'OR' => [
						'dynamic_text[~]' => $keyword,
						'location[~]'	=> $keyword	
					],
					'LIMIT' => [$offset,$page_size]
				]);
		} else {

			$result = $this->select($this->_table ,"*" ,[
					'ORDER' => 'time DESC',
					'LIMIT' => [$offset ,$page_size]
				]);
		}


		for ($i=0; $i < count($result); $i++) {

			$result[$i]['dynamic_picture'] = $result[$i]['dynamic_picture'] ? SITE_URL.$result[$i]['dynamic_picture'] : "";

			$creator = $this->get('users' ,['uid','nick_name','avatar'] ,[ 'uid'=>$result[$i]['uid'] ]);

			$result[$i]['nick_name'] = $creator['nick_name']; 
			$result[$i]['avatar'] = $creator['avatar'] ? SITE_URL.$creator['avatar'] : "";


			$result[$i]['likeds'] = $this->count('likes' ,"*" ,['dynamic_id'=>$result[$i]['dynamic_id']]);

			$result[$i]['is_liked'] = $this->get('likes' ,"*" ,['AND'=>['uid'=>$uid,'dynamic_id'=>$result[$i]['dynamic_id']]]) ? 1 : 0;
		}



		return $result ? $result : false;
	}

	public function getDynamicByUid( $uid ,$page ,$page_size ,$check_uid = '') {
		$offset = ($page == 1) ? 0 : ($page-1)*$page_size;
		$result = array();

		if( $check_uid ) {
			$result = $this->select($this->_table ,"*", [
					'uid' => $check_uid,
					'ORDER' => 'time DESC',
					'LIMIT' => [$offset ,$page_size]
				]);
		} else {
			$result = $this->select($this->_table ,"*", [
					'uid' => $uid,
					'ORDER' => 'time DESC',
					'LIMIT' => [$offset ,$page_size]
				]);
		}

		for ($i=0; $i < count($result); $i++) { 
			$result[$i]['dynamic_picture'] = $result[$i]['dynamic_picture'] ? SITE_URL.$result[$i]['dynamic_picture'] : "";
			$creator = $this->get('users' ,['uid','nick_name','avatar'] ,['uid'=>$result[$i]['uid']]);

			$result[$i]['nick_name'] = $creator['nick_name']; 
			$result[$i]['avatar'] = $creator['avatar'] ? SITE_URL.$creator['avatar'] : "";

			$result[$i]['likeds'] = $this->count('likes' ,"*" ,['dynamic_id'=>$result[$i]['dynamic_id']]);

			$result[$i]['is_liked'] = $this->get('likes' ,"*" ,['AND'=>['uid'=>$uid,'dynamic_id'=>$result[$i]['dynamic_id']]]) ? 1 : 0;
		}
		return $result ? $result : false;
	}

	public function getFriendsDynamic( $uid ,$page ,$page_size ) {
		$offset = ($page == 1) ? 0 : ($page-1)*$page_size;
		$result = array();
		
		$ret = $this->select('friends' ,['fuid'] ,['AND'=>['uid'=>$uid,'status'=>1] ]);

		$friends = array();
		for ($i=0; $i < count($ret); $i++) { 
			$friends[] = $ret[$i]['fuid'];
		}

		if( $friends ) {
			$result = $this->select($this->_table ,"*", [
					'uid' => $friends,
					'ORDER' => 'time DESC',
					'LIMIT' => [$offset ,$page_size]
				]);
		}


		for ($i=0; $i < count($result); $i++) { 
			$result[$i]['dynamic_picture'] = $result[$i]['dynamic_picture'] ? SITE_URL.$result[$i]['dynamic_picture'] : "";
			$creator = $this->get('users' ,['uid','nick_name','avatar'] ,['uid'=>$result[$i]['uid']]);

			$result[$i]['nick_name'] = $creator['nick_name']; 

			$result[$i]['avatar'] = $creator['avatar'] ? SITE_URL.$creator['avatar'] : "";

			$result[$i]['likeds'] = $this->count('likes' ,"*" ,['dynamic_id'=>$result[$i]['dynamic_id']]);

			$result[$i]['is_liked'] = $this->get('likes' ,"*" ,['AND'=>['uid'=>$uid,'dynamic_id'=>$result[$i]['dynamic_id']]]) ? 1 : 0;
		}
		return $result ? $result : false ;
	}
}