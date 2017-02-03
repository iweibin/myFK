<?php
namespace app\api\model;
use core\lib\model;

class M extends model {
	public $_table = '';

	public function __construct( $table ) {
		parent::__construct();
		$this->_table = $table;

	}

	public function lists() {

		$ret = $this->select($this->_table,"*");

		return $ret;

	}
}