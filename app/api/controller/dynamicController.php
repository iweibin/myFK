<?php
namespace app\api\controller;
use app\api\model\dynamicModel;
use app\api\model\tokenModel;
use app\api\controller\baseController;
use core\lib\upload;

class dynamicController extends baseController { 


	public function createDynamic() {

		$data = $_POST;

		$upload = new upload();

		if( isset($data['dynamic_picture']) && $info = $upload->doUpload() ) {

			$data['dynamic_picture'] = $info['dynamic_picture']['savePath'].$info['dynamic_picture']['saveName'];
		} else {

			$data['dynamic_picture'] = null;
		}

		$data['time'] = date('Y/m/d',time());

		$M = new dynamicModel();
		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($data['token']) ) {
			unset($data['token']);

			if( $M->insertOne($data)) {

				$this->jsonReturn('200','request success');
			} else {

				$this->jsonReturn('0','request failed');
			}

		} else {

			$this->jsonReturn('207','login timeout');
		}	
	}

	public function getDynamicList() {

		$data = $_POST;

		$M = new dynamicModel();
		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($data['token']) ) {
			
			if( $data['type'] == 1 ) {

				if( isset($data['keyword']) && $data['keyword']) {
					
					$result = $M->search( $data['uid'] ,$data['page'] ,$data['page_size'] ,$data['keyword']);
				} else {

					$result = $M->search($data['uid'],$data['page'] ,$data['page_size']);
				}
				
			} elseif( $data['type'] == 2 ) {

				$result = $M->getFriendsDynamic($data['uid'] ,$data['page'] ,$data['page_size']);	

			} elseif($data['type'] == 3  ) {

				$result = $M->getDynamicByUid($data['uid'] ,$data['page'] ,$data['page_size']);

			} elseif( $data['type'] == 4 ) {

				$result = $M->getDynamicByUid($data['uid'] ,$data['page'] ,$data['page_size'] ,$data['check_uid']);
			}

			$this->jsonReturn('200','request success' ,$result ? $result : null);
		} else {

			$this->jsonReturn('207','login timeout');
		}	


	}


	public function comment() {

		$token = $_POST['token'];
		$uid = $_POST['uid'];
		$dynamic_id = $_POST['dynamic_id'];
		$text = $_POST['text'];

		$data = array(
			'uid'	=> $uid,
			'dynamic_id' => $dynamic_id,
			'text'	=> $text,
			'time'	=> date('Y/m/d',time())
			);

		$M = new dynamicModel('comments');
		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($token) ) {


			if( $M->insertOne($data)) {

				$this->jsonReturn('200','request success');
			} else {

				$this->jsonReturn('0','request failed');
			}

		} else {

			$this->jsonReturn('207','login timeout');
		}

	}


	public function getCommentList() {

		$token = $_POST['token'];
		$dynamic_id = $_POST['dynamic_id'];

		$M = new dynamicModel('comments');
		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($token) ) {

			$list = $M->getCommentList($dynamic_id);

			$data = array(
				'comments' => $list,
				'likeds' => $M->count('likes',['dynamic_id'=>$dynamic_id])
				);

			$this->jsonReturn('200','request success',$data);


		} else {

			$this->jsonReturn('207','login timeout');
		}

	}



	public function liked() {

		$token = $_POST['token'];
		$uid = $_POST['uid'];
		$dynamic_id = $_POST['dynamic_id'];

		$M = new dynamicModel('likes');
		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($token) ) {
			
			// 取消点赞
			if( $M->get('likes',"*",['AND'=>['uid'=>$uid],'dynamic_id'=>$dynamic_id]) ) {

				if( $M->delete('likes',['AND'=>['uid'=>$uid],'dynamic_id'=>$dynamic_id]) ) {

					$this->jsonReturn('200','has canceled the point like');
				} else {

					$this->jsonReturn('0','request failed');
				}

			} else {

				$data = array(
					'uid'	=> $uid,
					'dynamic_id' => $dynamic_id
					);
				
				if( $M->insertOne($data)) {

					$this->jsonReturn('200','request success');
				} else {

					$this->jsonReturn('0','request failed');
				}
			}

		} else {

			$this->jsonReturn('207','login timeout');
		}

	}
}