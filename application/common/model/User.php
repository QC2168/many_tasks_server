<?php

namespace app\common\model;

use app\common\validate\UserValidate;
use app\lib\exception\BaseException;
use think\facade\Cache;
use think\Model;

class User extends Model
{
    protected $autoWriteTimestamp = true;
    //注册
    public function register()
    {
        //获取参数
        $param = request()->param();
        if ($param['password'] !== $param['rpassword']) TApiException('密码不一致', 20003, 200);
        // 验证用户是否存在
        $user = $this->where(['username' => $param['username']])->find();
        if ($user) TApiException('该昵称已被注册', 20004, 200);
        // 获取用户填写的邀请码
        // 查询是否有这个上级
        $power=$this->where('code',$param['code'])->value('username');
        $addUser = User::create([
            'username' => $param['username'],
            'password' => password_hash($param['password'], PASSWORD_DEFAULT),
            'phone' => $param['phone'],
            'power' => $power,
            'code' => create_InvitationCode(),
            'status' => 1,
        ]);
        $addAssets = Assets::create([
            'username' => $param['username'],
            'wallet' => 0,
            'deposit' => 0,
            'integral' => 0,
        ]);
        return true;
    }

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
        // 用户是否被禁用
        if ($user['status'] === 0) TApiException('该账户被禁用啦', 20001, 200);
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

// 用户是否被禁用（在前面课程的基础上扩充）
    public function checkStatus($username)
    {
        $user = $this->find($username)->toArray();
        $status = $user['status'];
        if ($status == 0) TApiException('该账户被禁用啦', 20001, 200);
    }

    // 生成并保存token
    public function CreateSaveToken($arr = [])
    {
        // 生成token
        $token = sha1(md5(uniqid(md5(microtime(true)), true)));
        $arr['token'] = $token;
        // 登录过期时间
//        $expire = array_key_exists('expires_in', $arr) ? $arr['expires_in'] : config('api.token_expire');
        $expire = 0;
        // 保存到缓存中
        if (!Cache::set($token, $arr, $expire)) throw new BaseException();
        // 返回token
        return $token;
    }

    //获取用户资料
    public function get_user()
    {

        return $this->where('username',request()->username)->hidden(['id','create_time','password','power','status'])->select();

    }

    // 获取用户团队
    public function get_team(){
        // 获取用户邀请码  查询所有下级
        $list= $this->where('power',request()->username)->field('username,user_pic,create_time')->select();
        $count= $this->where('power',request()->username)->count();
        $reward= $this->where('power',request()->username)->count()*50;
        return ['list'=>$list,'count'=>$count,'reward'=>$reward];
    }
}
