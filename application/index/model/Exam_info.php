<?php 
namespace app\index\model;
use think\Model;
class Exam_info extends Model
{
      /*进行课程id与科目名进行交换*/
     public function getCourseAttr($value)
    {
        $course = [
            1=>'中国近现代史纲要',
            2=>'思想道德修养与法律基础',
            3=> '毛泽东思想和中国特色社会主义理论体系概论Ⅰ',
            4=> '毛泽东思想和中国特色社会主义理论体系概论Ⅱ',
            5=> '马克思主义基本原理'
        ];
        return $course[$value];
    }
        

    
}



 ?>