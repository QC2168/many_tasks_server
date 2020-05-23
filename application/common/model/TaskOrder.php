<?php

namespace app\common\model;

use think\Db;
use think\Model;

class TaskOrder extends Model
{
    protected $autoWriteTimestamp = true;
    public function TaskList(){
        return $this->hasMany('TaskList','task_id','task_id');
    }
    // 报名任务
    public function createTaskOrder()
    {
        $task_id = request()->param('task_id');
        // 判断有无名额
        $TaskList=new TaskList();
        // 判断是不是已经领取过了该任务
        // 查询该用户历史订单
        $historicalOrder=$this->where(['task_id'=>$task_id,'username'=>request()->username])->find();
        // 查询是不是该任务的
        if($historicalOrder){
            TApiException('你已经申请过该任务了!',20017, 200);
        }
        $quota=$TaskList->where('task_id',$task_id)->value('remaining_quota');
        if($quota<=0) TApiException('任务名次没有啦!',20010, 200);
        // 名次减一
        $TaskList->where('task_id',$task_id)->setDec('remaining_quota',1);
    $this->create([
           'task_id'=>$task_id,
            'username'=>request()->username,
            'orderSn'=>create_OrderSn(),
            'status'=>4,
        ]);
        return;
    }
 //获取我的任务订单
    public function myTaskOrder(){
       return $this->with('TaskList')->visible(['task_list'=>['title','price','task_pic'],'status','orderSn','create_time'])->where('username',request()->username)->select();
    }
//获取指定任务订单
    public function getTaskOrderInfo(){
        return $this->with('TaskList')->visible(['task_list'=>['title','price','task_pic'],'status','orderSn','create_time'])->where(['username'=>request()->username,'orderSn'=>request()->param('orderSn')])->find();
    }

    // 提交任务订单提交内容并审核
    public function placeOrder(){
       $orderSn = request()->param('orderSn');
       $content = request()->param('content');
        $picList = request()->param('pic_list');
         $currentOrderStatus=$this->where('orderSn',$orderSn)->value('status');
       if($currentOrderStatus!=4) TApiException('该悬赏任务订单已经是待审核状态',20018,200);
$save=$this->save(['status'  => 0],['orderSn' => $orderSn]);
//提交图片
        $task_order_pic=new TaskOrderPic();
        // 判断有没有图片列表
        $task_order_pic_list=json_decode($picList,true);
        if(empty($task_order_pic_list)) return true;
        // 创建图片
        foreach ($task_order_pic_list as $key => $value){
            $task_order_pic->create([
                'orderSn'=>$orderSn,
                'pic'=>$value
            ]);
        }

        //提交文字内容
//        $postContent=$this->where('orderSn',$orderSn)->update(['content'=>$content]);
        $this->save(['content'=>$content],['orderSn'=>$orderSn]);
        return true;
    }
    public function changeOrderStatus(){
       return Db::transaction(function () {
            $orderSn = request()->param('orderSn');
            $status = request()->param('status');
        // 查询当前是不是与目标状态一样
        $currentOrderStatus=$this->where('orderSn',$orderSn)->value('status');
        if($status==$currentOrderStatus)return;
$save=$this->save(['status'  => $status],['orderSn' => $orderSn]);

// 如果是 完成  获取价格  添加到兼职用户
        if ($status==1){
            // 修改为完成
            // 获取任务ID
            $task_id=$this->where('orderSn',$orderSn)->value('task_id');
            // 获取价格
            $price=TaskList::where('task_id',$task_id)->value('price');
            // 添加余额
            $assets=new Assets();
            $add=$assets->where('username',request()->username)->setInc('wallet',$price);
            add_wallet_details(1,$price,'完成悬赏任务奖励');
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
            add_wallet_details(1,$add_reward,"一级下级".request()->username."完成悬赏任务奖励");
            // 获取上级的上级
            $f_username_f_username=$User->where(['username'=>$f_username])->value('f_username');
            if(empty($f_username_f_username)){
                // 如果没有上级就不用奖励了
             return;
            }
            $r2=$team_reward->where(['type'=>'task_reward_two'])->value('value');
            $add_reward2=$price*$r2;
            $assets->where(['username'=>$f_username_f_username])->setInc('wallet',$add_reward2);
            add_wallet_details(1,$add_reward2,"二级下级".request()->username."完成悬赏任务奖励");
        } if($status==3){
            // 修改为完成
            // 获取任务ID
            $task_id=$this->where('orderSn',$orderSn)->value('task_id');
            // 退回名额
            $add=TaskList::where('task_id',$task_id)->setInc('remaining_quota', 1);
        return;
        }if($status==2){
            // 修改为完成
            // 获取任务ID
            $task_id=$this->where('orderSn',$orderSn)->value('task_id');
            // 退回名额
            $add=TaskList::where('task_id',$task_id)->setInc('remaining_quota', 1);
          return;
        }
        else{
            return;
        }

        });
    }

    public function myPushTaskOrder(){
        $task_id= request()->param('task_id_select');
        // 是否是请求这个人发布的任务
        $is=TaskList::where(['username'=>request()->username,'task_id'=>$task_id])->find();
        if(!$is) TApiException('发布者与查询者不一致', 20007, 200);
       return  $this->where(['task_id'=>$task_id])->hidden(['task_id','id'])->select();
    }
public function selectOrderPic(){
        $orderSn=request()->param('orderSn');
        $order_pic=new TaskOrderPic();
   return $order_pic->where('orderSn',$orderSn)->field('pic')->select();
}
}
