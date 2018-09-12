<?php 
namespace app\index\model;
use think\Model;
class Cllect extends Model
{
	public function stk()
    {
        return $this->belongsTo('Stk','id');
    }
	
}



 ?>