<?php

namespace App\Http\Controllers;
use App\Http\Tools\Wechat;
use Illuminate\Http\Request;
use DB;

class LiuYanController extends Controller
{
    // public $wechat;
    // public function __construct(Wechat $wechat)
    // {
    // 	$this->wechat=$wechat;
    // }
    public function wechat_login()
    {
    	$redirect_uri='http://www.blog.com/liuyan/wechat_code';
    	  $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".env('WECHAT_APPID')."&redirect_uri=".urlEncode($redirect_uri)."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";//用户同意授权，获取code
        
//        dd($url);
        header('location:'.$url);
    }
			
    
    public function wechat_code(Request $request)
    {

    	 $req = $request->all();	
    	 // dd($req);
        $result = file_get_contents('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WECHAT_APPID').'&secret='.env('WECHAT_APPSECRET').'&code='.$req['code'].'&grant_type=authorization_code');//通过code换取网页授权access_token
        // dd($result);
        $re = json_decode($result,1);
        dd($re);
        $user_info = file_get_contents('https://api.weixin.qq.com/sns/userinfo?access_token='.$re['access_token'].'&openid='.$re['openid'].'&lang=zh_CN');//拉取用户信息
        $wechat_user_info = json_decode($user_info,1);
      // dd($wechat_user_info);
        $openid=$re['openid'];
    	//网站登录
    	$user_wechat=DB::table('user_openid')->where(['openid'=>$result['openid']])->first();
		//用户基本信息
		$wechat_info=$this->wechat->wechat_user_info($result['openid']);    	
		if (!empty($user_wechat)) {
		   		//已注册 主要登录操作
			$request->session()->put(['uid'=>$user_wechat['uid']]);
		}else{
			//未注册，需要注册然后登录

			$uid=DB::table('user')->insert([
			  'name'=>$wechat_info['nickname'],
			  'password'=>'',
			  'reg_time'=>time()
			]);
			// dd($uid);
			$wechat_insert=DB::table('user_openid')->insert([
				'uid'=>$uid,
				'openid'=>$user_wechat['uid']
			]);
			$request->session()->put(['uid'=>$user_wechat['uid']]);
		}
    }
  //  public function index()
  //   {

  //   	$info=DB::table('user_openid')->get();
  //   	return view('liuyan/index',['info'=>$info]);
  //   	// dd($info);
  //   	foreach ($info as $v) {
  //   		$user_info=$this->wechat->wechat_user_info($v->openid);
  //   		$v->nickname=$user_info['nickname'];
    		
  //   	}
  //  }

    public function login()
    {
    	return view('liuyan.login');
    }
}
