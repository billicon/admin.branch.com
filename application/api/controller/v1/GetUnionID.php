<?php

namespace app\api\controller\v1;

use app\api\service\Token;
use app\api\service\WXBizDataCrypt;
use think\Cache;

class GetUnionID{
    public function get($encryptedData='',$iv='',$code='')
    {
        $appid = config('wx.app_id');
        $sessionKey = Cache::get($code);
        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);
        if ($errCode == 0) {
            $data = json_decode($data,true);
            $messageUser = [
                'city' => $data['city'],
                'province' => $data['province'],
                'openId' => $data['openId'],
                'unionId' => $data['unionId'],
                'nickName' => $data['nickName']
            ];
            $messageUser=json_encode($messageUser);
            $token = Token::generateToken();
            Cache::set($token,$messageUser,259200);
            $result['noUnion'] = true;
            $result['token'] = $token;
            return json($result);

        }
    }

}
