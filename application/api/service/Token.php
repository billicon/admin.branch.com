<?php
/**
 * Created by PhpStorm.
 * User: zhangjun1903
 * Date: 2019/1/18
 * Time: 18:32
 */
namespace app\api\service;

use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token{
    public static function generateToken(){
        //32个字符组成一组随机字符串
        $randChars = getRandChars(32);
        //用三组字符串,进行md5加密，把加密过后的字符串返回
        $time = $_SERVER['REQUEST_TIME_FLOAT'];
        //salt 盐
        $salt = config('secure.token_salt');
        return md5($randChars.$time.$salt);
    }
    public static function getCurrentTokenVar($key){
            $token = Request::instance()->header('token');
            $vars = Cache::get($token);
            if(!$vars){
                throw new TokenException();
            }else{
                if(!is_array($vars)){
                    $vars = json_decode($vars,true);
                }
                if(array_key_exists($key,$vars)){
                    return $vars[$key];
                }else{
                    throw new Exception('尝试获取的Token变量并不存在');
                }
            }
    }
    public static function getCurrentUid(){
        //token
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

}