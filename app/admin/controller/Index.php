<?php

namespace app\admin\controller;

//TP类
use think\facade\View;
use think\facade\Db;

//类
use app\common\Common;

class Index
{

    //Index
    public function index()
    {
        //验证身份并返回数据
        $adminData = Common::validateViewAuth();
        if ($adminData[0] == false) {
            //跳转返回消息
            return Common::jumpUrl('/admin/login/index', '请先登入');
        }

        //函数-取图表数据
        function ChartData($key,$dataKey = 'date')
        {
            for ($i = 1; $i <= 6; $i++) {
                $time = date('Y-m-d', strtotime('-' . $i . 'day'));
                $arr[0][$i] = Db::table($key)->whereDay($dataKey, $time)->count();
                $arr[1][$i] = $time;
                if($i==1)$arr[1][$i] = '昨日';
            }
            $arr[0] = Array_reverse($arr[0]);
            $arr[1] = Array_reverse($arr[1]);
            return $arr;
        }

        //取总览数据
        $dataNum = [
            'cards' => Db::table('cards')->count(),
            'cardsComments' => Db::table('cards_comments')->count(),
            'good' => Db::table('good')->count()
        ];
        //取图表数据
        $dataChart = [ChartData('orders','creat_date'), ChartData('cards_comments'), ChartData('user','registration_date')];

        //取今日新增订单
        $nowDate = date('Y-m-d');
        $orderData0 = Db::table('orders')
        ->whereDay('creat_date', $nowDate)
        ->select()
        ->toArray();
        //未受理订单
        $orderData1 = Db::table('orders')
        ->where('status',0)
        ->select()
        ->toArray();
        //进行中订单
        $orderData2 = Db::table('orders')
        ->where('status',2)
        ->select()
        ->toArray();
        
        View::assign([
            'dataNum' => $dataNum,
            'dataChart' => json_encode($dataChart),
            'orderData0' => $orderData0,
            'orderData1' => $orderData1,
            'orderData2' => $orderData2,
        ]);

        //基础变量
        View::assign([
            'adminData'  => $adminData[1],
            'systemVer' => Common::systemVer(),
            'systemData' => Common::systemData(),
            'viewTitle'  => '总览'
        ]);

        //输出模板
        return View::fetch('/index');
    }
}
