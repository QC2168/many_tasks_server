<?php

namespace app\common\model;

use app\lib\exception\BaseException;
use think\facade\Cache;
use think\Model;

class AdminUser extends Model
{
    //登录
    public function login()
    {
        //获取参数
        $param = request()->param();
        // 验证用户是否存在
        $user = $this->where(['username' => $param['username']])->find();
        // 用户不存在
        if (!$user) TApiException('密码不一致', 20000, 200);
        // 验证密码
        $this->checkPassword($param['password'], $user['password']);
        // 登录成功 生成token，进行缓存，返回客户端
        return $this->CreateSaveToken($user);
    }
    // 验证密码
    public function checkPassword($password, $hash)
    {
        if (!$hash) TApiException('密码错误', 20002, 200);
        // 密码错误
        if (!password_verify($password, $hash)) TApiException('密码错误', 20002, 200);
        return true;
    }
    // 生成并保存token
    public function CreateSaveToken($arr = [])
    {
        // 生成token
        $token = sha1(md5(uniqid(md5(microtime(true)), true)));
        $arr['token'] = $token;
        // 登录过期时间
        $expire = 0;
        // 保存到缓存中
        if (!Cache::set($token, $arr, $expire)) throw new BaseException();
        // 返回token
        return $token;
    }
}
