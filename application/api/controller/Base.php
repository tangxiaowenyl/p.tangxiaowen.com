<?php
/**
 * author   tangxiaowen
 * time     18-7-25 16:50
 * mail     846506584@qq.com
 * describe 基础继承类
 */

namespace app\api\controller;

use think\Controller;
use think\Request;
use think\Log;

class Base extends Controller
{
    public function __construct()
    {
        parent::__construct();

        //允许任意域名发起的跨域请求
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With');

        //初始化本模块下所有日志存放目录
        $this->initLogSavePath();
    }

    /**
     * 初始化本模块下所有日志存放目录
     */
    private function initLogSavePath()
    {

        $requestName = Request::instance()->module();
        $controllerName = Request::instance()->controller();
        $actionName = Request::instance()->action();
        $setPath = strtolower("{$requestName}/{$controllerName}/{$actionName}/");
        Log::init(['path'=>LOG_PATH.$setPath,'type'  => 'file',]);
    }

    /**
     * 访问空方法执行默认执行函数(空操作)
     */
    public function _empty()
    {
        echo '<h1 style="margin:20px 0 20px 15px;font-size: 28px;line-height: 32px;font-weight: 500;letter-spacing: 1px">'.config('queue.msg').'</h1>';
        echo '<p style="border-bottom:1px solid #EEEEEE;"></p>';
        echo '<a href="'.config('web.web_url').'" style="margin:20px 0 20px 20px;font-size:14px;letter-spacing: 1px;line-height: 24px;color:#333333;">'.config('queue.title').'</a>';
    }


}