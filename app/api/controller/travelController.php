<?php
namespace app\api\controller;
use app\api\model\travelModel;
use app\api\model\tokenModel;
use app\api\controller\baseController;
use core\lib\upload;

class travelController extends baseController { 






	public function createTravel() {

		$data = $_POST;

		$M = new travelModel();

		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($data['token']) ) {
			unset($data['token']);

			if( $M->insertOne($data) ) {

				$this->jsonReturn('200','request success');
			} else {

				$this->jsonReturn('0','request failed');
			}
		} else {

			$this->jsonReturn('207','login timeout');
		}
	}


	public function travelList() {

		$token = $_POST['token'];
		$uid = $_POST['uid'];

		$M = new travelModel();

		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($token) ) {


			$list = $M->getTravelListByUid( $uid );
			for ($i=0; $i < count($list); $i++) { 
				$list[$i]['journeyCount'] = $M->getJourneyCount($list[$i]['travel_id']);
			}
			$this->jsonReturn('200','request success' ,$list);

		} else {

			$this->jsonReturn('207','login timeout');
		}

	}

	public function travelModify() {

		$data = $_POST;

		$M = new travelModel();

		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($data['token']) ) {

			$updateDate = array(
				'start_place' => $data['start_place'],
				'target_place' => $data['target_place'],
				'start_time' => $data['start_time'],
				'end_time' => $data['end_time'],
				'open_check' => $data['open_check']
				);
			if( $M->travelUpdate( $updateDate, $data['travel_id']) ) {

				$this->jsonReturn('200','request success');
			} else {

				$this->jsonReturn('0','request failed');
			}

		} else {

			$this->jsonReturn('207','login timeout');
		}

	}

	public function createJourney() {

		$data = $_POST;

		$M = new travelModel('journeys');

		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($data['token']) ) {
			unset($data['token']);

			if( $M->insertOne($data) ) {

				$this->jsonReturn('200','request success');
			} else {

				$this->jsonReturn('0','request failed');
			}
		} else {

			$this->jsonReturn('207','login timeout');
		}
	}

	public function journeyList() {

		$token = $_POST['token'];
		$uid = $_POST['uid'];
		$travel_id = $_POST['travel_id'];

		$M = new travelModel();

		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($token) ) {
			
			$journeyList = $M->getJourneyList($travel_id);

			$this->jsonReturn('200','request success' ,$journeyList ? $journeyList : null);
		} else {

			$this->jsonReturn('207','login timeout');
		}
	}

	public function journeyDetail() {

		$token = $_POST['token'];
		$journey_id = $_POST['journey_id'];

		$M = new travelModel('journeys');
		$tokenModel = new tokenModel();

		if( $tokenModel->checkToken($token) ) {

			$detail = $M->getJourneyDetail($journey_id);

			$this->jsonReturn('200','request success' ,$detail ? $detail : null);

		} else {

			$this->jsonReturn('207','login timeout');
		}

	}
}