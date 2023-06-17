<?php

namespace app\admin\controller;

//TP类
use think\facade\View;
use think\facade\Db;

//类
use app\common\Common;

class User
{

    //Index
    public function index()
    {
        //验证身份并返回数据
        $userData = Common::validateViewAuth();
        if ($userData[0] == false) {
            return Common::jumpUrl('/admin/login/index', '请先登入');
        }

        //获取列表
        $listNum = 12; //每页个数
        $list = Db::table('user')
            ->paginate($listNum, true);
        View::assign([
            'list'  => $list,
            'listNum'  => $listNum
        ]);

        //基础变量
        View::assign([
            'adminData'  => $userData[1],
            'systemVer' => Common::systemVer(),
            'viewTitle'  => '用户管理'
        ]);

        //输出模板
        return View::fetch('/user/index');
    }

    //order
    public function order()
    {
        //验证身份并返回数据
        $userData = Common::validateViewAuth();
        if ($userData[0] == false) {
            return Common::jumpUrl('/admin/login/index', '请先登入');
        }

        //获取列表
        $listNum = 12; //每页个数
        $list = Db::table('orders')
            ->paginate($listNum, true);
        View::assign([
            'list'  => $list,
            'listNum'  => $listNum
        ]);

        //基础变量
        View::assign([
            'adminData'  => $userData[1],
            'systemVer' => Common::systemVer(),
            'viewTitle'  => '订单管理'
        ]);

        //输出模板
        return View::fetch('/user/order');
    }
}
