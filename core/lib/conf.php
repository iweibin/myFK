<?php
namespace core\lib;
class conf
{
	static public $conf = array();

	/**
	 * 获取单个配置
	 * 
	 * @param [string] $name 配置名
	 * @param [string] $file 配置文件名
	 * @return string
	 */
	static public function get($name ,$file) {
		/**
		 * 1 判断配置文件是否存在
		 * 2 判断配置是否存在
		 * 3 缓存配置
		 */

		if( isset(self::$conf[$file]) ) {

			return self::$conf[$file][$name];

		} else {

			$path = BASE_PATH.'/'.APP_NAME.'/'.MODULE.'/conf/'.$file.'.php';

			if( is_file($path) ) {

				$conf = include $path;

				if( empty($conf) ) {

					$path = BASE_PATH.'/core/conf/'.$file.'.php';

					if( is_file($path) ) {

						$conf = include $path;

						if( isset($conf[$name]) ) {

							self::$conf[$file] = $conf;

							return $conf[$name];

						} else {

							throw new \Exception("没有这个配置项：".$name, 1);

						}

					} else {

						throw new \Exception("找不到配置文件:".$file, 1);
						
					}

				}

				self::$conf[$file] = $conf;

				return $conf[$name];

			}

		}
	}

	/**
	 * 加载所有配置
	 *
	 * @param [string] $file 配置文件名
	 * @return string
	 */
	static public function all($file) {

		if( isset(self::$conf[$file]) ) {

			return self::$conf[$file];

		} else {

			$path = BASE_PATH.'/'.APP_NAME.'/'.MODULE.'/conf/'.$file.'.php';

			if( is_file($path) ) {

				$conf = include $path;
				
				if( empty($conf) ) {

					$path = BASE_PATH.'/core/conf/'.$file.'.php';

					if( is_file($path) ) {

						$conf = include $path;

						self::$conf[$file] = $conf;

						return $conf;

					} else {

						throw new \Exception("找不到配置文件:".$file, 1);
						
					}

				}

				self::$conf[$file] = $conf;

				return $conf;

			}

			
		}
	}
}