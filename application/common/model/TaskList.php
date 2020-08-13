<?php

namespace app\common\model;

use think\Model;

class TaskList extends Model
{
    protected $autoWriteTimestamp = true;
    public function TaskStepPic(){
        return  $this->hasMany('TaskStepPic','sn','sn');
    }
    public function getTaskList(){
        return $this->where('show','<>',0)->hidden(['show','quota'])->select();
    }
    public function getATaskList(){
        $page=request()->param('index');
        $data= $this->page($page,10)->select();
        $row=$this->count();
        return ['data'=>$data,'row'=>$row];
    }
    public function getTaskDetail(){
        $param=request()->param();
        // 判断这个任务是否隐藏/下架了
        $current_show=$this->where('task_id',$param['task_id'])->value('show');
        if (!$current_show) TApiException('任务已经被删除了',20006, 200);
//        return $this->with('TaskStepPic')->where('task_id',$param['task_id'])->hidden(['quota','create_time','task_id','show'])->find();
        return $this->with('TaskStepPic')->hidden(['sn','quota','create_time','task_id','show','task_step_pic'=>['sn','id','create_time']])->where('task_id',$param['task_id'])->find();
    }
    public function pushTask()
    {
        // 获取任务唯一标识
        $Sn=time();
        $param=request()->param();
        $task_step_list=json_decode($param['task_step_list'],true);
        // 首页图片
        $task_pic=(!empty($task_step_list[0]))?$task_step_list[0]['pic']:'/static/TaskPic/default_task_pic.png';
        // 判断余额是否足够
        $push_task_price=$param['price']*$param['quota'];
        // 根据会员来判断push task 服务费
       $pricePerOrder=get_serve_price(request()->username,'push_task');
        $serve_price=$param['quota']*$pricePerOrder;
       $all_price=$push_task_price+$serve_price;
       // 获取用户余额
        $user_wallet=(Assets::where('username',request()->username)->value('wallet'));
       if($user_wallet>=$all_price){
           // 余额充足
           //扣除余额
           Assets::where('username',request()->username)->update(['wallet'=>($user_wallet-$all_price)]);
           add_wallet_details(2,$all_price,"发布悬赏任务".$param['title']);
           //创建任务
           $aa=$this->create([
               'username'=>request()->username,
               'title'=>$param['title'],
//               'tag'=>$param['tag'],
               'content'=>$param['content'],
               'price'=>$param['price'],
               'quota'=>$param['quota'],
               'pic'=>$task_pic,
               'remaining_quota'=>$param['quota'],
               'show'=>1,
               'sn'=>$Sn
           ]);
            $task_step_pic=new TaskStepPic();
            // 判断有没有图片列表
           if(empty($task_step_list)) return true;
            // 创建图片
            foreach ($task_step_list as $key => $value){
                $item =$task_step_list[$key];
                $task_step_pic->create([
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
        $task_id=request()->param('task_id');
        //判断发布的用户
        $isUsername=$this->where('task_id',$task_id)->value('username');
        $task_title=$this->where('task_id',$task_id)->value('title');
        if(request()->username != $isUsername)TApiException('发布者与下架不一致',20007, 200);
        // 获取状态
        $isdelete=$this->where('task_id',$task_id)->value('show');
        // 该任务已经被删除了
        if($isdelete==0) TApiException('任务已经被删除了',20006, 200);
        // 修改任务状态
        $this->where('task_id',$task_id)->update(['show'=>0]);
        // 计算退回的金额
        $task_data=$this->where('task_id',$task_id)->find();
        $returnAmount=$task_data['price']*$task_data['remaining_quota'];
        //更新
        Assets::where('username',request()->username)->setInc('wallet', $returnAmount);
        add_wallet_details(1,$returnAmount,"下架悬赏任务".$task_title."，返回剩下金额");
        return true;

    }
    // 我上传的悬赏任务
    public function myPushTask(){
        return $this->where('username',request()->username)->select();
    }


}
