<?php

namespace app\common\model;

use think\Model;

class News extends Model
{
    protected $autoWriteTimestamp = true;
public function getNews(){
    return $this->where('status',1)->select();
}
// 发布新的消息
    public function postNews(){
        $title=request()->param('title');
        $content=request()->param('content');
        $this->save([
           'title'=>$title,
           'content'=>$content,
           'status'=>1,
        ]);
        return true;
    }
}
