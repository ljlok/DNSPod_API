<?php

class RecordController extends Yaf_Controller_Abstract {

   public function indexAction() {//默认Action
   		$domain_id = htmlspecialchars( $_REQUEST['domain_id'] );
   		$params = init_params('record_list',$domain_id);
       	$arr = process_API_info(
       					get_API_info($params['api'],$params['post']),
       					'record_list'
       				 );
       	$this->getView()->assign("record_list",$arr);

	}

	public function addAction(){//add domain action
		 
		 //$request = $this->getRequest();
		 $record['domain_id'] = htmlspecialchars( $_REQUEST['domain_id'] );
		 $record['sub_domain'] = htmlspecialchars( $_REQUEST['record_name'] );
		 $record['value'] = htmlspecialchars( $_REQUEST['record_value'] );
		 $record['record_type'] = htmlspecialchars( $_REQUEST['record_type'] );
		 $record['record_line'] = htmlspecialchars( $_REQUEST['record_line'] );
		 //$record['mx'] = htmlspecialchars( $_REQUEST['record_mx'] );
		 //$record['ttl'] = htmlspecialchars( $_REQUEST['record_ttl'] );

		 $params = init_params('record_add',$record);
       	 $arr = process_API_info(
       				get_API_info($params['api'],$params['post']),
       				'record_add'
       			);
       	if($arr['status']->code==1){
       		$this->redirect("/DNSPod/public/Record?domain_id=".$record['domain_id']);
       	}else{
       		$this->getView()->assign("err_msg",$arr['status']->message);
       	}
		 	  
	}


	public function removeAction(){//remove domain action
		$post_param['domain_id'] = htmlspecialchars( $_REQUEST['domain_id'] );
		$post_param['record_id'] = htmlspecialchars( $_REQUEST['record_id'] );
		$params = init_params('record_remove',$post_param);
       	$arr = process_API_info(
       					get_API_info($params['api'],$params['post']),
       					'record_remove'
       				 );
       	if($arr['status']->code==1){
       		$this->redirect("/DNSPod/public/Record?domain_id=".$post_param['domain_id']);
       		//$this->indexAction();
       	}else{
       		$this->getView()->assign("err_msg",$arr['status']->message);
       	}

	}

	public function updateAction(){//update status of domain action
		$post_param['domain_id'] = htmlspecialchars( $_REQUEST['domain_id'] );
		$post_param['record_id'] = htmlspecialchars( $_REQUEST['record_id'] );
		$_REQUEST['status']=='enable'?$post_param['status']='enable':$post_param['status']='disable';
		$params = init_params('record_update',$post_param);
		$arr = process_API_info(
       					get_API_info($params['api'],$params['post']),
       					'record_update'
       				 );
       	if($arr['status']->code==1){
       		$this->redirect("/DNSPod/public/Record?domain_id=".$post_param['domain_id']);
       	}else{
       		$this->getView()->assign("err_msg",$arr['status']->message);
       	}

	}

	public function modifyAction(){
		 $record['domain_id'] = htmlspecialchars( $_REQUEST['domain_id'] );
		 $record['record_id'] = htmlspecialchars( $_REQUEST['record_id'] );
		 $record['sub_domain'] = htmlspecialchars( $_REQUEST['record_name'] );
		 $record['value'] = htmlspecialchars( $_REQUEST['record_value'] );
		 $record['record_type'] = htmlspecialchars( $_REQUEST['record_type'] );
		 $record['record_line'] = htmlspecialchars( $_REQUEST['record_line'] );
		 $record['mx'] = htmlspecialchars( $_REQUEST['record_mx'] );
		 $record['ttl'] = htmlspecialchars( $_REQUEST['record_ttl'] );

		$params = init_params('record_modify',$record);
		$arr = process_API_info(
       					get_API_info($params['api'],$params['post']),
       					'record_modify'
       				 );
       	if($arr['status']->code==1){
       		$this->redirect("/DNSPod/public/Record?domain_id=".$record['domain_id']);
       	}else{
       		$this->getView()->assign("err_msg",$arr['status']->message);

       	}

	}

	public function importAction(){
		$upload_dir = APP_PATH."/uploadfile/";          
  		$_FILES['import_record']['name'] = "records";
  
  		$uploadfile = $upload_dir.basename($_FILES['import_record']['name']);
  		if(move_uploaded_file($_FILES['import_record']['tmp_name'],$uploadfile)){
  			$file_info = file($uploadfile);
  			$domain_id = explode("=", $_SERVER["HTTP_REFERER"]);
  			$record = array();
  			$domain_id[1]?$record['domain_id'] = $domain_id[1]:$this->getView()->assign("err_msg","error1!");
  			
  			
  			foreach ($file_info as $key => $item) {
  				$item_arr = explode(",", $item);
  			
		 		$record['sub_domain'] =  $item_arr[0];
		 		$record['record_type'] =  $item_arr[1];
		 		$record['record_line'] =  $item_arr[2];
		 		$record['value'] = $item_arr[3];
		 		$record['mx'] = $item_arr[4];
		 		$record['ttl'] = $item_arr[5];

  				$params = init_params('record_add',$record);
       	 		$arr = process_API_info(
       						get_API_info($params['api'],$params['post']),
       						'record_add'
       					);
       	 		if($arr['status']->code!=1){
  					$this->getView()->assign("err_msg","line".($key+1)."->".$value." ".$arr['status']->message);
  					$flag+=1;
  				}
  			}
  			if($flag==0){
  				$this->redirect("/DNSPod/public/Record?domain_id=".$record['domain_id']);	
  			}

		}else{
			$this->getView()->assign("err_msg","import file error!");
		}

	}

	public function exportAction(){
		Yaf_Dispatcher::getInstance()->disableView();
		$domain_id = explode("=", $_SERVER["HTTP_REFERER"]);
  			
  		$domain_id[1]?$record['domain_id'] = $domain_id[1]:$this->getView()->assign("err_msg","error1!");

		$domain_id = $domain_id[1];
   		$params = init_params('record_list',$domain_id);
       	$arr = process_API_info(
       					get_API_info($params['api'],$params['post']),
       					'record_list'
       				 );

		$file = APP_PATH."/downloadfile/records";
       	$handle = fopen($file , "w");
       	 foreach ($arr['records'] as $key => $item) {
       	 	fwrite($handle, $item->name.",".$item->type.",".$item->line.",".$item->value.",".$item->mx.",".$item->ttl."\r\n");
       	 }
       	 fclose($handle);

        header("Content-type: application/octet-stream");

       	$ua = $_SERVER['HTTP_USER_AGENT'];
        $file_name = "records";
        $encoded_filename = rawurlencode($file_name);
        if(preg_match("/MSIE/",$ua)){
            header('Content-Dispositon: attachment; filename="'. $encoded_filename. '"'); 
        }else if(preg_match("/Firefox/",$ua)){
            header("Content-Disposition: attachment; filename*=\"utf8''" . $file_name . '"'); 
        }else{
            header('Content-Disposition: attachement; filename="' . $file_name . '"'); 
        
       }
       readfile($file);

	}
	//check domain 
	public function check_domain($domain_name){
		if($domain_name==""){
			return false;
		}else{
			return true;
		}
		
	}


}
