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
        return $this->select();

    }
}
