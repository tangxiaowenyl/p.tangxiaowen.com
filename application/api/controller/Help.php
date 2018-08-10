<?php
/**
 * author   tangxiaowen
 * time     18-8-8 15:48
 * mail     846506584@qq.com
 * describe 操作帮助表
 */

namespace app\api\controller;


class Help extends Base{

    //数据表名称
    static $tableName = 'help';
    //缓存时间(秒)
    static $cacheTime = 5;

    /**
     * 获取所有帮助的展示信息
     * @return json
     */
    public function getAllHelp(){

        $cacheAllHelp = cache('cacheAllHelp');
        if(!$cacheAllHelp){
            $result = db(self::$tableName)->select();
            cache('cacheAllHelp',$result,self::$cacheTime);
            $cacheAllHelp = cache('cacheAllHelp');
        }
        return controllerReturnResult($cacheAllHelp,$cacheAllHelp,'','没有数据');
    }

}