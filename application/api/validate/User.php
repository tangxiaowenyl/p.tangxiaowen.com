<?php
/**
 * author   tangxiaowen
 * time     18-7-25 19:19
 * mail     846506584@qq.com
 * describe 用户验证类
 */

namespace app\api\validate;

use think\Validate;

class User extends Validate
{

    protected $rule = [
        'open_id'      => 'require',
    ];

    protected $message = [
        'open_id'  => ['require' => 'open_id不能为空'],
    ];

    protected $scene = [
        'addUser'   => ['open_id'],
    ];

}