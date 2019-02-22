<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2019-02-12
 * Time: 11:39
 */

namespace app\api\controller\v1;


use think\Cache;

class Pay
{
    public function getPay($userid='',$courseid='',$token=''){
        $data = Cache::get($token);
        $data = json_decode($data,true);
        $openid = $data['openId'];
        $time = date('YmdHis',time());
        $parmStr = "appid=100014&platform=6&time=$time&ver=1.1";
        $key = get_appkeys('100014','3');
        $sign = getSign($parmStr,$key);
//        $url = 'https://order.wendu.com/orderapi/createorder';
        $url = 'http://ordertest.wendu.com/orderapi/createorder';
        $data = '{
                      "appid": "100014",
                      "platform": "6",
                      "time": "'.$time.'",
                      "ver": "1.1",
                      "sign": "'.$sign.'",
                      "data":{
                          "AppID":"100011",
                          "AppProductID":"PL10012",
                          "NotifyAppID":"100011,100014",
                          "UserID":"'.$userid.'",
                          "OrderType":"1",    
                          "Scene":"2",
                          "ChannelID":"1",
                          "Product":[{
                              "ProductID":"'.$courseid.'",
                              "Count": "1"
                          }],
                         "BackUrl":"/pages/detail/detail",
                         "OpenID":"'.$openid.'"
                        }
                   }';
        $result = curl_post_https($url,$data);
        return $result;
    }
    public function getParameters($userid='',$orderid='',$token='',$ip='121.69.40.110'){
        $data = Cache::get($token);
        $data = json_decode($data,true);
        $openid = $data['openId'];
//        $openid = "oofIQ5a9K__BqijEQHdLFMLxV3SU";
        $time = date('YmdHis',time());
        $parmStr = "appid=100014&platform=6&time=$time&ver=1.0";
        $key = get_appkeys('100014','3');
        $sign = getSign($parmStr,$key);
        $url = 'http://124.207.139.222:39180/paycenter/WechatApi/MiniProgramPay';
        $data = '{
                      "appid": "100014",
                      "platform": "6",
                      "time": "'.$time.'",
                      "ver": "1.0",
                      "sign": "'.$sign.'",
                      "data":{
                          "AppID":"100011",
                          "AppOrderID":"'.$orderid.'",
                          "OpenID":"'.$openid.'",
                          "BuyerIP":"'.$ip.'",
                          "Native":"0",    
                          "WDUserID":"'.$userid.'"
                        }
                   }';
        $result = curl_post_https($url,$data);
        return $result;
    }
}

