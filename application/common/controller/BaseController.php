<?php

namespace app\common\controller;

use think\Controller;
use think\Request;

class BaseController extends Controller
{
    // api 统一返回格式
    static function showResCode($msg='未定义MSG',$data=[],$errorCode=0,$code=200)
    {
        $res=[
            'msg'=>$msg,
            'data'=>$data,
            'errorCode'=>$errorCode,
        ];
        return json($res,$code);
    }

    // api统一返回格式无数据
    static public function showResCodeWithOutData($msg='未定义MSG',$data=[],$errorCode=0,$code=200){
        return self::showResCode($msg,[],$errorCode,$code);
    }
}
