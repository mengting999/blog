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
//        dd($_POST);微信传过来的数据是postman->raw的格式，大POST是拿不到的
//        echo $_GET['echostr'];//这个玩意是在第一次设置接口配置信息时需要在url里echo一下？echostr=111以表示微信返回给开发者的接口可以用
        $xml_string=file_get_contents('php://input');//微信返回的格式是xml字符串，用它来获取到
//        dd($xml_string);
//        $wechat_log_path=storage_path('logs/wechat/'.date('Y-m-d').'.log');
        $wechat_log_path=storage_path('logs/wx.log');

        file_put_contents($wechat_log_path,"<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n",FILE_APPEND);
        file_put_contents($wechat_log_path,$xml_string,FILE_APPEND);
        file_put_contents($wechat_log_path,"\n<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n\n",FILE_APPEND);
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //$re=file_put_contents(storage_path('logs/wechat/'.date('Y-m-d').'.log'),'123234545',FILE_APPEND);//
        //注释;咱用这个做，咱们每次接收的数据写入这里面。如果没有这个文件，他是会自己生成的。他的执行模式是覆盖写入。若写入第三个参数mode,会执行相关操作，file_append是追加（不覆盖之前内容），这是一个文件指针(这里指向了末尾)，相关内容：指针和偏移
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////
        /// 咱们做的效果，就是做的跟laravel日志一样，蛋直接放在storage->logs里有些许不合适,所以咱创建一个wechat,咱一天存一个,咱们往里面写的内容，就是$xml_string,不能在一个地方写，咱们应该换行，所以写一个标记
        $xml_obj=simplexml_load_string($xml_string,'SimpleXMLElement',LIBXML_NOCDATA);   //他是把xml转成咱们php能识别的对象.LIBXML_NOCDATA,将 CDATA 设置为文本节点(咱接收的xml解析不了cdata,所以直接百度出来这个的东西)，还差第二个参数classname，规定新对象的 class。,根据定义simplexml_load_string() 函数转换形式良好的 XML 字符串为 SimpleXMLElement 对象，应该就是SimpleXMLElement
//        dd($xml_obj);
        $xml_arr=(array)$xml_obj;//强制类型转换:obj->array
//        dd($xml_arr);
        \Log::Info(json_encode($xml_arr,JSON_UNESCAPED_UNICODE));//又写了一个laravel日志，他会不会与别的混了呢
        //业务逻辑

            //$d=date('Y-m-d,H:i:s',$start);今天凌晨时间

        /////判 断 是 否 应 该 签 到
        $openid=$xml_arr['FromUserName'];
        $u_info=DB::connection('test')->table('wechat_openid')->where(['openid'=>$openid])->first();
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
            $wx_info=DB::connection('myshop')->table('user_weixin')->where(['openid'=>$openid])->first();
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