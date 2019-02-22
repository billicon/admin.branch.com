<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2019-02-15
 * Time: 15:48
 */

namespace app\api\controller\v1;


class CancelOrder
{
     public function init($userid,$orderid){
//         $url = 'https://order.wendu.com/OrderApi/ModifyOrderStatus';
         $url = 'http://ordertest.wendu.com/OrderApi/ModifyOrderStatus';
         $time = date('YmdHis',time());
         $timedate = date('Y-m-d H:i:s',time());
         $parmStr = "appid=100014&platform=6&time=$time&ver=1.0";
         $key = get_appkeys('100014','3');
         $sign = getSign($parmStr,$key);
         $data = '{
	         		  "appid":"100014",
					  "platform": "6",
					  "sign": "'.$sign.'",
					  "time": "'.$time.'",
					  "ver": "1.0",
					  "data":{
					  			"AppID":"100011",
								"RequestNum":"zxxxcx",
								"OrderID":"'.$orderid.'",
								"Status":"5",
								"PreStatus":"0",
								"UserID":"'.$userid.'",
								"OperID":"'.$userid.'",
								"OperName":"'.$userid.'",
								"UpdateTime":"'.$timedate.'"
								
								}
	         }';
         $result = curl_post_https($url,$data);
         $result = json_decode($result,true);
         if($result['msg'] == 'success'){
             return 1;
         }
     }
}