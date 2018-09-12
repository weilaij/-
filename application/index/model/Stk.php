<?php 
namespace app\index\model;
use think\Model;
class Stk extends Model
{
	public function option()
    {
        return $this->hasOne('Option','id');
    }
    
        
}



 ?>