<?php
namespace app\api\controller\v1;

class Course{

    public $url;
    public $key;

    public function __construct(){
        $this->url = 'http://172.168.1.78:8557/';
        // $this->url = 'http://liveapi.wendu.com/';
        $this->key = get_appkeys('100014','3');
    }

    // 获取直播课程（带分页）
    public function getLiveCoursesWithPage(){
//        $_POST = $this->delXss($_POST);
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 10;
        $requestUrl = $this->url.'Course/FetchPage';
        $data = $this->getData();
        $sign = $this->getSign($data, $this->key);
        $data['sign'] = $sign;
        if(!isset($_POST['type'])){
            $_POST['type'] = 4;
        }
        // 获取课程类型
        $typeData = $this->getCourseType($_POST['type']);
        $data['data'] = [
            "Name" => "",
            "OrganizationId" => "",							// 机构id
            "CategoryId" => $typeData['CategoryId'],		// 1考研 2四六级 3医考 4公考 5教资 6建考 7中小学 8出国留学 9法考
            "Status" => $typeData['Status'],				// 1草稿 2待审批 3审批通过 4审批未通过 5上线 6问题下架 7系统下架 8已删除
            "ExcId" => "",									// 排除的课程id
            "Id" => "",
            "PageNumber" => $page,
            "PageSize" => $pageSize,
            "QUseActiveCode" => "",
            "Sorts" => $typeData['Sorts'],					// 排序，支持onlinetime（上线时间） createtime（创建时间）startdate（开课时间）排序，默认onlinetime desc
            "UseCourseSeq" => 0,							// 是否启用课程顺序功能 1启用，0不启用，启用则优先按照课程顺序排序，再使用Sorts里的值排序
            "SaleType" => $typeData['SaleType'],			// 销售类型，0全部， 1付费 ， 2免费
            "StartRange" => 0								// 开课范围，0全部， 1未开课 ， 2已开课
        ];
        // 发送post请求
        $result = curl_post_https($requestUrl, json_encode($data));
        $return = json_decode($result, true);

        if($return['code'] == 200 && $return['resultcode'] == 1){
            $jsonData['data']['TotalPageCount'] = $return['data']['TotalPageCount'];
            $jsonData['data']['TotalCount'] = $return['data']['TotalCount'];
            foreach($return['data']['Courses'] as $k => $v){
                $jsonData['data']['Courses'][$k]['id'] = $v['Id'];
                $jsonData['data']['Courses'][$k]['img'] = $v['CourseImage'];
                $jsonData['data']['Courses'][$k]['getNum'] = $v['CurBuyCount'];
                $jsonData['data']['Courses'][$k]['price'] = $v['RealPrice'];
                $jsonData['data']['Courses'][$k]['priceStr'] = $v['RealPriceStr'];
            }
            unset($return);
            $jsonData['code'] = 1;
            $jsonData['message'] = '成功';
            echo json_encode($jsonData);die;
        }else{
            echo json_encode(array('code' => 0, 'message' => '获取直播课程失败'));die;
        }
    }

