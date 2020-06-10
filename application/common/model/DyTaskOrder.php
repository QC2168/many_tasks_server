<?php

namespace app\common\model;

use think\Db;
use think\Model;

class DyTaskOrder extends Model
{
    protected $autoWriteTimestamp = true;
    public function DyTaskList(){
        return $this->hasMany('DyTaskList','dy_task_id','dy_task_id');
    }
    public function createDyTaskOrder()
    {
        $dy_task_id = request()->param('dy_task_id');
        $check_pic = request()->file('check_pic');
        // 判断是不是已经领取过了该任务
        // 查询该用户历史订单
        $historicalOrder=$this->where(['dy_task_id'=>$dy_task_id,'username'=>request()->username])->find();
        // 查询是不是该任务的
        if($historicalOrder){
            TApiException('你已经申请过该任务了!',20017, 200);
        }
        $info = $check_pic->validate(['size' => 2097152, 'ext' => 'jpg,png,gif'])->move('../public/static/DyTaskOrderPic');
        if($info==false)TApiException('图片上传失败',20009, 200);
        // 判断有无名额
        $quota=DyTaskList::where('dy_task_id',$dy_task_id)->value('remaining_quota');
        if($quota<=0) TApiException('任务名次没有啦!',20010, 200);
        // 名次减一
        DyTaskList::where('dy_task_id',$dy_task_id)->setDec('remaining_quota',1);
        $getSaveName = str_replace("\\", "/", $info->getSaveName());
    $this->create([
           'dy_task_id'=>$dy_task_id,
            'username'=>request()->username,
            'check_pic'=>'/static/DyTaskOrderPic/' . $getSaveName,
            'orderSn'=>create_OrderSn(),
            'status'=>0,
        ]);
        return;
    }

    public function myDyTaskOrder(){
       return $this->with('DyTaskList')->visible(['dy_task_list'=>['title','price','dy_task_pic'],'check_pic','status','orderSn','create_time'])->where('username',request()->username)->select();
    }


    public function changeDyOrderStatus(){
        return Db::transaction(function () {
            $orderSn = request()->param('orderSn');
            $status = request()->param('status');
            // 查询当前是不是与目标状态一样
            $currentOrderStatus = $this->where('orderSn', $orderSn)->value('status');
            $orderUser = $this->where('orderSn', $orderSn)->value('username');
            if ($status == $currentOrderStatus) return;
            // 修改订单状态
            $save = $this->save(['status' => $status], ['orderSn' => $orderSn]);

// 如果是 完成  获取价格  添加到兼职用户
            if ($status == 1) {
                // 修改为完成
                // 获取任务ID
                $dy_task_id = $this->where('orderSn', $orderSn)->value('dy_task_id');
                // 获取价格
                $price = DyTaskList::where('dy_task_id', $dy_task_id)->value('price');
                // 添加余额
                $assets = new Assets();
                $add = $assets->where('username', $orderUser)->setInc('wallet', $price);
                add_wallet_details(1,$price,"完成抖音任务",$orderUser);
                // 查找上级
                $User = new User();
                $f_username = $User->where(['username' =>$orderUser])->value('f_username');
                if (empty($f_username)) {
                    // 如果没有上级就不用奖励了
                    return;
                }
                // 给上级添加
                $team_reward = new TeamReward();
                $r = $team_reward->where(['type' => 'dy_task_reward_one'])->value('value');
                $add_reward = $price * $r;
                $assets->where(['username' => $f_username])->setInc('wallet', $add_reward);
                add_wallet_details(1,$add_reward,"一级下级".$orderUser."完成抖音任务奖励",$f_username);
                // 获取上级的上级
                $f_username_f_username = $User->where(['username' => $f_username])->value('f_username');
                if (empty($f_username_f_username)) {
                    // 如果没有上级就不用奖励了
                    return;
                }
                $r2 = $team_reward->where(['type' => 'dy_task_reward_two'])->value('value');
                $add_reward2 = $price * $r2;
                $assets->where(['username' => $f_username_f_username])->setInc('wallet', $add_reward2);
                add_wallet_details(1,$add_reward2,"一级下级".$orderUser."完成抖音任务奖励",$f_username);
            }
            if ($status == 2) {
                // 修改为完成
                // 获取任务ID
                $dy_task_id = $this->where('orderSn', $orderSn)->value('dy_task_id');
                // 退回名额
                $add = DyTaskList::where('dy_task_id', $dy_task_id)->setInc('remaining_quota', 1);
                if ($add && $save) return;
            } else {
                if ($save) return;
            }
        });
    }

    public function myPushDyTaskOrder(){
        $dy_task_id= request()->param('dy_task_id_select');
        // 是否是请求这个人发布的任务
        $is=DyTaskList::where(['username'=>request()->username,'dy_task_id'=>$dy_task_id])->find();
        if(!$is) TApiException('发布者与查询者不一致', 20007, 200);
       return  $this->where(['dy_task_id'=>$dy_task_id])->hidden(['dy_task_id','id'])->select();
    }

    public function getADyTaskOrderList(){
        $page=request()->param('index');
        $data= $this->page($page,10)->select();
        $row=$this->count();
        return ['data'=>$data,'row'=>$row];
    }

}
