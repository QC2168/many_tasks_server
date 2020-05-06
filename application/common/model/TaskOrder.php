<?php

namespace app\common\model;

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
       if($currentOrderStatus!=4) TApiException('错误！待审核状态',20011,200);
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
            $add=Assets::where('username',request()->username)->setInc('wallet',$price);
            if($add&&$save)return;
        } if($status==3){
            // 修改为完成
            // 获取任务ID
            $task_id=$this->where('orderSn',$orderSn)->value('task_id');
            // 退回名额
            $add=TaskList::where('task_id',$task_id)->setInc('remaining_quota', 1);
            if($add&&$save)return;
        }if($status==2){
            // 修改为完成
            // 获取任务ID
            $task_id=$this->where('orderSn',$orderSn)->value('task_id');
            // 退回名额
            $add=TaskList::where('task_id',$task_id)->setInc('remaining_quota', 1);
            if($add&&$save)return;
        }
        else{
            if($save)return;
        }


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