    // 获取直播课程详情
    public function getLiveCourseDetail(){
//        $_POST = $this->delXss($_POST);
        $requestUrl = $this->url.'coursecenter/detail';

        $data = $this->getData();
        $sign = $this->getSign($data, $this->key);
        $data['sign'] = $sign;
        $data['data'] = [
            'id' => $_POST['id'],
            'userid' => $_POST['userid'] ? $_POST['userid'] : 0
        ];

        $result = curl_post_https($requestUrl, json_encode($data));
        $return = json_decode($result, true);
        if($return['code'] == 200 && $return['resultcode'] == 1){
            // 获取课程介绍
            $courseData = curl_post_https($this->url.'Course/Memo', json_encode(array('id' => $_POST['id'])));
            $courseData = json_decode($courseData, true);
            foreach($return['data'] as $k => $v){
                $jsonData['data']['id'] = $v['Id'];
                $jsonData['data']['name'] = $v['Name'];
                $jsonData['data']['img'] = $v['CourseImage'];
                $jsonData['data']['closeOfTime'] = $v['StopSaleCountDown'];
                $jsonData['data']['originalPriceStr'] = $v['PriceStr'];
                $jsonData['data']['originalPrice'] = $v['Price'];
                $jsonData['data']['presentPrice'] = $v['RealPrice'];
                $jsonData['data']['presentPriceStr'] = ($v['RealPriceStr'] == '免费') ? '限时免费' : $v['RealPriceStr'];
                $jsonData['data']['isReceive'] = $v['IsOwner'];
                $jsonData['data']['introduction'] = str_replace('<img', '<img style="width:100%;height:auto;display:block"', $courseData['Result']);
            }
            unset($return);
            $jsonData['code'] = 1;
            $jsonData['message'] = '成功';
            echo json_encode($jsonData);die;
        }else{
            echo json_encode(array('code' => 0, 'message' => '获取直播课程详情失败'));die;
        }
    }

    // 获取课程目录
    public function getCourseCatalog(){
//        $_POST = $this->delXss($_POST);
        $requestUrl = $this->url.'Course/NodeTree';

        $data = $this->getData();
        $sign = $this->getSign($data, $this->key);
        $data['sign'] = $sign;
        $data['id'] = $_POST['id'];
        $result = curl_post_https($requestUrl, json_encode($data));
        $return = json_decode($result, true);
        foreach($return['Result']['Children'] as $k => $v){
            foreach($v['LessonList'] as $kk => $vv){
                $return['Result']['Children'][$k]['LessonList'][$kk]['StartTime'] = substr($vv['StartTime'], 0, -4);
                $return['Result']['Children'][$k]['LessonList'][$kk]['EndTime'] = substr($vv['EndTime'], 0, -4);
            }
        }

        if($return['StatusCode']){
            $return['code'] = 1;
            $return['message'] = '成功';
            echo json_encode($return);die;
        }else{
            echo json_encode(array('code' => 0, 'message' => '获取课程目录失败'));die;
        }
    }

    // 获取学员评价（带分页）
    public function getCommentsWithPage(){
//        $_POST = $this->delXss($_POST);
        $lastId = isset($_POST['lastId']) ? $_POST['lastId'] : 0;
        $pageSize = isset($_POST['pageSize']) ? $_POST['pageSize'] : 5;
        $requestUrl = $this->url.'UserComment/PageForCourse';

        $data = $this->getData();
        $sign = $this->getSign($data, $this->key);
        $data['sign'] = $sign;
        $data['CourseId'] = $_POST['id'];
        $data['lastId'] = $lastId;
        $data['pageSize'] = $pageSize;

        $result = curl_post_https($requestUrl, json_encode($data));
        $return = json_decode($result, true);
        foreach($return['Result']['DataList'] as $k => $v){
            $return['Result']['DataList'][$k]['UserName'] = $this->userNameReplace($v['UserName']);
        }

        if($return['StatusCode']){
            $return['code'] = 1;
            $return['message'] = '成功';
            echo json_encode($return);die;
        }else{
            echo json_encode(array('code' => 0, 'message' => '获取课程目录失败'));die;
        }
    }

    // 领取/购买直播课程
    public function receiveCourse(){
//        $_POST = $this->delXss($_POST);
        $requestUrl = $this->url.'coursecenter/receive';

        $data = $this->getData();
        $data['id'] = (int)$_POST['id'];
        $data['userid'] = (int)$_POST['userid'];
        $sign = $this->getSign($data, $this->key);
        $data['sign'] = $sign;
        if ($_POST['apptype'] == "devtools") {
            // PC
            $AppType = 'PC';
        } else if ($_POST['apptype'] == "ios") {
            // IOS
            $AppType = 'Iphone';
        } else if ($_POST['apptype'] == "android") {
            // android
            $AppType = 'Android';
        }
        $data['AppType'] = $AppType;

        $result = curl_post_https($requestUrl, json_encode($data));
        $return = json_decode($result, true);
        if($return['resultcode'] && $return['data']){
            $return['message'] = '成功';
            echo json_encode($return);die;
        }else{
            echo json_encode(array('code' => 0, 'message' => '领取直播课程失败'));die;
        }
    }

