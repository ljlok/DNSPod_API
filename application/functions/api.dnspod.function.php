<?php

    function init_params($type,$param=null){
   		
   		$conf_dir = APP_PATH.'/conf/application.ini';
   		$config = new Yaf_Config_Ini($conf_dir, 'product');
   		
   		$email = $config->application->email;
   		$password = $config->application->password;
   		$filetype = $config->application->filetype;
   		$post_param = array(
                  'login_email'=>$email,
                  'login_password'=>$password,
                  'format'=>$filetype
                  );
         switch ($type) {
            case 'domain_list':
               $params['api'] = $config->application->get('api')->domainList;
               break;
            case 'domain_add':
               $post_param['domain'] = $param;
               $params['api'] = $config->application->get('api')->domainCreate;
               break;
            case 'domain_remove':
               $post_param['domain_id'] = $param;
               $params['api'] = $config->application->get('api')->domainRemove;
               break;
            case 'domain_update':
               $post_param['domain_id'] = $param['domain_id'];
               $post_param['status'] = $param['status'];
               $params['api'] = $config->application->get('api')->domainStatus;
               break;
            /*case 'domain_detail':
               $post_param['domain_id'] = $param;
               $params['api'] = $config->application->get('api')->domainDetail;
               break;*/
            case 'record_list':
               $post_param['domain_id'] = $param;
               $params['api'] = $config->application->get('api')->recordList;
               break;
            case 'record_remove':
               $post_param['domain_id'] = $param['domain_id'];
               $post_param['record_id'] = $param['record_id'];
               $params['api'] = $config->application->get('api')->recordRemove;
               break;
            case 'record_add':
               $post_param['domain_id'] = $param['domain_id'];
               $post_param['sub_domain'] = $param['sub_domain'];
               $post_param['record_type'] = $param['record_type'];
               $post_param['record_line'] = $param['record_line'];
               $post_param['value'] = $param['value'];

               $params['api'] = $config->application->get('api')->recordCreate;
               # code...
               break;
            case 'record_update':
               $post_param['domain_id'] = $param['domain_id'];
               $post_param['record_id'] = $param['record_id'];
               $post_param['status'] = $param['status'];
               $params['api'] = $config->application->get('api')->recordUpdate;
               break;
            case 'record_modify':
               $post_param['domain_id'] = $param['domain_id'];
               $post_param['record_id'] = $param['record_id'];
               $post_param['sub_domain'] = $param['sub_domain'];
               $post_param['record_type'] = $param['record_type'];
               $post_param['record_line'] = $param['record_line'];
               $post_param['value'] = $param['value'];
               $post_param['ttl'] = $param['ttl'];
               $post_param['mx'] = $param['mx'];

               $params['api'] = $config->application->get('api')->recordModify;  
               # code...
               break;
            default:
               # code...
               break;
         }
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
   				//foreach($API_info->domains as $key=>$item){
   				//	$info_arr['domains'][$item->id] = $item->name;
   				//}
               $info_arr['domains'] = $API_info->domains;
   				break;
   			case 'domain_add':
               $info_arr['status'] = $API_info->status;
               $info_arr['domain'] = $API_info->domain;
   				break;
            case 'domain_remove':
               $info_arr['status'] = $API_info->status;
   			case 'domain_update':
               $info_arr['status'] = $API_info->status;
           /* case 'domain_detail':
               $info_arr['status'] = $API_info->status;
               $info_arr['domain'] = $API_info->domain;
   				break;*/
            case 'record_list':
               $info_arr['status'] = $API_info->status;
               $info_arr['domain'] = $API_info->domain;
               $info_arr['records'] = $API_info->records;
               break;
            case 'record_remove':
               $info_arr['status'] = $API_info->status;
               break;
            case 'record_add':
               $info_arr['status'] = $API_info->status;
               # code...
               break;
            case 'record_update':
               $info_arr['status'] = $API_info->status;
               $info_arr['record'] = $API_info->record;
               break;
            case 'record_modify':
               $info_arr['status'] = $API_info->status;
               $info_arr['record'] = $API_info->record;
               # code...
               break;
   			default:
   				break;
   		}
   		return $info_arr;
   	
   }