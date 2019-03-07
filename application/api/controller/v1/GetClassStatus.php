<?php
/**
 * Created by PhpStorm.
 * User: zhangjun
 * Date: 2019-02-15
 * Time: 18:38
 */

namespace app\api\controller\v1;


use app\api\service\GetClassOrderStatus;

class GetClassStatus
{

    public function init($courseid='712'){
            $result = GetClassOrderStatus::init($courseid);
    }
}