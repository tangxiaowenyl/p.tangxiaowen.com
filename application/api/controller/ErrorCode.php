<?php
/**
 * author   tangxiaowen
 * time     18-7-26 10:31
 * mail     846506584@qq.com
 * describe 微信小程序错误码
 */

namespace app\api\controller;

class ErrorCode extends Base
{

    protected static $error = [

        /**
         * 占用字段
         * code | describe
         */
        '-1' => '未知错误请检查!',


        /**
         * 项目使用中的状态码
         * code | describe
         */
        200 => '请求成功',
        201 => '验证请求参数 缺少请求参数',
        202 => '请求的数据已存在',
        203 => '没有数据',
        204 => '请求失败 (或者没有数据)',
        205 => '系统错误，请稍后再试~ ',

        /**
         * 阿里云短信错误码
         * code | describe
         */
        'OK'                         => '请求成功',
        'isp.RAM_PERMISSION_DENY'    => 'RAM权限DENY',
        'isv.OUT_OF_SERVICE'         => '业务停机',
        'isv.PRODUCT_UN_SUBSCRIPT'   => '未开通云通信产品的阿里云客户',
        'isv.PRODUCT_UNSUBSCRIBE'    => '产品未开通',
        'isv.ACCOUNT_NOT_EXISTS'     => '账户不存在',
        'isv.ACCOUNT_ABNORMAL'       => '账户异常',
        'isv.SMS_TEMPLATE_ILLEGAL'   => '短信模板不合法',
        'isv.SMS_SIGNATURE_ILLEGAL'  => '短信签名不合法',
        'isv.INVALID_PARAMETERS'     => '参数异常',
        'isp.SYSTEM_ERROR'           => '系统错误',
        'isv.MOBILE_NUMBER_ILLEGAL'  => '非法手机号',
        'isv.MOBILE_COUNT_OVER_LIMIT'=> '手机号码数量超过限制',
        'isv.TEMPLATE_MISSING_PARAMETERS' => '模板缺少变量',
        'isv.BUSINESS_LIMIT_CONTROL' => '业务限流',
        'isv.INVALID_JSON_PARAM'     => 'JSON参数不合法，只接受字符串值',
        'isv.BLACK_KEY_CONTROL_LIMIT'=> '黑名单管控',
        'isv.PARAM_LENGTH_LIMIT'     => '参数超出长度限制',
        'isv.PARAM_NOT_SUPPORT_URL'  => '不支持URL',
        'isv.AMOUNT_NOT_ENOUGH'      => '账户余额不足',

        /**
         * 小程序错误码
         * code|describe
         */
        '40029'  => 'js_code无效',
        '40163'  => 'js_code已被使用',
        '40037'	 => 'template_id不正确',
        '41028'	 => 'form_id不正确，或者过期',
        '41029'	 => 'form_id已被使用',
        '41030'	 => 'page不正确',
        '45009'  => '接口调用超过限额（目前默认每个帐号日调用限额为100万）',
    ];

    /**
     * 获取小程序返回码对应错误信息
     * @param array $param 需验证数据
     * @return array
     */
    public static function getWxAppErrorInfo($param = []){

        if(empty($param) || !array_key_exists('errcode',$param)){
            return $param;
        }

        //验证阿里云短信(errcode)
        if(isset(self::$error[$param['errcode']])){
            $param['describe'] = self::$error[$param['errcode']];
            return $param;
        }

        return ['errcode' => '-1','errmsg' => '未知错误请检查!','describe' => ''];
    }

    /**
     * 获取阿里云短信返回状态码对应中文错误信息(此方法仅限验证阿里云短信返回码)
     * @param array $param 需验证数据
     * @return array
     */
    public static function getAlyunMsgErrorInfo($param = null){

        if(!is_array($param) || !array_key_exists('Code',$param)){
            return $param;
        }

        //验证阿里云短信(code)
        if(isset(self::$error[$param['Code']])){
            $param['Describe'] = self::$error[$param['Code']];
            return $param;
        }

        return ['Code' => '-1','msg' => '未知错误请检查!','RequestId' => '','Describe' => ''];
    }

}