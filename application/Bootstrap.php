<?php

class Bootstrap extends Yaf_Bootstrap_Abstract{


	public function _init_API_function(){
		$base_dir = $_SERVER['DOCUMENT_ROOT'];
		$API_function = APP_PATH.'/application/functions/api.dnspod.function.php';
		Yaf_Loader::import($API_function);

	}
}