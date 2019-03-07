<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2019-02-19
 * Time: 17:43
 */

namespace app\api\model;


use think\Model;

class Poster extends Model
{
    public static function getBannerBySpaceid($spaceid){
        $bannerarr = self::where('spaceid','=',$spaceid)
            ->select();
        return $bannerarr;
    }
}