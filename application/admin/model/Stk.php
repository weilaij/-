<?php 
namespace app\admin\model;
use think\Model;
class Stk extends Model
{
	public function option()
    {
        return $this->hasOne('Option','id');
    }
     public function getSubAttr($value)
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
    public function getChAttr($value)
    {
        $course = [
            1=>'第一章',
            2=> '第二章',
            3=> '第三章',
            4=> '第四章'
        ];
        return $course[$value];
    }
    public function getTypeAttr($value)
    {
        $course = [
            0=>'单选题',
            1=> '多选题',
           
        ];
        return $course[$value];
    }
        
}



 ?>