<?php 
namespace app\api\controller;
use app\api\model\userModel;
use app\api\model\tokenModel;
use app\api\controller\baseController;
use core\lib\upload;

class userController extends baseController {

	public function signin() {

		$username = $_POST['username'];
		$password = $_POST['password'];

		$M = new userModel('users');
		$token = new tokenModel();

		$user = $M->loginInfo($username);

		if( !empty($user) ) {

			if( md5($password) == $user['password'] ) {

				$user['last_logined'] = time();
				unset($user['password']);

				$M->setLoginTime($username,['last_logined'=>$user['last_logined']]);

				$user['token'] = $token->createToken($user['uid']);

				$user['avatar'] = $user['avatar'] ? SITE_URL.$user['avatar'] : '';
				
				$this->jsonReturn('200','login successful',$user);
			} else {
				
				$this->jsonReturn('204','passwordError');
			}

		} else {

			$this->jsonReturn('205','user not exists');
		}

	}

	public function signup() {

		$phone_num = $_POST['phone_num'];
		$password = $_POST['password'];

		$M = new userModel('users');

		if( !$M->is_exists($phone_num) ) {

			$data['username'] = $phone_num;
			$data['nick_name'] = "用户_".$this->randnum();
			$data['phone_num'] = $phone_num;
			$data['password'] = md5($password);
			$data['rg_time'] = time();

			if( $M->data_insert($data) ) {

				$this->jsonReturn('200','signup successful');
			} else {
				
				$this->jsonReturn('0','request failed');
			}

		} else {

			$this->jsonReturn('202','user already exists');
		}
		
	}


	public function avatarUpload() {

		$token = $_POST['token'];
		$uid = $_POST['uid'];

		$M = new userModel('users');

		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($token) ) {

			$upload = new upload();
			$info = $upload->doUpload();

			if( $info ) {
				$avatar = $info['avatar']['savePath'].$info['avatar']['saveName'];

				if($M->updateInfo($uid ,['avatar'=>$avatar]) ) {

					$this->jsonReturn('200','request successful');
					
				} else {

					$this->jsonReturn('0','request failed');
				}
			} else {

				$this->jsonReturn('0','not uploaded file');
			}

		} else {

			$this->jsonReturn('207','login timeout');
		}


		

	}


	public function passwordModify() {

		$token = $_POST['token'];
		$uid = $_POST['uid'];
		$oldpassword = $_POST['oldpassword'];
		$newpassword = $_POST['newpassword'];

		$M = new userModel('users');
		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($token) ) {

			$profile = $M->getInfo($uid);

			if( $profile['password'] == md5($oldpassword) ) {

				if( $M->updateInfo( $uid ,['password'=>md5($newpassword)] ) ) {

					$this->jsonReturn('200','request successful');
				} else {

					$this->jsonReturn('0' ,'request failed');
				}
			} else {

				$this->jsonReturn('204' ,'passwordError');
			}

		} else {

			$this->jsonReturn('207','login timeout');
		}

	}



	public function passwordReset() {

		$username = $_POST['username'];
		$newpassword = $_POST['newpassword'];

		$M = new userModel('users');
		$profile = $M->getInfo($username);

		if( $M->updateInfo( $username ,['password'=>md5($newpassword)] ) ) {

			$this->jsonReturn('200','request successful');
		} else {

			$this->jsonReturn('0' ,'request failed');
		}

	}


	public function getProfile() {

		$token = $_POST['token'];
		$uid = $_POST['uid'];

		$M = new userModel('users');
		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($token) ) {


			$profile = $M->getInfo($uid);
			
			if( $profile ) {
				unset($profile['password']);

				$profile['is_friend'] = $M->isFriend($uid ,$token) ? 1 : 0;

				$this->jsonReturn('200' ,'request successful',$profile);
			} else {

				$this->jsonReturn('0','request failed');
			}

		} else {

			$this->jsonReturn('207','login timeout');
		}
	}


	public function profileModify() {

		$token = $_POST['token'];
		$uid = $_POST['uid'];
		$name = $_POST['name'];
		$nick_name = $_POST['nick_name'];
		$sign = $_POST['sign'];
		$gender = $_POST['gender'];
		$email = $_POST['email'];
		$birthday = $_POST['birthday'];
		$location = $_POST['location'];

		$data = array(
			'name' => $name, 
			'nick_name' => $nick_name, 
			'sign' => $sign, 
			'gender' => $gender,
			'email' => $email, 
			'birthday' => $birthday, 
			'location' => $location 
			);

		$M = new userModel('users');
		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($token) ) {
			
			if( $M->updateInfo($uid ,$data) ) {

				$this->jsonReturn('200' ,'request successful');
			} else {

				$this->jsonReturn('0','request failed');
			}

		} else {

			$this->jsonReturn('207','login timeout');
		}
	}


	public function getFriendsList() {
		
		$token = $_POST['token'];
		$uid = $_POST['uid'];

		$tokenModel = new tokenModel();
		$M = new userModel('friends');

		if( $tokenModel->checkToken($token) ) {

			$friends = $M->friendsList($uid);

			$this->jsonReturn('200','request success',$friends);

		} else {

			$this->jsonReturn('207','login timeout');
		}
	}

	public function searchFriends() {

		$token = $_POST['token'];
		$uid = $_POST['uid'];
		$keyword = $_POST['keyword'];

		$tokenModel = new tokenModel();
		$M = new userModel('friends');

		if( $tokenModel->checkToken($token) ) {

			$friends = $M->searchFriednsList($uid ,$keyword);

			$this->jsonReturn('200','request success',$friends ? $friends : null);

		} else {

			$this->jsonReturn('207','login timeout');
		}

	}

	public function getFriendRequestList() {

		$token = $_POST['token'];
		$uid = $_POST['uid'];


		$tokenModel = new tokenModel();
		$M = new userModel('friends');

		if( $tokenModel->checkToken($token) ) {

			$list = $M->requestList($uid);

			$this->jsonReturn('200','request success' ,$list);

		} else {

			$this->jsonReturn('207','login timeout');
		}

	}

	public function agree() {

		$token = $_POST['token'];
		$uid = $_POST['uid'];
		$fid = $_POST['fid'];


		$tokenModel = new tokenModel();
		$M = new userModel('friends');

		if( $tokenModel->checkToken($token) ) {

			if( $friends = $M->agree($uid ,$fid) ) {

				$this->jsonReturn('200','request success');	
			} else {

				$this->jsonReturn('0','request failed');	
			}

		} else {

			$this->jsonReturn('207','login timeout');
		}
	}



	public function addFriend() {

		$token = $_POST['token'];
		$uid = $_POST['uid'];
		$fuid = $_POST['fuid'];

		$tokenModel = new tokenModel();
		$M = new userModel('friends');

		if( $tokenModel->checkToken($token) ) {

			if( $M->addFriend($uid ,$fuid) ) {

				$this->jsonReturn('200','request success');	
			} else {

				$this->jsonReturn('0','request failed');	
			}

		} else {

			$this->jsonReturn('207','login timeout');
		}

	}



	public function nearby() {

		$token = $_POST['token'];
		$uid = $_POST['uid'];

		$tokenModel = new tokenModel();
		$M = new userModel('users');

		if( $tokenModel->checkToken($token) ) {

			$list = $M->getNearbyList($uid);

			$this->jsonReturn('200','request success' ,$list);	

		} else {

			$this->jsonReturn('207','login timeout');
		}


	}

}
