<?php
namespace app\index\controller;
use app\index\model\tableModel;
use core\controller;


class indexController extends controller 
{
	public function index() {

		p('it is index action');
	
	}

	public function view() {
	
		$this->assign('data','mnfbsnmgb');

		$this->display('index/index');
	}

	public function log() {

		\core\lib\log::log("ahsjkfhakjh");

	}
	public function conf() {
		$temp[] = \core\lib\conf::get('controller','route');
		$temp[] = \core\lib\conf::get('action','route');
		dump($temp);
	}
}