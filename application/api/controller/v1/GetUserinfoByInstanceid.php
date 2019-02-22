<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2019-02-01
 * Time: 14:02
 */

namespace app\api\controller\v1;


use think\Cache;

class GetUserinfoByInstanceid
{
    public function get($instanceid,$token){
        $url = 'http://passport.wenduedu.com/user/GetUserByInstanceId?instanceid='.$instanceid;
        $result = file_get_contents($url);
        $data = Cache::get($token);
        $data = json_decode($data,true);
        $resultarr = json_decode($result,true);
        $data['userid'] = $resultarr['Data']['UserId'];
        $data = json_encode($data);
        Cache::set($token,$data,259200);
        return $result;
    }
}