    // 获取直播课程结构
    public function getLessonList(){
//        $_POST = $this->delXss($_POST);
        $requestUrl = $this->url.'v2/ZhiBo/NodeList';

        $data = $this->getData();
        $data['userid'] = $_POST['userid'];
        $sign = $this->getSign($data, $this->key);
        $data['sign'] = $sign;
        $data['data']['id'] = $_POST['id'];

        $result = curl_post_https($requestUrl, json_encode($data));
        $return = json_decode($result, true);
        foreach($return['data'] as $k => $v){
            foreach($v['LessonList'] as $kk => $vv){
                $return['data'][$k]['LessonList'][$kk]['StartTime'] = substr($vv['StartTime'], 0, -4);
                $return['data'][$k]['LessonList'][$kk]['EndTime'] = substr($vv['EndTime'], 0, -4);
            }
        }

        if($return['code'] == 200 && $return['resultcode'] == 1){
            echo json_encode($return);die;
        }else{
            echo json_encode(array('code' => 0, 'message' => '获取直播课程结构失败'));die;
        }
    }

    // 获取直播/回放参数信息
    public function getLessonInfo(){
//        $_POST = $this->delXss($_POST);
        $requestUrl = $this->url.'v2/ZhiBo/Lesson';

        $data = $this->getData();
        $data['userid'] = (int)$_POST['userid'];
        $sign = $this->getSign($data, $this->key);
        $data['sign'] = $sign;
        $data['data']['CourseId'] = (int)$_POST['courseId'];
        $data['data']['LessonId'] = (int)$_POST['lessonId'];

        $result = curl_post_https($requestUrl, json_encode($data));
        $return = json_decode($result, true);

        // 获取课时介绍
        $requestUrl = $this->url.'v2/ZhiBo/LessonMemo';
        $result = curl_post_https($requestUrl, json_encode($data));
        $introduction = json_decode($result, true);
        $return['Result']['introduction'] = $introduction;

        if($return['code'] == 200 && $return['resultcode'] == 1){
            echo json_encode($return);die;
        }else{
            echo json_encode(array('code' => 0, 'message' => '获取参数信息失败'));die;
        }
    }

    // 获取课程类型
    private function getCourseType($type){
        switch($type){
            case 1:	// 免费好课
                $data = [
                    "CategoryId" => "7",
                    "SaleType" => 2,
                    "Status" => "5",
                    "Sorts" => "CreateTime desc",
                ];
                break;
            case 2:	// 自主招生
                $data = [
                    "CategoryId" => "10",
                    "SaleType" => 0,
                    "Status" => "5",
                    "Sorts" => "CreateTime desc",
                ];
                break;
            case 3:	// 志愿填报
                $data = [
                    "CategoryId" => "11",
                    "SaleType" => 0,
                    "Status" => "5",
                    "Sorts" => "CreateTime desc",
                ];
                break;
            case 4:	// 精品直播
                $data = [
                    "CategoryId" => "7",
                    "SaleType" => 1,
                    "Status" => "5",
                    "Sorts" => "CreateTime desc",
                ];
                break;
            default:
                // 精品直播
                $data = [
                    "CategoryId" => "7",
                    "SaleType" => 1,
                    "Status" => "5",
                    "Sorts" => "CreateTime desc",
                ];
                break;
        }
        return $data;
    }

