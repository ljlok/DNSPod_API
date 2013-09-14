<?php

    function init_params(){
   		
   		$conf_dir = APP_PATH.'/conf/application.ini';
   		$config = new Yaf_Config_Ini($conf_dir, 'product');
   		
   		$DOMAIN_LIST_API = $config->application->get('api')->domainList;
   		$email = $config->application->email;
   		$password = $config->application->password;
   		$filetype = $config->application->filetype;
   		
   		$post_param = array(
   			'login_email'=>$email,
   			'login_password'=>$password,
   			'format'=>$filetype
   		);
   		$params['api'] = $DOMAIN_LIST_API;
   		$params['post'] = $post_param;
   		return $params;
   }
   
    function get_API_info($API,$param){
   	
   		$query = http_build_query($param);
   		$ch = curl_init($API);
   		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
   		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
   		curl_setopt($ch,CURLOPT_POST,true);
   		curl_setopt($ch,CURLOPT_POSTFIELDS,$query);
   		$data = curl_exec($ch);
   		curl_close($ch);
   		
   		return json_decode($data);
   	
   }
   
    function process_API_info($API_info,$type){
   		$info_arr = array();
   		switch ($type){
   			case 'domain_list':
   				$info_arr['total'] = $API_info->info->domain_total;
   				foreach($API_info->domains as $key=>$item){
   					$info_arr['domains'][$item->id] = $item->name;
   				}
   				break;
   			case 'domain_add':
   				break;
   			case 'domain_update':
   				break;
   			default:
   				break;
   		}
   		return $info_arr;
   	
   }