<?php

class DomainController extends Yaf_Controller_Abstract {

   public function indexAction() {//默认Action


	}

	public function addAction(){//add domain action
		 
		 $request = $this->getRequest();
		 $domain_name = htmlspecialchars( $_REQUEST['domain_name'] );
		 if ( $this->check_domain($domain_name) ){
		 	// Yaf_Dispatcher::getInstance()->disableView();
		 	 //$this->initView();
		 	 //$this->indexAction();
		 	 $params = init_params('domain_add',$domain_name);
       		 $arr = process_API_info(
       					get_API_info($params['api'],$params['post']),
       					'domain_add'
       				 );
       		 if($arr['status']->code==1){
       		 	$this->redirect("/DNSPod/public/Index/");
       		 	//$this->getView()->assign("err_msg",$domain_name);
       		 }else{
       		 	$this->getView()->assign("err_msg",$arr['status']->message);
       		 }
		 	  
		 }else{
		 	$this->getView()->assign("err_msg","domain不能为空");
		 }
	}


	public function removeAction(){//remove domain action
		$domain_id = htmlspecialchars( $_REQUEST['domain_id'] );
		$params = init_params('domain_remove',$domain_id);
       	$arr = process_API_info(
       					get_API_info($params['api'],$params['post']),
       					'domain_remove'
       				 );
       	if($arr['status']->code==1){
       		$this->redirect("/DNSPod/public/Index/");
       	}else{
       		$this->getView()->assign("err_msg",$arr['status']->message);
       	}

	}

	public function updateAction(){//update status of domain action
		$post_param['domain_id'] = htmlspecialchars( $_REQUEST['domain_id'] );
		$_REQUEST['status']=='enable'?$post_param['status']='enable':$post_param['status']='disable';

		$params = init_params('domain_update',$post_param);
		$arr = process_API_info(
       					get_API_info($params['api'],$params['post']),
       					'domain_update'
       				 );
       	if($arr['status']->code==1){
       		$this->redirect("/DNSPod/public/Index/");
       	}else{
       		$this->getView()->assign("err_msg",$arr['status']->message);
       	}

	}

	public function detailAction(){
		$domain_id = htmlspecialchars( $_REQUEST['domain_id'] );
		$params = init_params('domain_detail',$domain_id);
		$arr = process_API_info(
       					get_API_info($params['api'],$params['post']),
       					'domain_detail'
       				 );
       	if($arr['status']->code==1){
       		$this->getView()->assign("domain",$arr['domain']);
       	}else{
       		$this->getView()->assign("err_msg",$arr['status']->message);
       	}

	}

	public function importAction(){//import domain action

		$upload_dir = APP_PATH."/uploadfile/";          
  		$_FILES['import_domain']['name'] = "domains";
  
  		$uploadfile = $upload_dir.basename($_FILES['import_domain']['name']);
  		if(move_uploaded_file($_FILES['import_domain']['tmp_name'],$uploadfile)){
  			$file_info = file($uploadfile);
  			foreach ($file_info as $key => $value) {
  				$params = init_params('domain_add',$value);
       		 	$arr = process_API_info(
       					get_API_info($params['api'],$params['post']),
       					'domain_add'
       				 );
       		 	if($arr['status']->code!=1){
       		 		$this->getView()->assign("err_msg","line".($key+1)."->".$value." ".$arr['status']->message);
       		 	}
  			}

  			//$this->redirect("/DNSPod/public/Index/");
  		}else{
			$this->getView()->assign("err_msg","error!");
		}
	}

	public function exportAction(){
		 Yaf_Dispatcher::getInstance()->disableView();
		 
		 $params = init_params('domain_list');
       	 $arr = process_API_info(
       					get_API_info($params['api'],$params['post']),
       					'domain_list'
       				 );
       	 $file = APP_PATH."/downloadfile/domains";
       	 $handle = fopen($file , "w");
       	 foreach ($arr['domains'] as $key => $item) {
       	 	fwrite($handle, $item->name."\r\n");
       	 }
       	 fclose($handle);

        header("Content-type: application/octet-stream");

        $ua = $_SERVER['HTTP_USER_AGENT'];
        $file_name = "domains";
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
