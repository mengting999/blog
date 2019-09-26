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
        $xml_arr = (array)$xml_obj;
        dd($xml_arr);
        \Log::Info(json_encode($xml_arr,JSON_UNESCAPED_UNICODE));
        //echo $_GET['echostr'];
        //业务逻辑
        $message='欢迎关注';
        
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