<?php
/**
 * author   tangxiaowen
 * time     18-8-9 8:58
 * mail     846506584@qq.com
 * describe 服务类，操作小类目
 */

namespace app\api\controller;

class Serve extends Base{

    //帮助表
    static $helpTable   = 'help';
    //更新记录表
    static $updateTable = 'update';
    //缓存时间(秒)
    static $cacheTime = 50;

    /**
     * 获取更新记录信息
     * @return json
     *
     * @param integer
     */
    public function getAllUpdate(){

        $cacheAllUpdate = cache('cacheAllUpdate');
        if(!$cacheAllUpdate){
            $result = db(self::$updateTable)->order('create_time','desc')->select();
            foreach($result as $k=>$v){
                $result[$k]['create_time'] = date('Y-m-d',strtotime($v['create_time']));
            }
            cache('cacheAllUpdate',$result,self::$cacheTime);
            $cacheAllUpdate = cache('cacheAllUpdate');
        }
        return controllerReturnResult($cacheAllUpdate,$cacheAllUpdate,'','没有数据');
    }

    /**
     * 获取所有帮助的信息
     * @return json
     */
    public function getAllHelp(){

        $cacheAllHelp = cache('cacheAllHelp');
        if(!$cacheAllHelp){
            $result = db(self::$helpTable)->order('create_time','desc')->select();
            cache('cacheAllHelp',$result,self::$cacheTime);
            $cacheAllHelp = cache('cacheAllHelp');
        }
        return controllerReturnResult($cacheAllHelp,$cacheAllHelp,'','没有数据');
    }

}