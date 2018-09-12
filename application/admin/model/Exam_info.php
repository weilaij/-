<?php 
namespace app\admin\model;
use think\Model;
class Exam_info extends Model
{
      /*进行课程id与科目名进行交换*/
     public function getCourseAttr($value)
    {
        $course = [
            1=>'近代史',
            2=>'思修',
            3=> '毛概Ⅰ',
            4=> '毛概Ⅱ',
            5=> '马克思'
        ];
        return $course[$value];
    }
        

    
}



 ?>