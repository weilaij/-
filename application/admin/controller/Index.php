<?php 
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Session;

class Index extends Base
{
    public function index()
    {
        
        if(Session::has('user_id'))
        	{
        		$this->view->assign(['title'=>'主页']);
        		return $this->view->fetch();	
        	}else{
        		$this->redirect('user/login');
        	}
       
  
    
    }
    
}



 ?>