    // 获取待签名数据
    private function getData(){
        // return [
        // 	'appid' => isset($_GET['appid']) ? $_GET['appid'] : '100014',
        // 	'platform' => isset($_GET['platform']) ? $_GET['platform'] : '6',
        // 	'time' => isset($_GET['time']) ? $_GET['time'] : date('YmdHis',time()),
        // 	'ver' => isset($_GET['ver']) ? $_GET['ver'] : '1.0.0'
        // ];
        return [
            'appid' => isset($_POST['appid']) ? $_POST['appid'] : '',
            'platform' => isset($_POST['platform']) ? $_POST['platform'] : '',
            'time' => isset($_POST['time']) ? $_POST['time'] : '',
            'ver' => isset($_POST['ver']) ? $_POST['ver'] : ''
        ];
    }

    /**
     * @param $data array   需要验签的数据
     * @param $key  string  32位密钥key
     * @return string sign值
     */
    private function getSign($data, $key){
        if (is_array($data)) {
            $paramArr = [];
            ksort($data);
            foreach ($data as $k => $value) {
                $paramArr[] = $k.'='.$value;
            }
            $paramStr = implode('&', $paramArr);
        }

        $sign = strtolower(md5($paramStr.$key));

        return $sign;
    }

    // 学员评价用户账号替换
    // 1.手机号，隐藏中间4位变为“186****2223”。
    // 2.邮箱，“@”之前的用户名，隐藏离@最近的4位，
    // 如果“@”之前的用户名小于等于4位，那么全部隐藏为“*”；例如：“xx5****@21cn.com;***@21cn.com”
    private function userNameReplace($string)
    {
        // 15964568459
        // 3243212343@qq.com.cn
        $newStr = '';
        if(is_numeric($string)){
            if(strlen($string) == 11){
                $newStr = preg_replace('/(\d{3})\d{4}(\d{4})/', '$1****$2', $string);
            }else{
                $newStr = $string;
            }
        }else{
            if(strrpos($string, '@') !== false){
                $arr = explode('@', $string);
                if($arr[0] <= 4){
                    $newStr .= '****@'.$arr[1];
                }else{
                    $newStr .= substr($arr[0], 0, -4).'****@'.$arr[1];
                }
            }else{
                $newStr = $string;
            }
        }

        // userNameHide:function(string){
        // 	var reg= /[0-9]]/;
        // 	if (reg.test(string)){
        // 		// 数字
        // 		if(string.length == 11){
        // 			var newStr = string.replace(/(\d{3})\d{4}(\d{4})/, '$1****$2');
        // 			return newStr;
        // 		}else{
        // 			return string;
        // 		}
        // 	}else{
        // 		// 判断是否有@
        // 		if(string.indexOf('@')){
        // 			var arr = string.split('@');
        // 			if(arr[0].length <= 4){
        // 				var newStr = '****@' + arr[1];
        // 			}else{
        // 				var newStr = arr[0].replace(/(\d{1,})(\d{4})/, '$1****@') + arr[1];
        // 			}
        // 			return newStr;
        // 		}else{
        // 			return string;
        // 		}
        // 	}
        // }
        return $newStr;
    }

    /**
     * [delXss 安全过滤]
     * @param  [array] $arr [$_GET]
     * @return [array]      [$_GET]
     */
    private function delXss($arr)
    {
        foreach ($arr as $k => $v) {
            $v = $this->remove_xss($v);
            $v = $this->safe_replace(trim($v));
            $v = $this->new_html_special_chars(strip_tags($v));
            $v = str_replace('%', '', $v);
            $arr[$k] = $v;
        }
        return $arr;
    }

    /**
     * xss过滤函数
     *
     * @param $string
     * @return string
     */
    private function remove_xss($string) {
        $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);

        $parm1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');

        $parm2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

        $parm = array_merge($parm1, $parm2);

