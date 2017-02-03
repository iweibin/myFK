<?php
namespace core\lib;
use core\lib\conf;

class model extends \medoo
{
	public function __construct() {

		$option = conf::all('database');
		
		parent::__construct($option);

		/* \PDO */
		/*$dsn = conf::get('DSN','database');
		$username = conf::get('USERNAME','database');
		$password = conf::get('PASSWORD','database');*/
		// $database = array_change_key_case( conf::all('database') );
		// $dsn = 'mysql:host='.$database['hostname'].';dbname='.$database['database'];
		// try {
		// 	parent::__construct($dsn,$database['username'],$database['password']);
		// } catch( \PDOException $e) {
		// 	p($e->getMessage());
		// }
	}


}
