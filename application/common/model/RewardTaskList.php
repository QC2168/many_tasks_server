<?php

namespace app\common\model;

use think\Model;

class RewardTaskList extends Model
{
    protected $autoWriteTimestamp = true;
    public function TaskStepPic(){
        return  $this->hasMany('RewardTaskStepPic','sn','sn');
    }
    public function getRewardTaskList(){
        $hide=['show','quota','create_time','update_time'];
        return $this->where('show','<>',0)->hidden($hide)->select();
    }
    public function getARewardTaskList(){
        return $this->select();
    }
    // 添加福利任务
    public function pushRewardTask()
    {
        // SET任务唯一标识
        $Sn='REWARD'.time();
        $param=request()->param();
        $reward_task_step_list=json_decode($param['reward_task_step_list'],true);
        // 首页图片
        $reward_task_pic=(!empty($reward_task_step_list[0]))?$reward_task_step_list[0]['pic']:'/static/TaskPic/default_task_pic.png';
        // 判断余额是否足够
        // 下单价格+红包价格 * 单数
        $push_task_price=($param['price']+$param['money_reward'])*$param['quota'];
        // 根据会员来判断push task 服务费
        $pricePerOrder=get_serve_price(request()->username,'push_reward_task');
        $serve_price=$param['quota']*$pricePerOrder;
        $all_price=$push_task_price+$serve_price;
        // 获取用户余额
        $user_wallet=(Assets::where('username',request()->username)->value('wallet'));
        if($user_wallet>=$all_price){
            // 余额充足
            //扣除余额
            Assets::where('username',request()->username)->update(['wallet'=>($user_wallet-$all_price)]);
            add_wallet_details(2,$all_price,"发布福利任务".$param['title']);
            //创建任务
            $this->create([
                'username'=>request()->username,
                'pic'=>$reward_task_pic,
                'title'=>$param['title'],
                'reward_goods_platform_type'=>$param['reward_goods_platform_type'],
                'reward_goods_type'=>$param['reward_goods_type'],
                'goods_url'=>$param['goods_url'],
                'liaison'=>$param['liaison'],
                'price'=>$param['price'],
                'money_reward'=>$param['money_reward'],
                'quota'=>$param['quota'],
                'remaining_quota'=>$param['quota'],
                'content'=>$param['content'],
                'show'=>1,
                'sn'=>$Sn,
                'is_btn'=>$param['is_btn'],
                'keyword'=>$param['keyword']
            ]);
            $reward_task_step_pic=new RewardTaskStepPic();
            // 判断有没有图片列表
            if(empty($reward_task_step_list)) return true;
            // 创建图片
            foreach ($reward_task_step_list as $key => $value){
                $item =$reward_task_step_list[$key];
                $reward_task_step_pic->create([
                    'sn'=>$Sn,
                    'pic'=>$item['pic'],
                    'text'=>$item['text']
                ]);
            }
            return true;
        }else{
            // 余额不足
            TApiException('当前账户余额不足',20005,200);
        }

    }


    //下架任务
    public function deleteTask(){
        $reward_task_id=request()->param('reward_task_id');
        //判断发布的用户
        $isUsername=$this->where('reward_task_id',$reward_task_id)->value('username');
        $reward_task_title=$this->where('reward_task_id',$reward_task_id)->value('title');
        if(request()->username != $isUsername)TApiException('发布者与下架不一致',20007, 200);
        // 获取状态
        $isDelete=$this->where('task_id',$reward_task_id)->value('show');
        // 该任务已经被删除了
        if($isDelete==0) TApiException('任务被删除了，无法查看',20006, 200);
        // 修改任务状态
        $this->where('reward_task_id',$reward_task_id)->update(['show'=>0]);
        // 计算退回的金额
        $task_data=$this->where('reward_task_id',$reward_task_id)->find();
        $returnAmount=$task_data['price']*$task_data['remaining_quota'];
        //更新
        Assets::where('username',request()->username)->setInc('wallet', $returnAmount);
        add_wallet_details(1,$returnAmount,"下架福利任务".$reward_task_title."，返回剩下金额");
        return true;

    }
    // 我上传的福利任务
    public function myPushRewardTask(){
        return $this->where('username',request()->username)->select();
    }
    // 获取任务详情
    public function getRewardTaskDetail(){
        $param=request()->param();
        // 判断这个任务是否隐藏/下架了
        $current_show=$this->where('reward_task_id',$param['reward_task_id'])->value('show');
        if (!$current_show) TApiException('任务被删除了，无法查看',20006, 200);
//        return $this->with('TaskStepPic')->where('task_id',$param['task_id'])->hidden(['quota','create_time','task_id','show'])->find();
        $hide=[
            'sn',
            'quota',
            'create_time',
            'reward_task_id',
            'show',
            'task_step_pic'=>[
                'sn',
                'id',
                'create_time'
            ]];
        return $this->with('TaskStepPic')->hidden($hide)->where('reward_task_id',$param['reward_task_id'])->find();
    }
}
