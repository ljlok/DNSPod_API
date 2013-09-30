<?php
class IndexController extends Yaf_Controller_Abstract {

   public function indexAction() {//默认Action
   	   
       $params = init_params('domain_list');
       $arr = process_API_info(
       					get_API_info($params['api'],$params['post']),
       					'domain_list'
       				 );
       $this->getView()->assign("domain_list",$arr);
   }
   
	
}
?>