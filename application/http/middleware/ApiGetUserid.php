<?php

namespace app\http\middleware;

class ApiGetUserid
{
    public function handle($request, \Closure $next)
    { // 获取头部信息
        $param = $request->header();
        // 不含token
        if (array_key_exists('token',$param)){
            $token = $param['token'];
            $user = \Cache::get($token);
            if ($user){
                $request->userId =  $user['id'];
            }
        }
        return $next($request);
    }
}
