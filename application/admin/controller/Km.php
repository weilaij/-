<?php 
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Session;
use think\Request;
use think\Db;
use app\admin\model\Stk as StkModel;
use app\admin\model\Chinfo as ChModel;
use app\admin\model\Subinfo as SubModel;
use app\admin\model\Statistics as UstaModel;
use app\admin\model\Option as OpModel;
use app\admin\model\Wrong as WrModel;
use app\admin\model\Cllect as CllModel;
class Km extends Base
{
  public function index()
  {
   $this -> isLogin(); 
   $subinfo=SubModel::all();
   $allstudent=UstaModel::all();
   $allstudent=count($allstudent);
   $subinfo[0]['sta']="jds_sum";
   $subinfo[1]['sta']="sx_sum";
   $subinfo[2]['sta']="mg1_sum";
   $subinfo[3]['sta']="mg2_sum";
   $subinfo[4]['sta']="mks_sum";

   for ($i=0; $i <count($subinfo) ; $i++) { 
     $student_no=UstaModel::all([$subinfo[$i]['sta']=>'0']);
     $student=$allstudent-count($student_no);
     $subinfo[$i]['student_count']=$student;
     $ch=ChModel::all(['sub'=>$subinfo[$i]['id']]);
     $subinfo[$i]['ch_count']=count($ch);
     $st_count=StkModel::all(['sub'=>$subinfo[$i]['id']]);
     $subinfo[$i]['st_count']=count($st_count);

   }
   $this->view->assign('subinfo',$subinfo);       
   return $this->view->fetch();
  }
   public function chinfo(Request $request)
   {
    $sub=$request -> param('sub');
    $title1=SubModel::get(['id'=>$sub]);
    $chinfo=ChModel::all(['sub'=>$sub]);
    $count=count($chinfo);

    for ($i=0; $i <count($chinfo) ; $i++) { 
      $st=StkModel::all(['sub'=>$sub,'ch'=> $chinfo[$i]['id']]);
      $st_count=count($st);
      $chinfo[$i]['count']=$st_count;
    }
    $this->view->assign('title','章节信息');
    $this->view->assign('count',$count); 
    $this->view->assign('chinfo',$chinfo);
    $this->view->assign('sub',$sub); 
    $this->view->assign('title1',$title1);   
    return $this->view->fetch();
  }

  public function chEdit(Request $request)
  {

    $ch_id = $request -> param();
    $ch_info = ChModel::get(['id'=>$ch_id['id']]);
    $this->view->assign('title','编辑章节信息');
    $this->view->assign('keywords','学霸思政');
    $this->view->assign('desc','学霸思政');
    $this->view->assign('ch_info',$ch_info);

    return $this->view->fetch('editCh');
  }
  public function editCh(Request $request)
  {
    $date = $request -> param();
    $status = 1;
    $message = "更新失败";
    $ch=ChModel::where("id",$date['id'])->update(['name'=>$date['name'],'title'=>$date['title']]);
    if ($ch) {
      $status = 0;
      $message = '更新成功~~';
    }
    return ['status'=>$status, 'message'=>$message];
  }
  
