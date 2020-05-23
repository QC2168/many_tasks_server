<?php

namespace app\common\model;

use think\Model;

class DyTaskList extends Model
{
    protected $autoWriteTimestamp = true;

    // 获取 抖音任务列表
    public function getDyTaskList()
    {
        return $this->where('show', '<>', 0)->hidden(['show', 'quota'])->select();
    }
    // 获取 抖音任务列表
    public function getADyTaskList()
    {
        return $this->select();
    }

    //获取详情
    public function getDyTaskDetail()
    {
        $param = request()->param();
        // 判断这个任务是否隐藏/下架了
        $current_show = $this->where('dy_task_id', $param['dy_task_id'])->value('show');
        if ($current_show == 0) TApiException('任务被删除了，无法查看', 20006, 200);
        return $this->where('dy_task_id', $param['dy_task_id'])->hidden(['quota', 'create_time', 'dy_task_id', 'show'])->find();
    }

    //发布抖音任务
    public function pushDyTask()
    {
        $param = request()->param();
        // 获取表单上传文件
        $pic = request()->file('dy_task_pic');
        $info = $pic->validate(['size' => 2097152, 'ext' => 'jpg,png,gif'])->move('../public/static/DyTaskPic');
        if($info==false)TApiException('图片上传失败',20009, 200);
        $getSaveName = str_replace("\\", "/", $info->getSaveName());
        // 判断余额是否足够
        $push_task_price = $param['price'] * $param['quota'];
        // 根据会员来判断push dy task 服务费
        $pricePerOrder=get_serve_price(request()->username,'push_dy_task');
        $serve_price = $param['quota'] * $pricePerOrder;
        $all_price = $push_task_price + $serve_price;
        // 获取用户余额
        $user_wallet = (Assets::where('username', request()->username)->value('wallet'));
        if ($user_wallet >= $all_price) {
            // 余额充足
            //扣除余额
            Assets::where('username', request()->username)->update(['wallet' => ($user_wallet - $all_price)]);
            add_wallet_details(2,$all_price,"发布抖音任务".$param['title']);
            //创建任务
            $this->create([
                'username' => request()->username,
                'dy_url' => $param['dy_url'],
                'dy_task_pic' => '/static/DyTaskPic/' . $getSaveName,
                'title' => $param['title'],
                'content' => $param['content'],
                'price' => $param['price'],
                'quota' => $param['quota'],
                'remaining_quota' => $param['quota'],
                'show' => 1,
            ]);
            return true;
        } else {
            // 余额不足
            TApiException('当前账户余额不足', 20005, 200);
        }
    }

    //下架任务
    public function deleteDyTask()
    {
        $dy_task_id = request()->param('dy_task_id');
        //判断发布的用户
        $isUsername = $this->where('dy_task_id', $dy_task_id)->value('username');
        $title = $this->where('dy_task_id', $dy_task_id)->value('title');
        if (request()->username != $isUsername) TApiException('发布者与下架不一致', 20007, 200);
        // 获取状态
        $isdelete = $this->where('dy_task_id', $dy_task_id)->value('show');
        // 该任务已经被删除了
        if ($isdelete == 0) TApiException('任务被删除了，无法查看', 20006, 200);
        // 修改任务状态
        $this->where('dy_task_id', $dy_task_id)->update(['show' => 0]);
        // 计算退回的金额
        $task_data = $this->where('dy_task_id', $dy_task_id)->find();
        $returnAmount = $task_data['price'] * $task_data['remaining_quota'];
        //更新
        Assets::where('username', request()->username)->setInc('wallet', $returnAmount);
        add_wallet_details(1,$returnAmount,"下架抖音任务".$title."，返回剩下金额");
        return true;

    }

    // 我上传的抖音任务
public function myPushDyTask(){
        return $this->where('username',request()->username)->select();
}
}
