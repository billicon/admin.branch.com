<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2019-01-31
 * Time: 17:59
 */

namespace app\api\controller\v1;


use think\Cache;

class GetLoginUrl
{
    public function getUrl($token,$backurlcheck = ''){
            $data = Cache::get($token);
            $data = json_decode($data,true);
            $url = 'http://passport.wenduedu.com/user/weixinsmallprogramlogin';
            if($backurlcheck == 'details'){
                $loginurl = '/pages/detail/detail';
            }else{
                $loginurl = '/pages/center/center';

            }
            $param = urlencode(base64_encode('{"AppId":100016,"Platform":6,"AppProvince":
            "'.$data['province'].'","AppCity":"'.$data['city'].'","Franchisee":"加盟商","ThreeAppId":
            "'.config('wx.app_id').'","UnionId":"'.$data['unionId'].'","OpenId":"'.$data['openId'].'"
            ,"OtherName":"'.$data['nickName'].'"}'));
            $url = $url."?LoginUrl=".$loginurl."&ParamBody=".$param."&Source=142";
            return json($url);
    }
}

