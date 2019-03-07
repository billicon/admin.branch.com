<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2019-02-02
 * Time: 15:27
 */

namespace app\api\controller\v1;


use app\api\service\GetClassOrderStatus;

class GetOrderList
{
    public function init($userid=''){
        $result = $this->getOrderlist(1,$userid);
        $num = $result['data']['TotalCount'];
        $num = ceil($num/20);
        for($i=0;$i<$num;$i++){
            $page = $i + 1;
            $resultxh = $this->getOrderlist($page,$userid);
            if($resultxh){
                $resultarr[] = $resultxh['data']['Items'];
            }
        }
        if(isset($resultarr['0'])){
            $resultarr = $resultarr['0'];
            $resultfina = [];
            foreach($resultarr as $key => $value){
                $times = strtotime($value['CreateTime']);
                $resultarr[$key]['CreateTime'] = date('Y-m-d h:i:s',$times);
                $resultarr[$key]['product'] = json_decode($this->getOrderDetail($value['OrderID']),true);
//            if(($value['OrderStatus'] == '0' || $value['OrderStatus'] == '1') && $resultarr[$key]['product']['coursestatus'] == 5 ){
//                $resultfina[$value['OrderID']] = $resultarr[$key];
//            }
                if(($value['OrderStatus'] == '0' || $value['OrderStatus'] == '1') ){
                    $resultfina[$value['OrderID']] = $resultarr[$key];
                }
            }
            return json($resultfina);
        }
        else{
            return 1;
        }
    }
    public function getOrderlist($page,$userid){
//      $url = 'https://order.wendu.com/orderapi/searchorderlist';
        $url = 'http://ordertest.wendu.com/orderapi/searchorderlist';
        $time = date('YmdHis',time());
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
					  			"PageIndex":"'.$page.'",
								"AppProductID":["PL10012"],
								"Platform":"6",
								"UserID":"'.$userid.'"
								}
	         }';
        $result = curl_post_https($url,$data);
        $result = json_decode($result,true);
        return $result;
    }
    function getOrderDetail($orderid){
//      $url = 'https://order.wendu.com/OrderApi/GetOrderDetails';
        $url = 'http://ordertest.wendu.com/orderapi/GetOrderDetails';
        $time = date('YmdHis',time());
        $parmStr = "appid=100014&platform=2&time=$time&ver=1.0";
        $key = get_appkeys('100014','3');
        $sign = getSign($parmStr,$key);
        $data = '{
                          "appid":"100014",
                          "platform": "2",
                          "sign": "'.$sign.'",
                          "time": "'.$time.'",
                          "ver": "1.0",
                          "data":{
                                   "apporderid":"'.$orderid.'"
                                    }
                 }';
        $result = curl_post_https($url,$data);
        $result = json_decode($result,true);
        $result = $result['data']['Products']['0'];
        $coursestatus = GetClassOrderStatus::init($result['ProductID']);
        $result['coursestatus'] = $coursestatus;
        $timestart  = strtotime($result['ProductValidBegin']);
        $timeend  = strtotime($result['ProductValidEnd']);
        $result['ProductValidBegin'] = date('Y-m-d h:i:s',$timestart);
        $result['ProductValidEnd'] = date('Y-m-d h:i:s',$timeend);
        return json_encode($result);
    }
}