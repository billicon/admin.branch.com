<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2019-01-30
 * Time: 18:21
 */

namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\validate\TokenGet;
use think\Cache;

class LoginWx
{
    public function getToken($code=''){
        (new TokenGet()) -> gocheck();
        $ut = new UserToken($code);
        $result = $ut->get();
        Cache::set($code,$result['session_key'],259200);
        if(!array_key_exists('UnionID',$result)){
             $result = null;
             $result['noUnion'] = false;
             return json($result);
        }else{
            $result['noUnion'] = true;
            return json($result);
        }
    }
}
