<?php
namespace app\index\model;
use core\lib\model;

class tableModel extends model
{
	public $table = 'a';
	
	public function lists() {

		$ret = $this->select($this->table,"*");

		return $ret;

	}
}