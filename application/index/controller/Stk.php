<?php 
namespace app\index\controller;
use app\index\controller\Base;
use think\Request;
use think\Session;
use think\Db;
use app\index\model\Stk as StkModel;
use app\index\model\Cllect as CllModel;
use app\index\model\Wrong as WroModel;

class Stk extends Base
{
    public function rember(Request $request)
    {
       $this->isAuth();    
       $data=$request->param();
         //echo  $data['tp'];
       $sub=[1=>"中国近代史",2=>"思修",3=>"毛概上",4=>"毛概下",5=>"马克思"];
       $ch=Db::table('chinfo')->where('id',$data['ch'])->field('title')->select();
       $title=$sub[$data['sub']]."—".$ch['0']['title'];
       $this->view->assign('title',$title);
       $this->view->assign('sub',$data['sub']);
       $this->view->assign('ch',$data['ch']);
       $this->view->assign('tp',$data['tp']);
       return $this->view->fetch('rember');
   }
    public function data(Request $request)//背诵、练习模式试题数据获取
    {
    	//header("content-type:text/html;charset=utf-8"); 
        $user_id=Session::get('student_id'); 
        $data=$request->param();
        $sub=$data['sub'];
        $ch=$data['ch'];
        $tp=$data['tp'];
        
        $stk=StkModel::all(['sub'=>$sub,'ch'=>$ch,'type'=>$tp]);
        $stkcount=StkModel::where(['sub'=>$sub,'ch'=>$ch,'type'=>$tp])->count();
        $question=stk($stk,$stkcount,$tp);
        $quejson=json_encode($question,JSON_UNESCAPED_UNICODE);
        return  $quejson;
    }
    public function practice(Request $request)
    {
      $this->isAuth();
      $data=$request->param();
         //echo  $data['tp'];
      $sub=[1=>"中国近代史",2=>"思修",3=>"毛概上",4=>"毛概下",5=>"马克思"];
      $ch=Db::table('chinfo')->where('id',$data['ch'])->field('title')->select();
      $title=$sub[$data['sub']]."—".$ch['0']['title'];
      $this->view->assign('title',$title);

      $this->view->assign('sub',$data['sub']);
      $this->view->assign('ch',$data['ch']);
      $this->view->assign('tp',$data['tp']);
      return $this->view->fetch('practice');
  }
    public function simdata(Request $request)//模考模式试题数据获取
    {
        $data=$request->param();
        $sub=$data['sub'];
        $user_id=Session::get('student_id');
        /*随机获取60道单选题试题数据*/
        $stk = StkModel::all(function($query)use($sub){
           $query->where(['sub'=> $sub,'type'=>'0'])->order('rand()')->limit(60);
       });
        $question1=stk($stk,$stkcount=60,$tp=0);
        $stk = StkModel::all(function($query)use($sub){
           $query->where(['sub'=>$sub,'type'=>'1'])->order('rand()')->limit(20);
       });
        $question2=stk($stk,$stkcount=20,$tp=1);
        $question=array_merge($question1,$question2);
        $quejson=json_encode($question,JSON_UNESCAPED_UNICODE);
        return  $quejson;
    }
    public function simulation(Request $request)
    {
      $this->isAuth();
      $data=$request->param();
      $sub=[1=>"中国近代史",2=>"思修",3=>"毛概上",4=>"毛概下",5=>"马克思"];
      
      $title=$sub[$data['sub']]."—"."模考";
      $this->view->assign('title',$title);
      $this->view->assign('sub',$data['sub']);
      return $this->view->fetch('simulation');
  }
  public function wrongdata(Request $request)
  {
     $user_id=Session::get('student_id');
     $data=$request->param();
     $sub=$data['sub'];
     $ch=$data['ch'];
     $tp=$data['tp'];
     $cllect = WroModel::hasWhere('stk',['sub'=>$sub,'ch'=>$ch,'type'=>$tp])->where('user_id',$user_id)->select();
     $cllectcount=count($cllect);
     $question=stk2($cllect,$cllectcount,$tp);
     $quejson=json_encode($question,JSON_UNESCAPED_UNICODE);
     return  $quejson;

     
 }
 public function wrong(Request $request)
 {
  $this->isAuth();
  $data=$request->param();
         //echo  $data['tp'];
  $sub=[1=>"中国近代史",2=>"思修",3=>"毛概上",4=>"毛概下",5=>"马克思"];
  $ch=Db::table('chinfo')->where('id',$data['ch'])->field('title')->select();
  $title=$sub[$data['sub']]."—".$ch['0']['title'];
  $this->view->assign('title',$title);

  $this->view->assign('sub',$data['sub']);
  $this->view->assign('ch',$data['ch']);
  $this->view->assign('tp',$data['tp']);
  return $this->view->fetch('wrong');
}
public function cllect(Request $request)
{
  $this->isAuth();
  $data=$request->param();
         //echo  $data['tp'];
  $sub=[1=>"中国近代史",2=>"思修",3=>"毛概上",4=>"毛概下",5=>"马克思"];
  $ch=Db::table('chinfo')->where('id',$data['ch'])->field('title')->select();
  $title=$sub[$data['sub']]."—".$ch['0']['title'];
  $this->view->assign('title',$title);
  $this->view->assign('sub',$data['sub']);
  $this->view->assign('ch',$data['ch']);
  $this->view->assign('tp',$data['tp']);
  return $this->view->fetch('cllect');
}
public function cllectdata(Request $request)
{
    $user_id=Session::get('student_id');
    $data=$request->param();
    $sub=$data['sub'];
    $ch=$data['ch'];
    $tp=$data['tp'];
    $cllect = CllModel::hasWhere('stk',['sub'=>$sub,'ch'=>$ch,'type'=>$tp])->where('user_id',$user_id)->select();
    $cllectcount=count($cllect);
    $question=stk2($cllect,$cllectcount,$tp=$tp);
    $quejson=json_encode($question,JSON_UNESCAPED_UNICODE);
    return  $quejson;
    

    
}

}






?>    
