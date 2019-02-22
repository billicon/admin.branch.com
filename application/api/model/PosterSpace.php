<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2019-02-19
 * Time: 17:20
 */

namespace app\api\model;


use think\Model;

class PosterSpace extends Model
{

    public static function getspaceid(){
        $spaceidarr = self::where('siteid','=','9')
            ->find();
        return $spaceidarr;
    }
}