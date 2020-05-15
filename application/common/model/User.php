<?php

namespace app\common\model;

use app\common\validate\UserValidate;
use app\lib\exception\BaseException;
use think\Db;
use think\facade\Cache;
use think\Model;

class User extends Model
{
    protected $autoWriteTimestamp = true;
    // 关联vip表
    public function privilege(){
        return $this->hasOne('Privilege','username','username');
    }
    //注册
    public function register()
    {
        return Db::transaction(function () {
        //获取参数
        $param = request()->param();
        if ($param['password'] !== $param['rpassword']) TApiException('密码不一致', 20003, 200);
        // 验证用户是否存在
        $user = $this->where(['username' => $param['username']])->find();
        if ($user) TApiException('该昵称已被注册', 20004, 200);
        // 获取用户填写的邀请码
        // 查询是否有这个上级
        $fUsername=$this->where('code',$param['code'])->value('username');
        $User=new User();
        $addUser = $User->create([
            'username' => $param['username'],
            'password' => password_hash($param['password'], PASSWORD_DEFAULT),
            'phone' => $param['phone'],
            'power' => 1,
            'f_username' => !empty($fUsername)?$fUsername:'',
            'code' => create_InvitationCode(),
            'status' => 1,
        ]);
        $Assets=new Assets();
        $addAssets = $Assets->create([
            'username' => $param['username'],
            'wallet' => 0,
            'deposit' => 0,
            'integral' => 0,
        ]);
        $Privilege=new Privilege();
        $addPrivilege=$Privilege->create([
            'username'  =>  request()->username,
            'vip' =>  0,
            'expire_time' =>0,
        ]);
        $sign=new Sign();
            $addSign=$sign->create([
                'username'  =>  request()->username,
                'continued' =>  0,
                'last_time' =>0,
            ]);
        });
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
        return Db::transaction(function () {
        // 判断会员过期  修改状态
        $privilege=new Privilege();
        // 获取过期时间
       $expire_time=$privilege->where(['username'=>request()->username])->value('expire_time');
        if(time()>$expire_time){
            $privilege->save(['expire_time'=>$expire_time,'vip'=>0],['username'=>request()->username]);
        }
        return $this->where('username',request()->username)->with('privilege')->hidden(['id','create_time','password','power','status','privilege'=>['id','username','update_time','create_time']])->select();
        });
    }

    // 获取用户团队
    public function get_team(){
        // 查询所有下级
        $list= $this->where('f_username',request()->username)->field('username,user_pic,create_time')->select();
        $code= $this->where('username',request()->username)->value('code');
        $count= $this->where('f_username',request()->username)->count();
        return ['list'=>$list,'count'=>$count,'code'=>$code];
    }
}
