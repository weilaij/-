<?php 
namespace app\index\model;
use think\Model;
class Mksta extends Model
{
	
public function getSubAttr($value)
    {
        $sub = [
            1=>'近代史',
            2=> '思修',
            3=> '毛概Ⅰ',
            4=> '毛概Ⅱ',
            5=>'马克思'
        ];
        return $sub[$value];
    }

    
}



 ?>