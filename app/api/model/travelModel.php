<?php
namespace app\api\model;
use core\lib\model;

class travelModel extends model {

	public $_table = 'travels';

	public function __construct($table = '') {
		parent::__construct();
		if( $table )
			$this->_table = $table;
	}

	public function insertOne( $data ) {
		return $this->insert($this->_table ,$data);
	}

	public function getTravelListByUid( $uid ) {
		return $this->select($this->_table ,"*" ,['uid'=>$uid]);
	}
	public function travelUpdate( $data ,$travel_id ) {
		return $this->update($this->_table ,$data ,['travel_id'=>$travel_id]);
	}

	public function getJourneyCount( $id ) {
		$count =  $this->count('journeys' ,['travel_id'=>$id]);
		return $count ? "$count" : "0";
	}

	public function getJourneyList( $travel_id ) {
		return $this->select('journeys' ,"*" ,['travel_id'=>$travel_id]);
	}

	public function getJourneyDetail( $journey_id ) {
		$detail = $this->get($this->_table ,"*" ,['journey_id'=>$journey_id]);
		$detail['dynamic'] = $this->select("dynamics",['dynamic_id','dynamic_text','location','dynamic_picture','time'],['journey_id'=>$journey_id]);
		
		for ($i=0; $i < count($detail['dynamic']); $i++) { 
			$detail['dynamic'][$i]['dynamic_picture'] = $detail['dynamic'][$i]['dynamic_picture'] ? SITE_URL.$detail['dynamic'][$i]['dynamic_picture'] : ""; 
		}
		return $detail;
	}

}