  public function  ChAdd(Request $request)
  {
    $sub=$request->param('sub');
    $this->view->assign('title','添加章节');
    $this->view->assign('keywords','学霸思政');
    $this->view->assign('desc','学霸思政');
    $this->view->assign('sub',$sub);
    return $this->view->fetch('ch_add');
  }
      //检测id是否可用
  public function checkCh(Request $request)
  {
    $data = $request -> param();
    $status = 1;
    $message = 'id可用';

    if (ChModel::get(['sub'=>$data['sub'],'ch'=> $data['id']])) {
              //如果在表中查询到该用户名
      $status = 0;
      $message = '章节id重复,请重新输入~~';
    }
    return ['status'=>$status, 'message'=>$message];
  }
  public function addCh(Request $request)
  {
    $data = $request -> param();
    $status = 0;
    $message = "添加失败";

    $rule = [
      'title|章节名' => "require|min:1|max:2",
    ];

    $result = $this -> validate($data, $rule);

    if ($result) {
      $map=[
        'name'=>$data['name'],
        'sub'=>$data['sub'],
        'title'=>$data['title']
      ];

      $ch= ChModel::create($map);
      if ($ch) {
        $status = 1;
        $message = '添加成功';
      }
    }


    return ['status'=>$status, 'message'=>$message];
  }
  public function deleteCh(Request $request)
  {
    $ch_id = $request -> param('id');
    ChModel::destroy(['id'=> $ch_id]);   
   
    


  }

public function stview(Request $request)
{
   $this -> isLogin(); 
 $ch=$request -> param('ch');
 $sub=$request -> param('sub');
 $stk=StkModel::all(['sub'=>$sub,'ch'=>$ch,'type'=>'0']);
 $stkm=StkModel::all(['sub'=>$sub,'ch'=>$ch,'type'=>'1']);
 $stklist=stk($stk,count($stk),0);
 $stklist_m=stk($stkm,count($stkm),1);

$subname=SubModel::get(['id'=>$sub]);
$chname=ChModel::get(['id'=>$ch]);
 $this->view->assign('title','查看试题');
 $this->view->assign('keyword','学霸思政');
 $this->view->assign('stk',$stklist);
 $this->view->assign('stkm',$stklist_m);
 $this->view->assign('subname',$subname);
 $this->view->assign('chname',$chname);
 $this->view->assign('desc','学霸思政');
 return $this->view->fetch();
}
public function steditone(Request $request)
{
 $stid=$request -> param('id');
 $stk=StkModel::get(['id'=>$stid]);
 $this->view->assign('stk',$stk);
 return $this->view->fetch('editstone');

}
public function steditmany(Request $request)
{
 $stid=$request -> param('id');
 $stk=StkModel::get(['id'=>$stid]);
 $this->view->assign('stk',$stk);
 return $this->view->fetch('editstmany');

}
public function stupdate(Request $request)
{
 $data=$request -> param();
 $status = 0;
 $message = "更新失败";
 if($data['ans']==1)
 {
  $ans='status_a';
}elseif($data['ans']==2)
{
  $ans='status_b';
}elseif($data['ans']==3)
{
  $ans='status_c';
}else{
  $ans='status_d';
}
if($data['oldans']==$ans)
{
 $stk_op=array('key_a'=>$data['key_a'],
  'key_b'=>$data['key_b'],
  'key_c'=>$data['key_c'],
  'key_d'=>$data['key_d'],
);
}else{
 $stk_op=array('key_a'=>$data['key_a'],
  'key_b'=>$data['key_b'],
  'key_c'=>$data['key_c'],
  'key_d'=>$data['key_d'],
  $ans=>1,
  $data['oldans']=>0
);
}
$isst=Db::table('stk')->where('id',$data['id'])->update(['stem'=>$data['stem']]);
$op=Db::table('option')->where('id',$data['id'])->update($stk_op);

if ($isst || $op) {
  $status = 1;
  $message = '更新成功~~';
}
return ['status'=>$status, 'message'=>$message];


}
public function stmupdata(Request $request)
{
 $data=$request -> param();
 $status = 0;
 $message = "更新失败";
 if(count($data)==14)
 {
  $stk_op=array('key_a'=>$data['key_a'],
    'key_b'=>$data['key_b'],
    'key_c'=>$data['key_c'],
    'key_d'=>$data['key_d'],
    'key_e'=>$data['key_e'],
    'key_f'=>$data['key_f'],

    'status_a'=>$data['ans1'],
    'status_b'=>$data['ans2'],
    'status_c'=>$data['ans3'],
    'status_d'=>$data['ans4'],
    'status_e'=>$data['ans5'],
    'status_f'=>$data['ans6'],

  );
}else{
 $stk_op=array('key_a'=>$data['key_a'],
  'key_b'=>$data['key_b'],
  'key_c'=>$data['key_c'],
  'key_d'=>$data['key_d'],


  'status_a'=>$data['ans1'],
  'status_b'=>$data['ans2'],
  'status_c'=>$data['ans3'],
  'status_d'=>$data['ans4'],

);
}

$oldans=array( 'status_a'=>0,
  'status_b'=>0,
  'status_c'=>0,
  'status_d'=>0,
  'status_e'=>0,
  'status_f'=>0,);
$op1=Db::table('option')->where('id',$data['id'])->update($oldans);
$isst=Db::table('stk')->where('id',$data['id'])->update(['stem'=>$data['stem']]);
$op=Db::table('option')->where('id',$data['id'])->update($stk_op);
if ($isst || $op) {
  $status = 1;
  $message = '更新成功~~';
}
return ['status'=>$status, 'message'=>$message];

}
public function deletest(Request $request)
{
  $st_id = $request -> param('id');
  if(WrModel::all(['id'=>$st_id]))
    {
      Db::table('wrong')->where('id',$st_id)->delete();
    }
    if(CllModel::all(['id'=>$st_id]))
    {
      Db::table('cllect')->where('id',$st_id)->delete();
    }
  StkModel::destroy(['id'=> $st_id]);  
  OpModel::destroy(['id'=> $st_id]);      
}
}



?>