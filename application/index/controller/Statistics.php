<?php 
namespace app\index\controller;
use app\index\controller\Base;
use think\Request;
use think\Session;
use app\index\model\Cllect as CllModel;
use app\index\model\Wrong as WroModel;
use app\index\model\Stk as StkModel;
use app\index\model\Mksta as MkModel;
use app\index\model\Statistics as StaModel;
use app\index\model\Mistake as MisModel;


class Statistics extends Base
{
    public function iscllect(Request $request)//判断试题是否被收藏
    {
        $data=$request->param();
        $user_id= Session::get('student_id');
        $st_id=$data['st'];
        $cllect = CllModel::get(['user_id' => $user_id,'st_id'=>$st_id]);
        if($cllect==null)
        {
            return 0;
        }else{
           return 1;
        }
    }
    public function cllect(Request $request)//写入收藏及错题
    {
        $data=$request->param();
        $user_id= Session::get('student_id');
        $st_id=$data['questionid'];
        $sub=$data['sub'];
        $op=$data['option'];
         $map=[
                'user_id'=>$user_id,
                'id'=>$st_id,
             ];
        if($op==1)
        {
          $cllect=CllModel::create($map);
        if($cllect)
        {
            return 1;
        }else{
            return 0;
        }
    }else if($op==0){
         $delcllect=CllModel::where($map)->delete();;
             if($delcllect)
        {
             return 1;
        }else{
             return 0;
        }
        }else if($op==2){/*{错题处理（传入个人错题集、题目错题统计、个人题目统计）*/
             $st_info=StkModel::get(['id'=>$st_id]);
             $st_info->false_count+=1;
             $st_info->ans_count+=1;
             $st_info->save();
             $user_sum_km=user_sum($sub);
             $user_wrong_km=user_wrong($sub);
             $sta=StaModel::get(['student_id'=>$user_id]);
             $sta->$user_wrong_km+=1;
             $sta->$user_sum_km+=1;
             $sta->user_sum+=1;
             $sta->user_wrong+=1;
             $sta->save();
              $wrong=WroModel::create($map);

        }else if($op==3){/*{正确处理（题目错题统计、个人题目统计）*/
            $st_info=StkModel::get(['id'=>$st_id]);
             $st_info->ans_count+=1;
             $st_info->save();
             $user_sum_km=user_sum($sub);
             $user_wrong_km=user_wrong($sub);
             $sta=StaModel::get(['student_id'=>$user_id]);
             $sta->$user_sum_km+=1;
             $sta->user_sum+=1;
             $sta->save();
              $wro_info=WroModel::get(['id'=>$st_id,'user_id'=>$user_id]);
              if($wro_info)
              {
                $delcllect=WroModel::where($map)->delete();
              }
            

        }

        }
    public function delcllect(Request $request)
    {
        $data=$request->param();
        $user_id= Session::get('student_id');
        $st_id=$data['questionid'];
         $map=[
                'user_id'=>$user_id,
                'id'=>$st_id,
             ];
         $delcllect=CllModel::where($map)->delete();
        return 1;

    }
    public function mksta(Request $request)
    {
        $data=$request->param();
        $user_id= Session::get('student_id');
       /* var_dump($data);*/
       for ($i=0; $i <80 ; $i++) { 
         if($data['data']['data'][$i]['result']==0)
         {
            $map=['user_id'=>$user_id,
                  'id'=>$data['data']['data'][$i]['questionid'],
            ];
             $wrong=WroModel::create($map);
             $st_info=StkModel::get(['id'=>$data['data']['data'][$i]['questionid']]);
             $st_info->false_count+=1;
             $st_info->ans_count+=1;
             $st_info->save();
         }elseif($data['data']['data'][$i]['result']==1){
            $st_info=StkModel::get(['id'=>$data['data']['data'][$i]['questionid']]);
             $st_info->ans_count+=1;
             $st_info->save();
            
         }

       }
       $score=$data['data']['score'];
        $sub=$data['sub'];
        $wrong=$data['data']['count_wrong'];
        $null=$data['data']['count_null'];
        $map=['create_time'=>time(),
              'student_id'=>$user_id,
              'sub'=>$sub,
              'fs'=>$score,
              'wrong'=>$wrong,
              'wd'=>$null,
        ];
         $mk=MkModel::create($map);
       
    }
       
    public function wrong(Request $request)
    {
        $data=$request->param();
        $user_id= Session::get('student_id');
        $st_id=$data['st'];
         $map=[
                'user_id'=>$user_id,
                'st_id'=>$st_id,
             ];
        $wrong=WroModel::create($map);
    }
    public function delwrong(Request $request)
    {
        $data=$request->param();
        $user_id= Session::get('student_id');
        $st_id=$data['st'];
        $delcllect=WroModel::where(['user_id' => $user_id,'id'=>$st_id])->delete();
        if($delcllect)
        {
             return 1;
        }else{
             return 0;
        } 
    }
    public function mistakedata(Request $request)
    {
        $data=$request->param();
        $user_id= Session::get('student_id');
        $questionid=$data['questionid'];
        $title=$data['where'];
        $conent=$data['detil'];
        $is_mistake=MisModel::get(['id'=>$questionid,'user_id'=>$user_id]);
        if($is_mistake)
        {
            return 0;
        }else{
            $map=['st_id'=>$questionid,'user_id'=>$user_id,'mistake_location'=>$title,'mistake_des'=>$conent,'create_time'=>time()];
            $mistake=MisModel::create($map);
            return 1;
        }
    }

}
    



 ?>