<?php
/**
 * author   tangxiaowen
 * time     18-7-26 11:36
 * mail     846506584@qq.com
 * describe 微信验证类
 */

namespace app\api\validate;

use think\Validate;

class Wechat extends Validate
{

    protected $rule = [
        'js_code'   => 'require',
    ];

    protected $message = [
        'js_code'   => ['require' => '系统被外星人劫持，请稍后再试!'],
    ];

    protected $scene = [
        'getWxOpenId'    => ['js_code'],
    ];

}