<?php
/**
 * author   tangxiaowen
 * time     18-7-25 17:44
 * mail     846506584@qq.com
 * describe 用户处理类
 */

namespace app\api\controller;

use think\Request;

class User extends Base{

    static $userTable = 'user';

    public function addUser(){

        $postData = Request()->only('avatar_url,city,country,gender,language,nickname,province,open_id');
        //验证post数据
        $result = $this->validate($postData, 'User.addUser');
        if ($result !== true) {
            return validateResult($result);
        }

        $is_user = db(self::$userTable)->where('open_id',$postData['open_id'])->field('id,login_num')->find();
        if($is_user == null){
            $postData['login_ip']    = Request()->ip();
            $postData['create_time'] = date('Y-m-d H:i:s',time());
            $postData['login_time'] = date('Y-m-d H:i:s',time());
            $result = db(self::$userTable)->insert($postData);
        }else{
            $postData['login_ip']    = Request()->ip();
            $postData['login_time'] = date('Y-m-d H:i:s',time());
            $postData['login_num'] = $is_user['login_num'] + 1;
            //允许用户上传设置头像故不再更新用户头像
            if(isset($postData['avatar_url'])){
                unset($postData['avatar_url']);
            }
            $result = db(self::$userTable)->where('id',$is_user['id'])->update($postData);
        }
        return controllerReturnResult($result);
    }

    public function delUser(){

    }

    public function editUser(){

    }

    public function getUser(){

    }


}