<?php
/**
 * author   tangxiaowen
 * time     18-7-25 17:09
 * mail     846506584@qq.com
 * describe 留言管理类
 */

namespace app\api\controller;

use think\Log;
use think\Db;
use think\Request;

class Message extends Base
{

    //缓存时间(秒)
    static $cacheTime = 5;

    /**
     * 添加留言
     * @param string contents 留言内容
     * @param float latitude 纬度
     * @param float longitude 经度
     * @param string open_id 留言者openId
     * @return json
     */
    public function addMessage(){

        //验证post数据
        $postData = Request()->only('contents,latitude,longitude,open_id');
        $result = $this->validate($postData, 'Message.addMessage');
        if ($result !== true) return validateResult($result);

        //验证是否频繁请求添加留言
        $result = $this->checkComment($postData['open_id'],'addMessage');
        if(!$result) return validateResult('服务器太撑正在消化ing，5秒后即可留言!');

        $postData['create_time'] = date('Y-m-d H:i:s',time());
        $postData['expire_time'] = date('Y-m-d H:i:s',(config('queue.msg_expire_time') + time()));
        $result = db('message')->insert($postData);
        return controllerReturnResult($result,'','留言成功','留言失败');
    }

    /**
     * 获取附近留言信息
     * @param float latitude 纬度
     * @param float longitude 经度
     * @return json
     */
    public function getNearbyMessage(){

        //验证post数据
        $postData = Request()->only('latitude,longitude');
        $result = $this->validate($postData, 'Message.getNearbyMessage');
        if ($result !== true) return validateResult($result);

        //根据经纬度获取附近留言信息
        $squares = $this->returnSquarePoint($postData['latitude'], $postData['latitude']);
        $sql = "SELECT id,latitude,longitude FROM `p_message` WHERE latitude<>0 AND latitude>{$squares['right-bottom']['lat']} AND latitude<{$squares['left-top']['lat']} AND latitude>{$squares['left-top']['lng']} AND latitude<{$squares['right-bottom']['lng']} AND status = 1 AND type = 1 ORDER BY create_time DESC LIMIT 100";
        $messageData = Db::query($sql);

        //获取最新的一条留言信息
        $sql = "SELECT m.id,m.contents,x.nickname FROM p_message as m inner join p_user as x on m.open_id = x.open_id WHERE m.latitude<>0 AND m.latitude>{$squares['right-bottom']['lat']} AND m.latitude<{$squares['left-top']['lat']} AND m.latitude>{$squares['left-top']['lng']} AND m.latitude<{$squares['right-bottom']['lng']} AND m.status = 1 AND type = 2 ORDER BY m.create_time DESC LIMIT 1";
        $scrollData = Db::query($sql);

        $tempData = [
            'scroll' => $scrollData,
            'message'=> $messageData
        ];
        return controllerReturnResult($tempData,$tempData,'获取成功','没有数据');
    }

    /**
     * 根据留言ID获取留言信息
     * @param int $message_id 留言信息ID
     * @return json
     */
    public function getIdMessage(){

        $postData = Request()->only('message_id');
        $result = $this->validate($postData,'Message.getIdMessage');
        if($result !== true) return validateResult($result);

        //获取指定留言
        $where['m.id'] = $postData['message_id'];
        $where['m.status'] = ['<>',2];
        $result = db('message')->alias('m')
            ->where($where)
            ->join('user u','m.open_id = u.open_id')
            ->field(['m.id','m.contents','m.create_time','u.nickname','u.avatar_url'])
            ->find();

        if(!$result) return validateResult('留言信息不存在或已被删除',204);

        //获取留言的评论条数
        $replyData = db('comment')->where('message_id',$result['id'])->where('status','<>',2)->select();
        $result['total'] = count($replyData);
        $tempData = [
            'code' => 200,
            'msg'  => '获取成功',
            'data' => [
                'message' => $result,
                'reply'   => $replyData,
            ]
        ];
        return controllerReturnResult($tempData,$tempData,'获取成功','留言不存在或已被删除!');
    }

    /**
     * 评论留言
     * @param string $contents 评论内容
     * @param url $comment_avatar_url 评论者头像
     * @param string $comment_nickname 评论者昵称
     * @param string $comment_open_id 评论者open_id
     * @param int $message_id 被评论的留言ID
     * @param url $reply_avatar_url 被评论者头像
     * @param string $reply_open_id 被评论者open_id
     * @param string $reply_nickname 被评论者昵称
     * @return json
     */
    public function commentMessage(){

        $postData = Request()->only('contents,comment_avatar_url,comment_nickname,comment_open_id,message_id,reply_avatar_url,reply_open_id,reply_nickname');
        $result = $this->validate($postData,'Message.commentMessage');
        if($result !== true) return validateResult($result);

        //验证是否频繁请求添加留言
        $result = $this->checkComment($postData['comment_open_id'],'commentMessage');
        if(!$result) return validateResult('服务器太撑正在消化ing，5秒后即可留言!');

        $postData['create_time'] = date('Y-m-d H:i:s',time());
        $result = db('comment')->insert($postData);
        return controllerReturnResult($result,'','评论成功','评论失败');
    }

    /**
     * 计算某个经纬度的周围某段距离的正方形的四个点
     * @param lng float 经度
     * @param lat float 纬度
     * @param integer $globe_radius 地球半径(平均半径为6371km)
     * @param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为10千米
     * @return array 正方形的四个点的经纬度坐标
     */
     private function returnSquarePoint($lng, $lat,$globe_radius = 6371,$distance = 10){

        $dlng =  2 * asin(sin($distance / (2 * $globe_radius)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);

        $dlat = $distance/$globe_radius;
        $dlat = rad2deg($dlat);

        return [
            'left-top'    =>['lat'=>$lat + $dlat,'lng'=>$lng-$dlng],
            'right-top'   =>['lat'=>$lat + $dlat, 'lng'=>$lng + $dlng],
            'left-bottom' =>['lat'=>$lat - $dlat, 'lng'=>$lng - $dlng],
            'right-bottom'=>['lat'=>$lat - $dlat, 'lng'=>$lng + $dlng]
        ];
    }

    /**
     * 检测用户评论是否过于频繁
     * @param string $open_id 微信用户open_id
     * @param integer $cache_time 缓存时间(次/30秒)
     * @return bool
     */
    public function checkComment($open_id,$type){

        if(!cache($open_id.$type)){
            cache($open_id.$type,$open_id,self::$cacheTime);
            return true;
        }else{
            return false;
        }
    }

}