<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2019-02-18
 * Time: 11:37
 */

namespace app\api\service;


class GetClassOrderStatus
{
    public static function init($courseid){
        //         $url = 'http://liveapi.wendu.com/Course/Fetch';
        $url = 'http://172.168.1.78:8557/Course/Fetch';
        $time = date('YmdHis',time());
        $parmStr = "appid=100014&platform=3&time=$time&ver=1.0";
        $key = get_appkeys('100014','3');
        $sign = getSign($parmStr,$key);
        $data = '{
                "data": "'.$courseid.'",
                "appid": "100014",
                "platform": "3",
                "time": "'.$time.'",
                "ver": "1.0",
                "sign": "'.$sign.'"
            }';
        $result = curl_post_https($url,$data);
        $result = json_decode($result,true);
        $result = $result['data'][0]['Status'];
        return $result;
    }
}