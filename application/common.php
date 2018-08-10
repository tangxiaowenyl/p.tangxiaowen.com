<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Log;
use think\Request;

/**
 * php curl请求
 * @param string $url 请求的url地址
 * @param string $param 请求的参数
 * @param bool $isPost 请求方式
 * @return string|flase
 */
if(!function_exists('curlRequestFun')){
    function curlRequestFun($url = null,$param = null,$isPost = false){

        if($url === null) return false;

        $culObj = curl_init();
        //设置请求的curl
        curl_setopt($culObj, CURLOPT_URL, $url);
        //不输出文件头(直接获取内容部分)
        curl_setopt($culObj, CURLOPT_HEADER, false);

        //设置curl请求方式
        if($isPost){
            curl_setopt($culObj, CURLOPT_POST, true);
        }else{
            curl_setopt($culObj, CURLOPT_POST, false);
        }

        //设置请求的参数
        curl_setopt($culObj, CURLOPT_POSTFIELDS, $param);
        //将curl_exec()获取的信息以字符串返回，而不是直接输出。
        curl_setopt($culObj, CURLOPT_RETURNTRANSFER, true);
        //禁止 cURL 验证对等证书（peer's certificate）
        curl_setopt($culObj, CURLOPT_SSL_VERIFYPEER, false);
        //允许Curl函数执行的最长秒数。这里设置为30秒
        curl_setopt($culObj, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($culObj);
        if($result === false){
            //错误信息写入记录日志
            $str = 'code 4050 msg 请求外部接口失败 contents '.curl_error($culObj);
            Log::record($str);
            return false;
        }
        curl_close($culObj);
        return $result;
    }
}

/**
 * 控制器处理模型结果并返回最终处理结果
 * @param int|array $result 模型返回的结果
 * @param array|string $data 需要返回ajax的数据
 * @param string $success 成功提示语
 * @param string $error 错误提示语
 * @return array|json
 */
if(!function_exists('controllerReturnResult')){
    function controllerReturnResult($result,$data = '',$success = '',$error = ''){
        if($result){
            return ['code' => 200,'msg' => empty($success)?'请求成功':$success,'data' => empty($data)?[]:$data];
        }else{
            return ['code' => 204,'msg' => empty($error)?'请求失败':$error,'data' => empty($data)?[]:$data];
        }
    }
}

/**
 * 返回验证类处理结果
 * @param string $msg 提示信息
 * @param string $code 状态码
 * @return array|json
 */
if(!function_exists('validateResult')){
    function validateResult($msg = null,$code = null){
        return [
            'code' => !is_null($code)?$code:201,
            'msg'  => !is_null($msg)?$msg:'验证参数错误',
            'data' => [],
        ];
    }
}

/**
 * 验证IP地址请求是否频繁
 * @param string $ip 请求IP地址
 * @return bool
 */
if(!function_exists('checkIpRequest')){
    function checkIpRequest($ip){

    }
}

