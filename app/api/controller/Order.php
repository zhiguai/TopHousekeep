<?php

namespace app\api\controller;

//TP类
use think\facade\Request;
use think\exception\ValidateException;
use think\facade\Db;

//验证类
use app\api\validate\Order as OrderValidate;

//类
use app\Common\Common;

class Order
{
    //添加-POST
    public function add()
    {
        //防手抖
        $preventClicks = Common::preventClicks('LastPostTime');
        if ($preventClicks[0] == false) {
            //返回数据
            return Common::create(['prompt' => $preventClicks[1]], '添加失败', 500);
        }

        //验证身份并返回数据
        $userData = Common::validateUserAuth();
        if (!empty($userData[0])) {
            return Common::create([], $userData[1], $userData[0]);
        }

        $cid = Request::param('cid');
        $uid = $userData['id'];
        $number = common::generateOrderNumber();

        $address = Request::param('address');
        $note = Request::param('note');
        $service_date = Request::param('service_date');

        //验证参数是否合法
        try {
            $tryData = [
                'address' => $address,
                'note' => $note,
                'service_date' => $service_date,
                'price' => '10'
            ];
            validate(OrderValidate::class)->batch(true)
                ->check($tryData);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            $validateerror = $e->getError();
            return Common::create($validateerror, '添加失败', 400);
        }

        //验证ID取Cards数据
        $result = Db::table('cards')->where('ban', 'false')->where('status', 'true')->where('id', $cid)->findOrEmpty();
        if (!$result) {
            return Common::create([], '卡片ID不存在', 500);
        }

        $time = date('Y-m-d H:i:s');
        $ip = Common::getIp();
        $price = $result['price'];
        //构建数据格式
        $data = [
            'cid' => $cid,
            'uid' => $uid,
            'number' => $number,
            'price' => $price,

            'address' => $address,
            'note' => $note,
            'service_date' => $service_date,
            'creat_date' => $time,
            'ip' => $ip
        ];

        //Order写入库
        if (!Db::table('orders')->insert($data)) {
            return Common::create([], '添加失败', 500);
        }

        return Common::create([], '添加成功', 200);
    }

    //编辑-POST
    public function edit()
    {
        //验证身份并返回数据
        $adminData = Common::validateAuth();
        if (!empty($adminData[0])) {
            return Common::create([], $adminData[1], $adminData[0]);
        }

        $id = Request::param('id');
        $price = Request::param('price');
        $address = Request::param('address');
        $note = Request::param('note');
        $status = Request::param('status');
        $ban = Request::param('ban');
        $service_date = Request::param('service_date');

        //验证参数是否合法
        try {
            $tryData = [
                'price' => $price,
                'address' => $address,
                'note' => $note,
                'service_date' => $service_date
            ];
            validate(OrderValidate::class)->batch(true)
                ->check($tryData);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            $validateerror = $e->getError();
            return Common::create($validateerror, '编辑失败', 400);
        }
        //获取order数据库对象
        $result = Db::table('orders')->where('id', $id);
        $resultOrder = $result->find();
        if (!$resultOrder) {
            return Common::create([], 'id不存在', 400);
        }

        //构建数据格式
        $data = [
            'price' => $price,
            'status' => $status,
            'ban' => $ban,

            'address' => $address,
            'note' => $note,
            'service_date' => $service_date
        ];

        //Order写入库
        if (!$result->update($data)) {
            return Common::create([], '编辑失败', 500);
        }

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

    //结束-POST
    public function end()
    {
        //验证身份并返回数据
        $userData = Common::validateUserAuth();
        if (!empty($userData[0])) {
            return Common::create([], $userData[1], $userData[0]);
        }

        //获取数据
        $number = Request::param('number');
        $ip = Common::getIp();
        $time = date('Y-m-d H:i:s');

        //获取Cards数据库对象
        $result = Db::table('orders')->where('number', $number);
        $resultData = $result->find();
        if (!$resultData) {
            return Common::create([], '订单不存在', 400);
        }

        if ($resultData['status'] != '3') {
            return Common::create(['order.status' => 'order.status!=3'], '结束失败', 400);
        }

        //更新视图字段
        if (!$result->update(['status'=>'4'])) {
            return Common::create(['order.status' => 'order.status更新失败'], '结束失败', 400);
        };

        //返回数据
        return Common::create([], '结束成功', 200);
    }
}
