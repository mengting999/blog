<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use App\Tools\Tools;
class EventController extends Controller
{
    public  $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }
    /**
     * 接收微信发送的消息【用户互动】
     */
    public function event()
    {
        $xml_string = file_get_contents('php://input');  //获取
        // dd($xml_string);
        $wechat_log_psth = storage_path('logs/wechat/'.date('Y-m-d').'.log');
        file_put_contents($wechat_log_psth,"<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n",FILE_APPEND);
        file_put_contents($wechat_log_psth,$xml_string,FILE_APPEND);
        file_put_contents($wechat_log_psth,"\n<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n\n",FILE_APPEND);
        //dd($xml_string);
        $xml_obj = simplexml_load_string($xml_string,'SimpleXMLElement',LIBXML_NOCDATA);
        dd($xml_obj);
        $xml_arr = (array)$xml_obj;
        // dd($xml_arr);
        \Log::Info(json_encode($xml_arr,JSON_UNESCAPED_UNICODE));
        //echo $_GET['echostr'];
        //业务逻辑
         $openid=$xml_arr['FromUserName'];
        $u_info=DB::connection('test')->table('user_info')->where(['openid'=>$openid])->first();
        if(empty($u_info)){
            //根据openid和access-token拿到信息，存入table
        }
        $pre_time=$u_info->signin;
//        $d=date('Y-m-d H:i:s',$pre_time);
//        $start=strtotime('0:00:00');//今天的0：00
//          dd($start);
        $today=date('Y-m-d',time());


        $tools = new Tools();
        if($xml_arr['MsgType']=='event' && $xml_arr['Event']=='subscribe'){
            $wx_info=DB::connection('wechat')->table('user_weixin')->where(['openid'=>$openid])->first();
            $user_info=file_get_contents('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$tools->get_access_token().'&openid='.$openid.'&lang=zh_CN');
            $u_info=json_decode($user_info,1);
            $name=$u_info['nickname'];
//            dd($info);
            if(!$wx_info){
                DB::connection('myshop')->table('user_weixin')->insert([
                    'openid'=>$openid,
                    'nickname'=>$u_info['nickname'],
                    'city'=>$u_info['city'],
                    'country'=>$u_info['country'],
                    'add_time'=>time()
                ]);
                $message = '您好，' . $name.'。当前时间为：'.date('Y-m-d H:i:s',time());
                $xml_str = '<xml><ToUserName><![CDATA[' . $xml_arr['FromUserName'] . ']]></ToUserName><FromUserName><![CDATA[' . $xml_arr['ToUserName'] . ']]></FromUserName><CreateTime>' . time() . '</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[' . $message . ']]></Content></xml>';
                echo $xml_str;
            }elseif($wx_info){
                $message = '欢迎回来，' . $name.'。当前时间为：'.date('Y-m-d H:i:s',time());
                $xml_str = '<xml><ToUserName><![CDATA[' . $xml_arr['FromUserName'] . ']]></ToUserName><FromUserName><![CDATA[' . $xml_arr['ToUserName'] . ']]></FromUserName><CreateTime>' . time() . '</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[' . $message . ']]></Content></xml>';
                echo $xml_str;
            }
        }
       
    }
        
  




  // if($xml_arr['MsgType'] == 'event'){
  //           if($xml_arr['Event'] == 'subscribe'){
  //               $share_code = explode('_',$xml_arr['EventKey'])[1];
  //               $user_openid = $xml_arr['FromUserName']; //粉丝openid
  //               //判断openid是否已经在日志表
  //               $wechat_openid = DB::connection('mysql_cart')->table('wechat_openid')->where(['openid'=>$user_openid])->first();
  //               if(empty($wechat_openid)){
  //                   DB::connection('mysql_cart')->table('user')->where(['id'=>$share_code])->increment('share_num',1);
  //                   DB::connection('mysql_cart')->table('wechat_openid')->insert([
  //                       'openid'=>$user_openid,  
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
  //                       'add_time'=>time()
  //                   ]);
  //               }
  //           }
  //       }