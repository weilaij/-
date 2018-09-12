<?php
use app\index\model\Cllect as CllModel;
use app\index\model\User as UserModel;
use app\index\model\Statistics as StaModel;
use think\Session;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function sendRequest($uri)
   {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Yi OAuth2 v0.1');
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array());
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    $response = curl_exec($ch);
    return $response;
}

function curl_post_https($url,$data){ // 模拟提交数据函数
    $curl = curl_init(); // 启动一个CURL会话
    curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
    curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec($curl); // 执行操作
    if (curl_errno($curl)) {
        echo 'Errno'.curl_error($curl);//捕抓异常
    }
    curl_close($curl); // 关闭CURL会话
    return $tmpInfo; // 返回数据，json格式
}

function ans($stk,$tp,$i)
{
   if( $tp==1){
       if($stk[$i]['option']['status_a']==1)
        {
            $ansa='A';
        }else{
            $ansa='';
        }
         if($stk[$i]['option']['status_b']==1)
        
        {
            $ansb='B';
        }else{
            $ansb='';
        }
         if($stk[$i]['option']['status_c']==1)
        {
            $ansc='C';
        }else{
            $ansc='';
        }
         if($stk[$i]['option']['status_d']==1)
        {
            $ansd='D';
        }else{
            $ansd='';
        }
        if($stk[$i]['option']['status_e']==1)
        {
            $anse='E';
        }else{
            $anse='';
        }
        $ans= $ansa. $ansb. $ansc. $ansd.$anse;
        }else{
          if($stk[$i]['option']['status_a']==1)
        {
            $ans='A';
        }
         if($stk[$i]['option']['status_b']==1)
        {
            $ans='B';
        }
         if($stk[$i]['option']['status_c']==1)
        {
            $ans='C';
        }
         if($stk[$i]['option']['status_d']==1)
        {
            $ans='D';
        }
        }
        return $ans;    
    
}
function user_wrong($sub)
{
    switch ($sub) {
        case '1':
           $wrong="jds_wrong";
            break;
        case '2':
           $wrong="sx_wrong";
            break;
         case '3':
           $wrong="mg1_wrong";
            break;
         case '4':
           $wrong="mg2_wrong";
            break;
         case '5':
           $wrong="mks_wrong";
            break;  
    }
    return $wrong;
}
function user_sum($sub)
{
   switch ($sub) {
        case '1':
           $sum="jds_sum";
            break;
        case '2':
           $sum="sx_sum";
            break;
         case '3':
           $sum="mg1_sum";
            break;
         case '4':
           $sum="mg2_sum";
            break;
         case '5':
           $sum="mks_sum";
            break;  
    }
    return $sum; 
}
function ybauth()
{
    $APPID = '9c06f8415ea56f73';   //在open.yiban.cn管理中心的AppID
    $APPSECRET = '487e6d63d9aab393e7d06bea8c5baf45'; //在open.yiban.cn管理中心的AppSecret
    $CALLBACK = 'http://f.yiban.cn/iapp223683'; //在open.yiban.cn管理中心的oauth2.0回调地址

   if (isset($_GET["code"])) { // 用户授权后跳转回来会带上code参数，此处code非access_token，需调用接口转化。
    $getTokenApiUrl = "https://oauth.yiban.cn/token/info?code=" . $_GET['code'] . "&client_id={$APPID}&client_secret={$APPSECRET}&redirect_uri={$CALLBACK}";
    $res = sendRequest($getTokenApiUrl);
    if (!$res) {
        exit('Get Token Error');
    }
    $userTokenInfo = json_decode($res);
    $access_token = $userTokenInfo["access_token"];
} else {
    $_GET["verify_request"] = isset($_GET["verify_request"])?$_GET["verify_request"]:'';
    $postStr = pack("H*", $_GET["verify_request"]);
    if (strlen($APPID) == '16') {
        $postInfo = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $APPSECRET, $postStr, MCRYPT_MODE_CBC, $APPID);
    } else {
        $postInfo = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $APPSECRET, $postStr, MCRYPT_MODE_CBC, $APPID);
    }
    $postInfo = rtrim($postInfo);
    $postArr = json_decode($postInfo, true);
    if (!$postArr['visit_oauth']) { // 说明该用户未授权需跳转至授权页面
        header("Location: https://openapi.yiban.cn/oauth/authorize?client_id={$APPID}&redirect_uri={$CALLBACK}&display=mobile");
        die();
    }
    $access_token = $postArr['visit_oauth']['access_token'];
}


    $userInfoJsonStr1 = sendRequest("https://openapi.yiban.cn/user/me?access_token={$access_token}");
     $userInfoJsonStr3= sendRequest("https://openapi.yiban.cn/user/verify_me?access_token={$access_token}");
     $userInfo1 = json_decode($userInfoJsonStr1);
     $userInfo1 = $userInfo1->info;
     $head = $userInfo1->yb_userhead;
     $id=$userInfo1->yb_userid;
   
    $userInfo3 = json_decode($userInfoJsonStr3);
    $userInfo3 = $userInfo3->info;
    $name=$userInfo3->yb_realname;

    $studentid=$userInfo3->yb_studentid;
     Session::set('student_id',$studentid);
   Session::set('name',$name);
   Session::set('head',$head);
}
/*获取收藏模式，错误集数据*/
function stk2($stk,$stkcount,$tp)
{
     $question=array();
         $que=array();
     for ($i=0; $i <$stkcount ; $i++) {
        if( $tp==1){
       if($stk[$i]['stk']['option']['status_a']==1)
        {
            $ansa='A';
        }else{
            $ansa='';
        }
         if($stk[$i]['stk']['option']['status_b']==1)
        
        {
            $ansb='B';
        }else{
            $ansb='';
        }
         if($stk[$i]['stk']['option']['status_c']==1)
        {
            $ansc='C';
        }else{
            $ansc='';
        }
         if($stk[$i]['stk']['option']['status_d']==1)
        {
            $ansd='D';
        }else{
            $ansd='';
        }
        if($stk[$i]['stk']['option']['status_e']==1)
        {
            $anse='E';
        }else{
            $anse='';
        }
        $ans= $ansa. $ansb. $ansc. $ansd.$anse;
        }else{
          if($stk[$i]['stk']['option']['status_a']==1)
        {
            $ans='A';
        }
         if($stk[$i]['stk']['option']['status_b']==1)
        {
            $ans='B';
        }
         if($stk[$i]['stk']['option']['status_c']==1)
        {
            $ans='C';
        }
         if($stk[$i]['stk']['option']['status_d']==1)
        {
            $ans='D';
        }
        }    
        $que['questionid']=$stk[$i]['stk']['id'];
        $que['first']=$stk[$i]['stk']['stem'];
        $que['type']=$stk[$i]['stk']['type'];
        $que['option']=array();
        $que['answer']=$ans;
         $cllect=CllModel::get(['user_id'=>Session::get('student_id'),'id'=>$stk[$i]['id']]);
        if($cllect)
        {
            $que['cllect']=1;
        }else{
            $que['cllect']=0;
        }
        $option['op']='A';
        $option['content']=$stk[$i]['stk']['option']['key_a'];
        array_push($que['option'], $option);
        $option['op']='B';
        $option['content']=$stk[$i]['stk']['option']['key_b'];
        array_push($que['option'], $option);
          if($stk[$i]['stk']['option']['key_c']==null){
        }else{
        $option['op']='C';
        $option['content']=$stk[$i]['stk']['option']['key_c'];
        array_push($que['option'], $option);

        $option['op']='D';
        $option['content']=$stk[$i]['stk']['option']['key_d'];
        array_push($que['option'], $option);
    }
        if($stk[$i]['stk']['option']['key_e']==null){
        }else{
        $option['op']='E';
        $option['content']=$stk[$i]['stk']['option']['key_e'];
        array_push($que['option'], $option);
       }
        array_push($question,$que);
   }
   
       return $question;
}
/*获取背诵模式、练习模式数据*/
function stk($stk,$stkcount,$tp)
{
     $question=array();
     $que=array();
     for ($i=0; $i <$stkcount ; $i++) {
         $ans=ans($stk,$tp,$i);      
        $que['questionid']=$stk[$i]['id'];
        $que['first']=$stk[$i]['stem'];
        $que['type']=$stk[$i]['type'];
        $que['option']=array();
        $que['answer']=$ans;
         $cllect=CllModel::get(['user_id'=>Session::get('student_id'),'id'=>$stk[$i]['id']]);
        if($cllect)
        {
            $que['cllect']=1;
        }else{
            $que['cllect']=0;
        }
        $option['op']='A';
        $option['content']=$stk[$i]['option']['key_a'];
        array_push($que['option'], $option);

        $option['op']='B';
        $option['content']=$stk[$i]['option']['key_b'];
        array_push($que['option'], $option);
         if($stk[$i]['option']['key_c']==null){
        }else{
        $option['op']='C';
        $option['content']=$stk[$i]['option']['key_c'];
        array_push($que['option'], $option);

        $option['op']='D';
        $option['content']=$stk[$i]['option']['key_d'];
        array_push($que['option'], $option);
    }
        if($stk[$i]['option']['key_e']==null){
        }else{
        $option['op']='E';
        $option['content']=$stk[$i]['option']['key_e'];
        array_push($que['option'], $option);

       }
       array_push($question,$que);
   }
   return $question;
}
function isfirst()
{
   $user=UserModel::get(['student_id'=>Session::get('student_id')]); 
    if($user)
    {
     return 1;
    }else{
      $map=['student_id'=>Session::get('student_id'),
            'name'=>Session::get('name'),
            'creat_time'=>time()
          ];
      $user=UserModel::create($map);
      $sta=StaModel::create(['student_id'=>Session::get('student_id')]);
    }
}
function ybapi()
{
      Session::set('name','江为来');
    Session::set('student_id','150940415');
}

function sub_ch_select()
{
     if(Session::has('sub_select'))
    { 
    }else{
        Session::set('sub_select','1');
    }
    Session::set('chtp_select','0');
}