<?php 
namespace app\index\controller;
use app\index\controller\Base;
use think\Session;
use think\Request;
use think\Db;
use app\index\model\Statistics as StaModel;
use app\index\model\Exam_info as ExaModel;
use app\index\model\Mksta as MkModel;


class Personal extends Base
{
	public function prainf()
	{
		$this->isAuth();
		$user_id=Session::get('student_id');
		$sta=StaModel::get(['student_id'=>$user_id]);
		if($sta['user_sum']==0)
		{
		 $isexam=0;
		}else{
		$isexam=1;
		}
		$this->view->assign('sta',$sta);
		$this->view->assign('isexam',$isexam);
		return $this->view->fetch('prainfo');
		
	}
	public function examinfo()
	{
		$this->isAuth();
		$user_id=Session::get('student_id');
		$examinfo=ExaModel::all(['student_id'=>$user_id]);
		if(!$examinfo)
		{
		 $isexam=0;
		}else{
		$isexam=1;
		}
		$this->view->assign('examinfo',$examinfo);
		$this->view->assign('isexam',$isexam);
		return $this->view->fetch();
	
	}
	public function mkinfo()
	{
		$this->isAuth();
		$user_id=Session::get('student_id');
		$mkinfo=MkModel::all(['student_id'=>$user_id]);
		if(!$mkinfo)
		{
		 $isexam=0;
		}else{
		$isexam=1;
		$count=count($mkinfo);
		for ($i=0; $i <$count ; $i++) { 
			$fs[$i]=$mkinfo[$i]['fs'];
		}
		$avg=round(array_sum($fs)/$count,2);
		$max=max($fs);
		$this->view->assign('count',$count);
		$this->view->assign('max',$max);
		$this->view->assign('avg',$avg);
		}
		
		$this->view->assign('mkinfo',$mkinfo);
		$this->view->assign('isexam',$isexam);
		return $this->view->fetch();

	}
	public function about()
	{
		return $this->view->fetch();
	}
}

 ?>
