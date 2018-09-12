<?php 
namespace app\admin\controller;
use app\admin\controller\Base;
use think\Request;
use app\admin\model\Admin_user as UserModel;
use think\Session;

class User extends Base
{
    public function login()
    {
         $this -> alreadyLogin();
        return $this->view->fetch();
    }
   /* 判断后台登录*/
     public function checklogin(Request $request)
    {
        $status=0;
        $result='';
        $data=$request->param();

        $rule=[
         'name'=>'require',
         'password'=>'require',
         'verify'=>'require|captcha',
        ];
        
        $msg=[
        	'name'=>['require'=>'用户名不能为空，请检查！'],
        	'password'=>['require'=>'密码不能为空，请检查！'],
        	'verify'=>[
        		'require'=>'验证码不能为空，请检查！',
        		'captcha'=>'验证码错误！'

        ],

        ];

        $result=$this->validate($data,$rule,$msg);

        if($result===true){
        	$map=[
        		'name'=>$data['name'],
        		'password'=>md5($data['password']),
        	];

        	$user=UserModel::get($map);

        	if($user==null){
        		$result='没有找到该用户';
        	}else{
        		$status=1;
        		$result='验证通过，点击[确定]进入';
        		Session::set('user_id',$user->name);
        		Session::set('user_info',$user->getData());
                 $user -> setInc('login_count');
        	}
        }

        return ['status'=>$status,'message'=>$result,'data'=>$data];


    }
    /*注销登录操作*/
     public function logout()
    {
        Session::delete('user_id');
        Session::delete('user_infoer');
        $this->success('注销登陆，正在返回','index.php/admin/user/login');
    }
    /* 渲染管理员列表*/
    public function adminlist()
    {
        $this -> isLogin(); 
        
    	$this->view->assign(['title'=>'管理员列表']);

    	$this->view->count=UserModel::count();

    	$userName=Session::get('user_info.name');
    	if($userName=='admin'){
    		$list=UserModel::all();

    	}else{
    		$list=UserModel::all(['name'=>$userName]);
    	}
        
    	$this->view->assign(['list'=>$list]);
     
      return $this->view->fetch("admin_list");

    }

   public function  adminAdd()
    {
        $this->view->assign('title','添加管理员');
        $this->view->assign('keywords','学霸思政');
        $this->view->assign('desc','学霸思政');
        return $this->view->fetch('admin_add');
    }

    //检测用户名是否可用
    public function checkUserName(Request $request)
    {
        $data = $request -> param();
        $status = 1;
        $message = '用户名可用';

        if (UserModel::get(['name'=> $data['name']])) {
            //如果在表中查询到该用户名
            $status = 0;
            $message = '用户名重复,请重新输入~~';
        }
        return ['status'=>$status, 'message'=>$message];
    }
    //删除操作
    public function deleteUser(Request $request)
    {
        $user_id = $request -> param('id');
        UserModel::destroy(['id'=> $user_id]);
        

    }
     public function editUser(Request $request)
    {
        $date = $request -> param();
        $status = 1;
        $message = "更新失败";
        $update_time=time();
        $user=UserModel::where("id",$date['id'])->update(['password'=>md5($date['password']),'update_time'=>$update_time]);
         if ($user) {
                $status = 1;
                $message = '更新成功~~';
            }
         return ['status'=>$status, 'message'=>$message];
        

    }
    public function adminEdit(Request $request)
    {
        $user_id = $request -> param('id');
        $result = UserModel::get($user_id);
        $this->view->assign('title','编辑管理员信息');
        $this->view->assign('keywords','学霸思政');
        $this->view->assign('desc','学霸思政');
        $this->view->assign('user_info',$result->getData());
        return $this->view->fetch('admin_edit');
    }
        public function addUser(Request $request)
    {
        $data = $request -> param();
        $status = 1;
        $message = "添加成功";

        $rule = [
            'name|用户名' => "require|min:3|max:10",
            'password|密码' => "require|min:3|max:10",
        ];

        $result = $this -> validate($data, $rule);

        if ($result === true) {
           $create_time=time();
            $map=[
                'name'=>$data['name'],
                'password'=>md5($data['password']),
                'create_time'=>$create_time
            ];

            $user= UserModel::create($map);
            if ($user === null) {
                $status = 0;
                $message = '添加失败~~';
            }
        }


        return ['status'=>$status, 'message'=>$message];
    }
}



 ?>