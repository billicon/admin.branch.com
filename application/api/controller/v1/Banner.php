<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2019-01-30
 * Time: 17:54
 */

namespace app\api\controller\v1;


use app\api\model\Poster;
use app\api\model\PosterSpace;

class Banner
{
    public function getBanner()
    {
        $result = PosterSpace::getspaceid();
        $spaceid = $result->spaceid;
        $posterarr = Poster::getBannerBySpaceid($spaceid);
        foreach ($posterarr as $key => $value)
        {
            $posterarr[$key] = $value->toArray();
            $posterarr[$key] = json_decode($posterarr[$key]['setting'],true)['1'];

        }
        return json($posterarr);
    }
}