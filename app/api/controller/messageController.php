<?php
namespace app\api\controller;
use app\api\model\messageModel;
use app\api\model\tokenModel;
use app\api\controller\baseController;

class messageController extends baseController {


	public function send() {

		$token = $_POST['token'];
		$uid = $_POST['uid'];
		$to_uid = $_POST['to_uid'];
		$text = $_POST['text'];

		$M = new messageModel();
		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($token) ) {

			$data = array(
				'uid' => $uid,
				'to_uid' => $to_uid,
				'text'	=> $text,
				'time'	=> date('Y-m-d H:i:s',time())
				);

			if( $mid = $M->insertOne($data)) {

				$sender = $M->get('users' ,['username' ,'nick_name' ,'sign' ,'avatar'] ,['uid'=>$data['uid'] ]);
				$sender['avatar'] = $sender['avatar'] ? SITE_URL.$sender['avatar'] : "";
					
				$data['mid'] = $mid;	
				$data['sender'] = $sender;

				$this->jsonReturn('200','request success' ,[$data]);
			} else {

				$this->jsonReturn('0','request failed');
			}

		} else {

			$this->jsonReturn('207','login timeout');
		}
	}

	public function messageList() {
		$token = $_POST['token'];
		$uid = $_POST['uid'];

		$M = new messageModel();
		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($token) ) {

			$messages = $M->getMessageList($uid); 
			$this->jsonReturn('200','request success',$messages);

		} else {

			$this->jsonReturn('207','login timeout');
		}

	}

	public function deleteMessage() {

		$token = $_POST['token'];
		$uid = $_POST['uid'];
		$mid = $_POST['mid'];

		$M = new messageModel();
		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($token) ) {

			if( $M->deleteMsg($mid) ) {

				$this->jsonReturn('200','request success');
			} else {

				$this->jsonReturn('0','request failed');
			}

		} else {

			$this->jsonReturn('207','login timeout');
		}
	}

	public function getTalkDetail() {
		$data = $_POST;

		$M = new messageModel();
		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($data['token']) ) {

			$talk = $M->getTalk($data['uid'] ,$data['to_uid'] ,$data['page'] ,$data['page_size']);

			$this->jsonReturn('200','request success' ,$talk);


		} else {

			$this->jsonReturn('207','login timeout');
		}
	}


	public function getNewMessage() {

		$token = $_POST['token'];
		$uid = $_POST['uid'];

		$fuid = isset($_POST['fuid']) ? $_POST['fuid'] : '';

		$M = new messageModel();
		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($token) ) {

			$newMessage = $M->newMessage($uid ,$fuid);

			if( $newMessage ) {

				$this->jsonReturn('208','new messages' ,$newMessage);
			} else {

				$this->jsonReturn('209','no new messages');
			}


		} else {

			$this->jsonReturn('207','login timeout');
		}

	}

}