        for ($i = 0; $i < sizeof($parm); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($parm[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[x|X]0([9][a][b]);?)?';
                    $pattern .= '|(&#0([9][10][13]);?)?';
                    $pattern .= ')?';
                }
                $pattern .= $parm[$i][$j];
            }
            $pattern .= '/i';
            $string = preg_replace($pattern, ' ', $string);
        }
        return $string;
    }

    /**
     * 安全过滤函数
     *
     * @param $string
     * @return string
     */
    function safe_replace($string) {
        $string = str_replace('%20','',$string);
        $string = str_replace('%27','',$string);
        $string = str_replace('%2527','',$string);
        $string = str_replace('*','',$string);
        $string = str_replace('"','&quot;',$string);
        $string = str_replace("'",'',$string);
        $string = str_replace('"','',$string);
        $string = str_replace(';','',$string);
        $string = str_replace('<','&lt;',$string);
        $string = str_replace('>','&gt;',$string);
        $string = str_replace("{",'',$string);
        $string = str_replace('}','',$string);
        $string = str_replace('\\','',$string);
        return $string;
    }

    /**
     * 返回经htmlspecialchars处理过的字符串或数组
     * @param $obj 需要处理的字符串或数组
     * @return mixed
     */
    function new_html_special_chars($string) {
        $encoding = 'utf-8';
        if(strtolower(CHARSET)=='gbk') $encoding = 'ISO-8859-15';
        if(!is_array($string)) return htmlspecialchars($string,ENT_QUOTES,$encoding);
        foreach($string as $key => $val) $string[$key] = new_html_special_chars($val);
        return $string;
    }

    // // 直播间h5页面
    // public function forPolyvLive(){
//    // 	$_POST = $this->delXss($_POST);
    // 	$requestUrl = 'http://playertest.wendu.com/h5/live?';
    // 	$data = $this->getData();
    // 	$data['userid'] = $_POST['userid'];
    // 	$data['param1'] = $_POST['livevid'];
    // 	$sign = $this->getSign($data, $this->key);
    // 	$data['sign'] = $sign;
    // 	$data['vc'] = 3; // 保利
    // 	$data['param2'] = $_POST['liveuid'];
    // 	$data['roomname'] = '页面标题sdsa';
    // 	// var_dump($data);die;
    // 	foreach($data as $k => $v){
    // 		$str .= $k .'='. $v.'&';
    // 	}
    // 	$str = trim($str, '&');
    // 	// var_dump($str);die;
    // 	// echo $requestUrl.$str;die;
    // 	echo json_encode(array('url' => $requestUrl.$str));die;
    // 	echo json_encode(array('url' => urlencode($requestUrl.$str)));die;
    // 	$result = file_get_contents($requestUrl.$str);
    // 	$return = json_decode($result, true);
    // 	var_dump($return);die;

    // }
    // // 解密微信数据
    // public function decryptData($sessionKey, $encryptedData, $iv, &$data)
    // {
    // 	/*
    // 	public static $OK = 0;
    // 	public static $IllegalAesKey = -41001;
    // 	public static $IllegalIv = -41002;
    // 	public static $IllegalBuffer = -41003;
    // 	public static $DecodeBase64Error = -41004;
    // 	 */
    // 	if (strlen($sessionKey) != 24) {
    // 		return '-41001';
    // 	}
    // 	$aesKey = base64_decode($sessionKey);


    // 	if (strlen($iv) != 24) {
    // 		return '-41002';
    // 	}
    // 	$aesIV = base64_decode($iv);

    // 	$aesCipher = base64_decode($encryptedData);

    // 	$result = openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

    // 	$dataObj = json_decode( $result );
    // 	if( $dataObj  == NULL )
    // 	{
    // 		return '-41003';
    // 	}
    // 	if( $dataObj->watermark->appid != $this->miniprogramAppid )
    // 	{
    // 		return '-41003';
    // 	}
    // 	$data = $result;
    // 	return 0;
    // }
}