<?php

namespace app\api\controller;

//TP类
use think\facade\Request;
use think\facade\Db;

//类
use app\Common\Common;

class CardsTag
{
    //添加-POST
    public function add()
    {
        //验证身份并返回数据
        $userData = Common::validateAuth();
        if (!empty($userData[0])) {
            return Common::create([], $userData[1], $userData[0]);
        }

        $name = Request::param('name');
        $tip = Request::param('tip');
        if (mb_strlen($name, 'utf-8') > 8 || mb_strlen($tip, 'utf-8') > 64) {
            return Common::create(['name' => 'name长度<=8', 'tip' => 'tip长度<=64'], '添加失败', 400);
        }
        $time = date('Y-m-d H:i:s');
        $status = 'false'; //上/下:0/1

        //获取数据库对象
        $result = Db::table('cards_tag');
        //整理数据
        $data = ['name' => $name, 'tip' => $tip, 'status' => $status, 'date' => $time];
        //写入失败返回
        if (!$result->insert($data)) {
            return Common::create(['CardsTag' => '写入失败'], '添加失败', 400);
        }
        //返回结果
        return Common::create([], '添加成功', 200);
    }

    //编辑-POST
    public function edit()
    {
        //验证身份并返回数据
        $userData = Common::validateAuth();
        if (!empty($userData[0])) {
            return Common::create([], $userData[1], $userData[0]);
        }

        $id = Request::param('id');
        $name = Request::param('name');
        $tip = Request::param('tip');
        $status = Request::param('status'); //上/下:0/1       
        $time = date('Y-m-d H:i:s');

        //验证数据格式
        if (mb_strlen($name, 'utf-8') > 8 || mb_strlen($tip, 'utf-8') > 64) {
            return Common::create(['name' => 'name长度<=8', 'tip' => 'tip长度<=64'], '编辑失败', 400);
        }
        if ($status != 'false' && $status != 'true') {
            return Common::create(['status' => 'status格式错误'], '编辑失败', 400);
        }
        //验证ID是否正常传入
        if (empty($id)) {
            //跳转返回消息
            return Common::create(['id' => '缺少id参数'], '编辑失败', 400);
        }


        //获取数据库对象
        $result = Db::table('cards_tag')->where('id', $id);
        //验证ID是否存在
        if (!$result->find()) {
            return Common::create(['CardsTagID' => 'ID不存在'], '编辑失败', 400);
        }

        //整理数据
        $data = ['name' => $name, 'tip' => $tip, 'status' => $status];
        //编辑失败返回
        if (!$result->update($data)) {
            return Common::create(['CardsTag' => '更新失败'], '编辑失败', 400);
        }
        //返回结果
        return Common::create([], '编辑成功', 200);
    }

    //删除-POST
    public function delete()
    {

        //验证身份并返回数据
        $userData = Common::validateAuth();
        if (!empty($userData[0])) {
            return Common::create([], $userData[1], $userData[0]);
        }

        //获取数据
        $id = Request::param('id');

        //获取数据库对象
        $result = Db::table('cards_tag')->where('id', $id);
        if (!$result->find()) {
            return Common::create([], 'id不存在', 400);
        }
        $result->delete();

        //获取tag_map数据库对象
        $result = Db::table('cards_tag_map')->where('tid', $id);
        if ($result->find()) {
            $result->delete();
        }

        //返回数据
        return Common::create([], '删除成功', 200);
    }
}
