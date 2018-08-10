<?php
/**
 * author   tangxiaowen
 * time     18-7-27 14:32
 * mail     846506584@qq.com
 * describe 留言信息验证类
 */

namespace app\api\validate;

use think\Validate;

class Message extends Validate
{

    protected $rule = [
        'contents'  => 'require|length:1,250',
        'latitude'  => 'require',
        'longitude' => 'require',
        'open_id'   => 'require',
        'message_id'=> 'require|number',
        'comment_avatar_url' => 'require',
        'comment_nickname'   => 'require',
        'comment_nickname'   => 'require',
        'comment_open_id'    => 'require',
        'message_id'         => 'require',
        'reply_avatar_url'   => 'require',
        'reply_open_id'      => 'require',
        'reply_nickname'     => 'require',
    ];

    protected $message = [
        'contents'  => ['require' => '亲，说点什么吧!','length' => '内容在1-250个字符之间哟!'],
        'latitude'  => ['require' => '位置信息错误，请稍后再试!'],
        'longitude' => ['require' => '位置信息错误，请稍后再试!'],
        'open_id'   => ['require' => '系统被外星人劫持，请稍后再试!'],
        'message_id'=> ['require' => '系统被外星人劫持，请稍后再试1!','number' => '系统被外星人劫持，请稍后再试2!'],
        'comment_avatar_url' => ['require' => '系统被外星人劫持，请稍后再试3!'],
        'comment_nickname'   => ['require' => '系统被外星人劫持，请稍后再试4!'],
        'comment_open_id'    => ['require' => '系统被外星人劫持，请稍后再试5!'],
        'message_id'         => ['require' => '系统被外星人劫持，请稍后再试6!'],
        'reply_avatar_url'   => ['require' => '系统被外星人劫持，请稍后再试7!'],
        'reply_open_id'      => ['require' => '系统被外星人劫持，请稍后再试8!'],
        'reply_nickname'     => ['require' => '系统被外星人劫持，请稍后再试9!'],
    ];

    protected $scene = [
        'addMessage'       => ['contents','latitude','longitude','open_id'],
        'getNearbyMessage' => ['latitude','longitude'],
        'getIdMessage'     => ['message_id'],
        'commentMessage'   => ['contents','comment_avatar_url','comment_nickname','comment_open_id','message_id','reply_avatar_url','reply_open_id','reply_nickname'],
    ];

}