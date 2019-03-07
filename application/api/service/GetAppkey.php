<?php
namespace app\api\service;
use think\Cache;

/**
 * by zj 2018-12-12
 * 获取APPID对应配置中心的key
 */
class GetAppkey{

    /**
     * 获取中小学移动站的key
     * @param $appid string
     * @param $platform int (1-APP 2-H5 3-WEB)
     * @return string key
     */
    public static function xcx_wdzxx($appid, $platform){

        $private_key = '-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQC4uEJJyNbiwZ++aXtsP3mzDBvGGqH4x/vpTdw/0IK+1R
/FQYtor5kAdNRRXgHtMKe+ICGSdbwCg7NhmV6HOn7f+1wK88BMP1sE6rrXe
pc7O+J67MKWSeCn9DoO1jI3K+cd05NmYp9NwuE4SJT755HxdstuSHn59Nd
GEJ76UBP2awIBAwKBgB7JYGGheSXK7/URlJIKlEiCBKEEcFQhVKbiT1/4F
cp42qDgQebH7tVozg2PqvzdcUpasEMTn1XAneWZj8E0anphZ8qkHNQHls/
zi4bRCs/IK3L6ZrPhxgnPSO0zJTFx4YrMKQhdLIk9XaYK9p8Rw8nNAZN
ly9CEO4vnmv+XC/pHAkEA2umLZgEI5clx1WhyHU3kWvu6tyF3/lHJFCs3j1E
f3BEmg5+EQlKgIQaJ4OcMieMPxQviJ24YREMv+w+a+I/15wJBANgDv7USSyw
Is2ANPHcIeC/iDlc45wDqpARZSBQB7qSMtob9r/FBdjGoyiXmNPMYJuO2F75
E/pcuzqftYc08It0CQQCR8QeZVgXuhkvjmva+M+2R/SckwPqpi9tix3pfi2q
SthmtFQLW4cAWBFvrRLMGl1/YspbE9BAtgh/8tRH7CqPvAkEAkAJ/zgwyHVs
iQAjS+gWldUFe5NCaAJxtWDuFYqv0bbMkWf51S4D5dnCGw+7N92VvQnllKYN
UZMnfGp5BM31skwJBAJZOn9ylLw+X9IgROuhursTx4Dyinhr0g2KESTNUOjX
lazL5MH3MI0P7SaC1f5ISm2hdAvcObGgzz/KDn89SgAg=
-----END RSA PRIVATE KEY-----';
        return self::get($appid, $platform, $private_key);
    }

    /**
     * 获取key（缓存存在取缓存数据，否则重新获取）
     * @param $appid
     * @param $platform
     * @param $private_key
     * @return mixed|string
     */
    public static function get($appid, $platform, $private_key){
        if(!$appid || !$platform) return '';

        $data = Cache::get('appkey');
        if(!$data){
            $url = "https://conf.wendu.com/api/app/bootparam?appid=". $appid ."&platform=". $platform;
            $data = file_get_contents($url);
            $data = json_decode($data, true);
            if ($data['code'] == 200 && $data['resultcode'] == 1) {
                $encrypted = $data['data']['param1'];
                $key = '';
                // 私钥解密
                openssl_private_decrypt(base64_decode($encrypted), $key, $private_key);
                // 设置缓存
                Cache::set('appkey',$key,'7200');
                return $key;
            } else {
                return '';
            }
        }else{
            return $data;
        }
    }
}




