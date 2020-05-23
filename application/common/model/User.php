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

    // 获取 后台首页数据
    public function wmsHomeData(){
        // 获取全部数据
        $userCount=User::where('status',"<>",'0')->count();
        $taskCount=TaskList::where('show',"<>",'0')->count()+DyTaskList::where('show',"<>",'0')->count()+RewardTaskList::where('show',"<>",'0')->count();
        $AllOrderCount=DyTaskOrder::count('id')+TaskOrder::count('id')+RewardTaskOrder::count('id');
        // 获取当天数据
        $toDayUserCount=User::where('status',"<>",'0')->whereTime('create_time', 'today')->count();
        $toDayTaskCount=TaskList::where('show',"<>",'0')->whereTime('create_time', 'today')->count()+DyTaskList::where('show',"<>",'0')->whereTime('create_time', 'today')->count()+RewardTaskList::where('show',"<>",'0')->whereTime('create_time', 'today')->count();
        $toDayAllOrderCount=DyTaskOrder::whereTime('create_time', 'today')->count('id')+TaskOrder::whereTime('create_time', 'today')->count('id')+RewardTaskOrder::whereTime('create_time', 'today')->count('id');
        // 获取全部提现金额
        $AllOutAmount=OutOrderList::sum('amount');
        $toDayAllOutAmount=OutOrderList::whereTime('create_time', 'today')->sum('amount');
        // 最近订单5个
        $latelyOrderData=DyTaskOrder::limit(0,5)->select();
        $serveInfo=[
            '操作系统' => PHP_OS,
            '运行环境' => $_SERVER["SERVER_SOFTWARE"],
            'MYSQL'=>Db::query('select VERSION() as sqlversion')[0]['sqlversion'],
            '主机名' => $_SERVER['SERVER_NAME'],
            'WEB服务端口' => $_SERVER['SERVER_PORT'],
            '网站文档目录' => $_SERVER["DOCUMENT_ROOT"],
            'PHP版本' => PHP_VERSION,
            '服务器域名/IP' => $_SERVER['SERVER_NAME'] . ' [ ' . gethostbyname($_SERVER['SERVER_NAME']) . ' ]',
            '用户的IP地址' => $_SERVER['REMOTE_ADDR'],
            '剩余空间' => round((disk_free_space(".") / (1024 * 1024)), 2) . 'M',
        ];
        $outOrder=['sum'=>$AllOutAmount,'today'=>$toDayAllOrderCount,'title'=>'提现订单MODUL'];
        $taskdataorder=['sum'=>$taskCount,'today'=>$toDayTaskCount,'title'=>'任务订单MODUL'];
        $user=['sum'=>$userCount,'today'=>$toDayUserCount,'title'=>'用户MODUL'];
        return [
            'modulData'=>['outOrder'=>$outOrder,'taskdataorder'=>$taskdataorder,'user'=>$user],
            'money'=>['AllOutAmount'=>$AllOutAmount,'toDayAllOutAmount'=>$toDayAllOutAmount],
            'chart'=>[['name'=>'All任务订单','value'=>$AllOrderCount],['name'=>'任务数量','value'=>$taskCount],['name'=>'用户数量','value'=>$userCount]],
            'latelyOrder'=>$latelyOrderData,
            'serveInfo'=>$serveInfo
        ];
    }
}
