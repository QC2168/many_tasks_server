<?php

namespace app\common\model;

use think\Model;

class Feedback extends Model
{
    protected $autoWriteTimestamp = true;
    // 反馈
    public function post_feedback_content(){
        $feedback_content=request()->param('feedback_content');
        $this->create([
            'username'=>request()->username,
            'value'=>$feedback_content,
            'status'=>0,
        ]);
        return true;

    }
    // 获取反馈list
    public function get_feedback_list(){
        $page=request()->param('index');
        $data= $this->page($page,10)->select();
        $row=$this->count();
        return ['data'=>$data,'row'=>$row];

    }
    // 处理反馈
    public function change_feedback_data(){
        $id=request()->param('id');
        $this->save(['status'=>1],['id'=>$id]);
        return true;

    }
}
