<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::post('api/:version/token/user','api/:version.LoginWx/getToken');
Route::post('api/:version/unionID/post','api/:version.GetUnionID/get');
Route::post('api/:version/loginurl/post','api/:version.GetLoginUrl/getUrl');
Route::get('api/:version/getorderlist/get/:userid','api/:version.GetOrderList/init');
Route::get('api/:version/getorderdetails/get/:orderid','api/:version.GetOrderList/getOrderDetail');
Route::get('api/:version/getcoursestatus/get/:course','api/:version.GetClassStatus/init');
Route::get('api/:version/logincheck/get/:token','api/:version.CheckLogin/init');
Route::get('api/:version/banner/get','api/:version.Banner/getBanner');
Route::get('api/:version/logincheck/changetime/:token','api/:version.CheckLogin/changeTime');
Route::post('api/:version/pay/getpay','api/:version.Pay/getPay');
Route::post('api/:version/pay/getpayorder','api/:version.Pay/getParameters');
Route::post('api/:version/order/cancel','api/:version.CancelOrder/init');

Route::post('api/:version/course/getlivecourseswithpage','api/:version.Course/getLiveCoursesWithPage');
Route::post('api/:version/course/getlivecoursedetail','api/:version.Course/getLiveCourseDetail');
Route::post('api/:version/course/getcoursecatalog','api/:version.Course/getCourseCatalog');
Route::post('api/:version/course/getcommentswithpage','api/:version.Course/getCommentsWithPage');
Route::post('api/:version/course/receivecourse','api/:version.Course/receiveCourse');
Route::post('api/:version/course/getlessoninfo','api/:version.Course/getLessonInfo');
Route::post('api/:version/course/getlessonlist','api/:version.Course/getLessonList');

Route::get('api/:version/getuserinfobyinstanceid/get/:instanceid/:token','api/:version.GetUserinfoByInstanceid/get');

