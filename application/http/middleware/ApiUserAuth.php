<?php

namespace app\http\middleware;

use think\console\Table;

class ApiUserAuth
{
    public function handle($request, \Closure $next)
    {
        //获取头部信息
        $param=$request->header();
        //没有token
        if (!array_key_exists('token',$param))TApiException('非法token，禁止操作',20003,200);
        //当前用户token是否存在
        $token=$param['token'];
        $user=\Cache::get($token);
        //验证失败 （没登录或者是过期）
        if (!$user)TApiException("非法token，请重新登录",20003,200);
        $request->userTokenUserInfo = $user;
        $request->username = $user['username'];
        return $next($request);
    }
}
