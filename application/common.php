<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

 function getRandChars($length){
        $str = null;
        $strPol = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghigklmnopqrstuvwxyz';
        $max = strlen($strPol) -1;
        for($i=0;$i<$length;$i++){
            $str .= $strPol[rand(0,$max)];
        }
        return $str;
 }
 function getcurlarr($url){
           $ch = curl_init();
           curl_setopt($ch, CURLOPT_URL, $url);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
           curl_setopt($ch, CURLOPT_HEADER, true);
           curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
           $data = curl_exec($ch);
           return $data;
 }
/*签名认证*/
function getSign($parmStr,$key){
    //校验签名
    $paramArr = explode('&',$parmStr);
    //对参数按ansic码进行重新排序
    $ansiParamArr = ansiSortParamArr($paramArr);
    //拼串处理
    $ansiParamStr = implode('&',$ansiParamArr);
    $sign = strtolower(md5($ansiParamStr.$key));

    return  $sign;
}
function ansiSortParamArr($paramArr){
    if(is_array($paramArr)){
        $paramArr1 = array();

        foreach($paramArr as $key =>$value){
            $key1Arr = explode('=',$value);
            $key1 = $key1Arr[0];
            $paramArr1[] = strtolower($key1);

        }
    }
    $paramArr2 = array_combine($paramArr1,$paramArr);
    ksort($paramArr2);
    unset($paramArr2['sign']);
    return $paramArr2;
}
/*
*https post请求
*/
  function curl_post_https($url,$data){ // 模拟提交数据函数
      $curl = curl_init(); // 启动一个CURL会话
      curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
      curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
      curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
      curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
      curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
      curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
      curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
      curl_setopt($curl, CURLOPT_HTTPHEADER, array(
              'Content-Type: application/json; charset=utf-8',
              'Content-Length: ' . strlen($data))
      );
      $tmpInfo = curl_exec($curl); // 执行操作
      if (curl_errno($curl)) {
          echo 'Errno'.curl_error($curl);//捕抓异常
      }
      curl_close($curl); // 关闭CURL会话
      return $tmpInfo; // 返回数据，json格式
  }
//获取中小学的appkey
function get_appkeys($appid,$platform){
    return \app\api\service\GetAppkey::xcx_wdzxx($appid,$platform);
}
function curl_get_http($url){
    $ch = curl_init();
    $timeout = 5;
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $file_contents = curl_exec($ch);
    curl_close($ch);
    echo $file_contents;
}