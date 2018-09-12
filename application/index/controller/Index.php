<?php 
namespace app\index\controller;
use app\index\controller\Base;
use think\Request;
use think\Session;
use app\index\model\Chinfo as ChModel;
use think\Db;
use app\index\model\Wrong as WroModel;
use app\index\model\User as UserModel;
use app\index\model\Cllect as CllModel;
use app\index\model\Statistics as StaModel;
class Index extends Base
{
 public function index()
  {
      ybauth();
	    isfirst();//判断是否第一次登陆
		$this->redirect('index/index1');
  }
    
    public function index1()
    {
		$this->isAuth();
		  sub_ch_select();
   if(Session::has('sub_select'))
    { 
    }else{
        Session::set('sub_select','1');
    }
  
    
    return $this->view->fetch('index');

    }
    public function my()
    {
      $this->isAuth();
      return $this->view->fetch();
    }
    public function mulu(Request $request)
    {
      $this->isAuth();
       $data=$request->param();
       $sub=$data['subject'];
       $type=$data['type'];
       $user_id=Session::get('student_id');
     /*  对收藏和错题集的目录动态加载*/
       if ($type=="wrong" || $type=="cllect") {
         if($type=="wrong")
         {
            $list = WroModel::hasWhere('stk',['sub'=>$sub,'type'=>'0'])->where('user_id',$user_id)->select();
             $listm = WroModel::hasWhere('stk',['sub'=>$sub,'type'=>'1'])->where('user_id',$user_id)->select();
        }else{
            $list = CllModel::hasWhere('stk',['sub'=>$sub,'type'=>'0'])->where('user_id',$user_id)->select();
             $listm = CllModel::hasWhere('stk',['sub'=>$sub,'type'=>'1'])->where('user_id',$user_id)->select();
        }
        if($list) 
        {
        for ($i=0; $i <count($list) ; $i++) { 
        $ch[$i]=$list[$i]['stk']['ch'];
       }
      $ch=array_unique($ch);
       $ch=array_merge($ch);
    //  $chinfo1=ChModel::all(['sub'=>$sub]);
     for ($i=0; $i < count($ch); $i++) { 
            $cho[$i]=ChModel::get(['id'=>$ch[$i]]);
        }
      }else{
        $cho=0;
      }
      

       if($listm)
       {
         for ($i=0; $i <count($listm) ; $i++) { 
        $ch[$i]=$listm[$i]['stk']['ch'];
       }
       $ch=array_unique($ch);
       $ch=array_merge($ch);
       //  $chinfo2=ChModel::all(['sub'=>$sub]);
     for ($i=0; $i < count($ch); $i++) { 
           
          $chm[$i]=ChModel::get(['id'=>$ch[$i]]);
        }
        }else{
          $chm=0;
       }
      

       }else{
         $chinfo=ChModel::all(['sub'=>$sub]);
         if($chinfo==null)
         {
          $cho=0;
          $chm=0;
         }else{
          $cho=$chinfo;
         $chm=$chinfo;
         }
         
       }
       
        $this -> view -> assign('ustaList', $cho);
         $this -> view -> assign('ustaListm', $chm);
       $this->view->assign('type',$type);
        $this->view->assign('sub',$sub);
        return $this->view->fetch();
    } 
    public function subselect(Request $request)
    {
        $data=$request->param();
        Session::set('sub_select',$data['sub']);
    }
     public function chtpselect(Request $request)
    {
        $data=$request->param();
        Session::set('chtp_select',$data['chtp']);
    }
    
}



 ?>