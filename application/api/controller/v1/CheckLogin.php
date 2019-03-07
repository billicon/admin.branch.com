<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2019-02-15
 * Time: 14:54
 */

namespace app\api\controller\v1;


use think\Cache;

class CheckLogin
{
        public function init($token){
            $data = Cache::get($token);
            if($data){
                return 1;
            }else{
                return 2;
            }
        }
        public function changeTime($token){
            $data = Cache::get($token);
            if($data) {
                Cache::set($token,$data,259200);
            }
        }
}