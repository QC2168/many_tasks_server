<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use think\Controller;
use think\Request;
use app\common\model\Feedback as FeedbackModel;
class Feedback extends BaseController
{
    //获取反馈list
    public function getFeedbackList(){
        $data=(new FeedbackModel())->get_feedback_list();
        return self::showResCode('获取成功',$data);
    }
}
