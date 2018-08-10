<?php
/**
 * author   tangxiaowen
 * time     18-7-26 10:43
 * mail     846506584@qq.com
 * describe 微信处理类
 */

namespace app\api\controller;

class Wechat extends Base
{

    private static $WxAppConfig = [];

    public function __construct()
    {
        parent::__construct();
        self::$WxAppConfig = config('queue.wx_app');
    }

    public function getWxOpenId(){

        //验证post数据
        $postData = Request()->only('js_code');
        $result = $this->validate($postData, 'Wechat.getWxOpenId');
        if ($result !== true) {
            return validateResult($result);
        }

        //拼接请求参数请求小程序接口
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.self::$WxAppConfig['app_id'].'&secret='.self::$WxAppConfig['app_secret'].'&js_code='.$postData['js_code'].'&grant_type=authorization_code';
        $result = curlRequestFun($url);

        //解析返回数据的对应(成功/失败)原因
        $result = ErrorCode::getWxAppErrorInfo(json_decode($result,true));
        if(is_array($result) && isset($result['openid'])){
            return ['code' => 200,'msg' => '请求成功','data' => $result];
        }else{
            return ['code' => 204,'msg' => '请求失败','data' => $result];
        }
    }

}