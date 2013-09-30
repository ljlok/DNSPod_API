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
	//check domain 
	public function check_domain($domain_name){
		if($domain_name==""){
			return false;
		}else{
			return true;
		}
		
	}


}
