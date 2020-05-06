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
            'value'=>$feedback_content
        ]);
        return true;

    }
}
