<?php

namespace app\common\model;

use think\Db;
use think\Model;

class RewardTaskOrder extends Model
{
    protected $autoWriteTimestamp = true;
    public function RewardTaskList(){
        return $this->hasMany('RewardTaskList','reward_task_id','reward_task_id');
    }
    // 报名任务
    public function createRewardTaskOrder()
    {
        $reward_task_id = request()->param('reward_task_id');
        // 判断有无名额
        $RewardTaskList=new RewardTaskList();
        $quota=$RewardTaskList->where('reward_task_id',$reward_task_id)->value('remaining_quota');
        if($quota<=0) TApiException('任务名次没有啦!',20010, 200);
        // 名次减一
        $RewardTaskList->where('reward_task_id',$reward_task_id)->setDec('remaining_quota',1);
        $this->create([
            'reward_task_id'=>$reward_task_id,
            'username'=>request()->username,
            'orderSn'=>'RE'.create_OrderSn(),
            'status'=>0,//商家审核
        ]);
        return;
    }
// 提交任务订单提交内容并审核
    public function placeRewardOrder(){
        $orderSn = request()->param('orderSn');
        $goodsOrderSn = request()->param('goods_orderSn');
        $content = request()->param('content');
        $picList = request()->param('pic_list');
        $currentOrderStatus=$this->where('orderSn',$orderSn)->value('status');
        if($currentOrderStatus!=4) TApiException('错误状态',20011,200);
        $save=$this->save(['status'  => 5],['orderSn' => $orderSn]);
//提交图片
        $reward_task_order_pic=new RewardTaskOrderPic();
        // 判断有没有图片列表
        $task_order_pic_list=json_decode($picList,true);
        if(empty($task_order_pic_list)) return true;
        // 创建图片
        foreach ($task_order_pic_list as $key => $value){
            $reward_task_order_pic->create([
                'orderSn'=>$orderSn,
                'pic'=>$value
            ]);
        }

        //提交文字内容
        $this->save(['content'=>$content,'goods_orderSn'=>$goodsOrderSn],['orderSn'=>$orderSn]);
        return true;
    }
    //获取我的任务订单
    public function myRewardTaskOrder(){
        return $this->with('RewardTaskList')->visible(['reward_task_list'=>['title','price','task_pic'],'status','orderSn','create_time'])->where('username',request()->username)->select();
    }
//获取指定任务订单
    public function getRewardTaskOrderInfo(){
        return $this->with('RewardTaskList')->visible(['reward_task_list'=>['title','price','task_pic'],'status','orderSn','create_time'])->where(['username'=>request()->username,'orderSn'=>request()->param('orderSn')])->find();
    }

    public function myPushRewardTaskOrder(){
        $reward_task_id= request()->param('reward_task_id_select');
        // 是否是请求这个人发布的任务
        $is=RewardTaskList::where(['username'=>request()->username,'reward_task_id'=>$reward_task_id])->find();
        if(!$is) TApiException('发布者与查询者不一致', 20007, 200);
        return  $this->where(['reward_task_id'=>$reward_task_id])->hidden(['reward_task_id','id'])->select();
    }
    public function selectRewardOrderPic(){
        $orderSn=request()->param('orderSn');
        $order_pic=new RewardTaskOrderPic();
        return $order_pic->where('orderSn',$orderSn)->field('pic')->select();
    }

    public function changeOrderStatus(){
        return Db::transaction(function () {
            $orderSn = request()->param('orderSn');
            $status = request()->param('status');
            // 查询当前是不是与目标状态一样
            $currentOrderStatus=$this->where('orderSn',$orderSn)->value('status');
            // 订单一样无需修改
            if($status==$currentOrderStatus)return;
            $save=$this->save(['status' => $status],['orderSn' => $orderSn]);
// 如果是 完成  获取价格  添加到兼职用户
            if ($status==1){
                // 修改为完成
                // 获取任务ID
                $task_id=$this->where('orderSn',$orderSn)->value('reward_task_id');
                // 获取价格
                $price=RewardTaskList::where('reward_task_id',$task_id)->value('price');
                // 添加余额
                $assets=new Assets();
                $add=$assets->where('username',request()->username)->setInc('wallet',$price);
                // 查找上级
                $User=new User();
                $f_username=$User->where(['username'=>request()->username])->value('f_username');
                if(empty($f_username)){
                    // 如果没有上级就不用奖励了
                    return;
                }
                // 给上级添加
                $team_reward=new TeamReward();
                $r=$team_reward->where(['type'=>'task_reward_one'])->value('value');
                $add_reward=$price*$r;
                $assets->where(['username'=>$f_username])->setInc('wallet',$add_reward);
                // 获取上级的上级
                $f_username_f_username=$User->where(['username'=>$f_username])->value('f_username');
                if(empty($f_username_f_username)){
                    // 如果没有上级就不用奖励了
                    return;
                }
                $r2=$team_reward->where(['type'=>'task_reward_two'])->value('value');
                $add_reward2=$price*$r2;
                $assets->where(['username'=>$f_username_f_username])->setInc('wallet',$add_reward2);
            }
            if($status==3){
                // 修改为完成
                // 获取任务ID
                $task_id=$this->where('orderSn',$orderSn)->value('task_id');
                // 退回名额
                $add=RewardTaskList::where('reward_task_id',$task_id)->setInc('remaining_quota', 1);
                return;
            }
            if($status==2){
                // 修改为完成
                // 获取任务ID
                $reward_task_id=$this->where('orderSn',$orderSn)->value('reward_task_id');
                // 退回名额
                $add=RewardTaskList::where('reward_task_id',$reward_task_id)->setInc('remaining_quota', 1);
                return;
            }
            if($status==7){
                // 修改为完成
                // 获取任务ID
                $reward_task_id=$this->where('orderSn',$orderSn)->value('reward_task_id');
                // 退回名额
                $add=RewardTaskList::where('reward_task_id',$reward_task_id)->setInc('remaining_quota', 1);
                return;
            }
            else{
                return;
            }

        });
    }
}
