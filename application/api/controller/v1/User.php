<?php

namespace app\api\controller\v1;

use app\common\controller\BaseController;
use app\common\model\Feedback;
use app\common\validate\UserValidate;
use app\common\model\User as UserModel;
use app\common\model\Feedback as FeedbackModel;
use think\Controller;
use think\Request;

class User extends BaseController
{
    //注册
    public function register(){
        (new UserValidate())->goCheck('register');
        (new UserModel())->register();
        return self::showResCodeWithOutData('注册成功');
    }

    //登录
    public function login(){
        (new UserValidate())->goCheck('login');
        $token=(new UserModel())->login();
        return self::showResCode('登录成功',['token'=>$token]);
    }

    //获取用户页面资料
    public function get_user(){
        $data=(new UserModel())->get_user();
        return self::showResCode('获取成功',$data);
    }
    //获取用户团队
    public function team(){
        $data=(new UserModel())->get_team();
        return self::showResCode('获取成功',$data);
    }
    //反馈
    public function feedback(){
        (new UserValidate())->goCheck('feedback');
        $data=(new FeedbackModel())->post_feedback_content();
        return self::showResCode('反馈成功',$data);
    }
    //反馈
    public function getUserInfoList(){
        $data=(new UserModel())->get_user_info_list();
        return self::showResCode('获取成功',$data);
    }

    // 上传头像
    public function uploadUserPic()
    {
        (new UserValidate())->goCheck('uploadUserPic');
        $pic = request()->file('user_pic');
        $info = $pic->validate(['size' => 2097152, 'ext' => 'jpg,png,gif'])->move('../public/static/UserPic');
        if ($info == false) TApiException('图片上传失败', 20009, 200);
        $getSaveName = str_replace("\\", "/", $info->getSaveName());
        return self::showResCode('上传成功','/static/UserPic/'.$getSaveName);
    }
}
