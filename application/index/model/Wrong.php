<?php 
namespace app\index\model;
use think\Model;
class Wrong extends Model
{
	public function stk()
    {
        return $this->belongsTo('Stk','id');
    }
	
	
}



 ?>