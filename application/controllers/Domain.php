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
	//check domain 
	public function check_domain($domain_name){
		if($domain_name==""){
			return false;
		}else{
			return true;
		}
		
	}


}
