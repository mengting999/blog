<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use App\Tools\Tools;
class KaoshiController extends Controller
{
	public  $tools;
    public function __construct(Tools $tools)
    {
        $this->tools = $tools;
    }

    public function etc()
    {
    	 $xml_string = file_get_contents('php://input');  //获取
        // dd($xml_string);
        $wechat_log_psth = storage_path('logs/wechat/'.date('Y-m-d').'.log');
        file_put_contents($wechat_log_psth,"<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n",FILE_APPEND);
        file_put_contents($wechat_log_psth,$xml_string,FILE_APPEND);
        file_put_contents($wechat_log_psth,"\n<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<\n\n",FILE_APPEND);
        // dd($xml_string);
        $xml_obj = simplexml_load_string($xml_string,'SimpleXMLElement',LIBXML_NOCDATA);
        dd($xml_obj);
        $xml_arr = (array)$xml_obj;
        $wechat_log_psth = storage_path('logs/wechat/'.date('Y-m-d').'.log');
         $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->tools->get_wechat_access_token().'&openid=';
         // dd($url);
    }

}
