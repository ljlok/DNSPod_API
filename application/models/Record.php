<?php
class Domain {


public function init_params(){
	$DOMAIN_LIST_API = "https://dnsapi.cn/Domain.List";
	$param = array(
			'login_email'=>'dnspod_api@126.com',
			'login_password'=>'114110',
			'format'=>'json'
	);
}

}