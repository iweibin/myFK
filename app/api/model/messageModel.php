<?php
namespace app\api\model;
use core\lib\model;

class messageModel extends model {

	public $_table = 'messages';

	public function __construct($table = '') {
		parent::__construct();
		if( $table )
			$this->_table = $table;
	}

	public function insertOne( $data) {
		return $this->insert($this->_table ,$data);
	}

	public function getMessageList( $uid ) {

		$list = $this->select($this->_table , ['mid', 'uid', 'text', 'time'],['to_uid'=>$uid, 'ORDER'=>'time DESC']);

		for ($i=0; $i < count($list); $i++) { 
			$sender = $this->get('users' ,['username' ,'nick_name' ,'sign' ,'avatar'] ,['uid'=>$list[$i]['uid'] ]);
			$sender['avatar'] = $sender['avatar'] ? SITE_URL.$sender['avatar'] : "";

			$list[$i]['sender'] = $sender;
		}
		return $list ? $list : null;
	}

	public function deleteMsg( $mid ) {
		return $this->delete($this->_table ,['mid'=>$mid]);
	}


	public function getTalk($uid ,$to_uid ,$page ,$page_size) {
		$offset = ($page == 1) ? 0 : ($page-1)*$page_size;

		$list = $this->select($this->_table ,"*" , [
				'OR #Actually, this comment feature can be used on every AND and OR relativity condition' => [
					'AND #the first condition' => [
						'uid' => $uid,
						'to_uid' => $to_uid
						],
					'AND #the second condition' => [
						'uid' => $to_uid,
						'to_uid' => $uid
						]
					],
				'ORDER' => 'time DESC',
				'LIMIT' => [$offset ,$page_size]
			
			]);

		if( $list ) {

			$this->update($this->_table ,['is_read'=>1] ,[
				'AND' => [
					'uid' => $to_uid,
					'to_uid' => $uid
					]
				]);
		}

		for ($i=0; $i < count($list); $i++) { 

			if( $list[$i]['uid'] != $uid ) {

				$list[$i]['is_to_me'] = 1;
			} else {

				$list[$i]['is_to_me'] = 0;
			}

			$sender = $this->get('users' ,['username' ,'nick_name' ,'sign' ,'avatar'] ,['uid'=>$list[$i]['uid'] ]);
			$sender['avatar'] = $sender['avatar'] ? SITE_URL.$sender['avatar'] : "";
			
			$list[$i]['sender'] = $sender;
		}
		return $list ? $list : null;
	}



	public function newMessage( $uid ,$fuid = '') {

		if( $fuid ) {

			$new =  $this->select($this->_table ,['mid', 'uid','to_uid' ,'text', 'time'] ,['AND'=>['to_uid'=>$uid,'uid'=>$fuid ,'is_read'=>0]]);
		} else {
			
			$new =  $this->select($this->_table ,['mid', 'uid','to_uid' ,'text', 'time'] ,['AND'=>['to_uid'=>$uid,'is_read'=>0]]);
		}

		for ($i=0; $i < count($new); $i++) { 

			$sender = $this->get('users' ,['username' ,'nick_name' ,'sign' ,'avatar'] ,['uid'=>$new[$i]['uid'] ]);
			$sender['avatar'] = $sender['avatar'] ? SITE_URL.$sender['avatar'] : "";
			
			$new[$i]['is_to_me'] = 1;
			$new[$i]['sender'] = $sender;


		}

		if( $new ) {

			for ($i=0; $i < count($new); $i++) { 
				
				$this->update($this->_table ,['is_read'=>1] ,['mid'=>$new[$i]['mid']]);
			}
				
		}

		return $new ? $new : null;
	}
}
