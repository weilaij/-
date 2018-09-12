<?php 
namespace app\index\model;
use think\Model;
class Mistake extends Model
{
	public function stk()
    {
        return $this->hasOne('Stk','id');
    }
	
	
}



 ?>