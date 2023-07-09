<?php

namespace app\index\controller;

//TP类
use think\facade\View;
use think\facade\Db;
use think\facade\Request;

//类
use app\common\Common;

class User
{

    //获取模板路径
    var $TemplateDirectoryPath;
    var $TemplateDirectory;
    function __construct()
    {
        $this->TemplateDirectoryPath = Common::get_templateDirectory()[0];
        $this->TemplateDirectory = Common::get_templateDirectory()[1];
    }

    //用户中心
    public function index()
    {
        //验证身份并返回数据
        $userData = Common::validateViewUserAuth();
        if ($userData[0] == false) {
            //跳转返回消息
            return Common::jumpUrl('/index/user/login', '请先登入');
        }

        //基础变量
        View::assign([
            'userData'  => $userData[1],
            'TemplateDirectory' => '/view/index/' . $this->TemplateDirectory . '/assets',
            'systemVer' => Common::systemVer(),
            'systemData' => Common::systemData(),
            'viewTitle'  => '用户中心',
            'viewDescription' => false,
            'viewKeywords' => false
        ]);

        //输出模板
        return View::fetch($this->TemplateDirectoryPath . '/user/index');
    }

    //编辑信息
    public function edit()
    {
        //验证身份并返回数据
        $userData = Common::validateViewUserAuth();
        if ($userData[0] == false) {
            //跳转返回消息
            return Common::jumpUrl('/index/user/login', '请先登入');
        }

        //基础变量
        View::assign([
            'userData'  => $userData[1],
            'TemplateDirectory' => '/view/index/' . $this->TemplateDirectory . '/assets',
            'systemVer' => Common::systemVer(),
            'systemData' => Common::systemData(),
            'viewTitle'  => '编辑信息',
            'viewDescription' => false,
            'viewKeywords' => false
        ]);

        //输出模板
        return View::fetch($this->TemplateDirectoryPath . '/user/user-edit');
    }

    //订单详情
    public function order()
    {
        //验证身份并返回数据
        $userData = Common::validateViewUserAuth();
        if ($userData[0] == false) {
            //跳转返回消息
            return Common::jumpUrl('/index/user/login', '请先登入');
        }

        $number = Request::param('number');

        //取Order数据
        $result = Db::table('orders')
            ->alias('a')
            ->where('a.ban', 'false')
            ->where('a.uid', $userData[1]['id'])
            ->where('number', $number)
            ->leftJoin('cards b', 'a.cid = b.id')
            ->field([
                'a.id' => 'id',
                'a.cid' => 'cid',
                'a.uid' => 'uid',
                'a.number' => 'number',
                'a.price' => 'price',
                'a.address' => 'address',
                'a.note' => 'note',
                'a.service_date' => 'service_date',
                'a.status' => 'status',
                'a.creat_date' => 'creat_date',
                'a.ban' => 'ban',
                'b.img' => 'c_img',
                'b.introduction' => 'c_introduction',
                'b.good' => 'c_good',
                'b.content' => 'c_content'
            ])->findOrEmpty();
        if (!$result) {
            return Common::jumpUrl('/index/Orders', '订单不存在，可能已被下架');
        }
        $orderData = $result;

        //获取good数据库对象
        $goodData = $orderData['cid'];
        $resultGood = Db::table('good');
        if ($resultGood->where('pid', $goodData)->where('uid', $userData[1]['id'])->find()) {
            $goodData = 'false';
        }


        //取comments数据
        $listNum = 6; //每页个数
        $result = Db::table('cards_comments')
            ->alias('a')
            ->where('a.ban', 'false')
            ->where('a.number', $orderData['number'])
            ->where('a.uid', $userData[1]['id'])
            ->where('a.cid', $orderData['cid'])
            ->paginate($listNum, true);
        $cardsCommentsListRaw = $result->render();
        $listData = $result->items();

        //评论列表变量
        View::assign([
            'cardsCommentsListRaw'  => $cardsCommentsListRaw,
            'cardsCommentsListData'  => $listData,
            'cardsCommentsListNum'  => $listNum
        ]);

        //Order
        View::assign([
            'orderData' => $orderData,
            'goodData' => $goodData
        ]);

        //基础变量
        View::assign([
            'userData'  => $userData[1],
            'TemplateDirectory' => '/view/index/' . $this->TemplateDirectory . '/assets',
            'systemVer' => Common::systemVer(),
            'systemData' => Common::systemData(),
            'viewTitle'  => '查看订单',
            'viewDescription' => false,
            'viewKeywords' => false
        ]);

        //输出模板
        return View::fetch($this->TemplateDirectoryPath . '/user/order');
    }

    //订单列表
    public function orders()
    {
        //验证身份并返回数据
        $userData = Common::validateViewUserAuth();
        if ($userData[0] == false) {
            //跳转返回消息
            return Common::jumpUrl('/index/user/login', '请先登入');
        }

        $chooseStatus = Request::param('chooseStatus');

        //取Order列表数据
        $listNum = 12; //每页个数

        $field = [
            'a.id' => 'id',
            'a.cid' => 'cid',
            'a.uid' => 'uid',
            'a.number' => 'number',
            'a.price' => 'price',
            'a.address' => 'address',
            'a.note' => 'note',
            'a.service_date' => 'service_date',
            'a.status' => 'status',
            'a.creat_date' => 'creat_date',
            'a.ban' => 'ban',
            'b.img' => 'c_img',
            'b.introduction' => 'c_introduction',
            'b.content' => 'c_content'
        ];
        function OrderStatueResult($status, $userData, $field, $listNum)
        {
            $result = Db::table('orders')
                ->alias('a')
                ->where('a.ban', 'false')
                ->where('a.status', $status)
                ->where('a.uid', $userData[1]['id'])
                ->leftJoin('cards b', 'a.cid = b.id')
                ->field($field)
                ->order('a.id', 'desc')
                ->paginate($listNum, true);
            return $result;
        }

        if ($chooseStatus == 1) {
            //待受理
            $result = OrderStatueResult('0', $userData, $field, $listNum);
        } else if ($chooseStatus == 2) {
            //待服务
            $result = OrderStatueResult('1', $userData, $field, $listNum);
        } else if ($chooseStatus == 3) {
            //待确认
            $result = OrderStatueResult('3', $userData, $field, $listNum);
        } else if ($chooseStatus == 4) {
            //评价
            $result = OrderStatueResult('4', $userData, $field, $listNum);
        } else {
            $chooseStatus = 5;
            //全部
            $result = Db::table('orders')
                ->alias('a')
                ->where('a.ban', 'false')
                ->where('a.uid', $userData[1]['id'])
                ->leftJoin('cards b', 'a.cid = b.id')
                ->field($field)
                ->order('a.id', 'desc')
                ->paginate($listNum, true);
        }

        $ordersListRaw = $result->render();
        $listData = $result->items();

        //Order分页变量;
        View::assign([
            'ordersListRaw'  => $ordersListRaw,
            'ordersListData'  => $listData,
            'ordersListNum'  => $listNum
        ]);

        //chooseStatus
        View::assign('chooseStatus', $chooseStatus);

        //基础变量
        View::assign([
            'userData'  => $userData[1],
            'TemplateDirectory' => '/view/index/' . $this->TemplateDirectory . '/assets',
            'systemVer' => Common::systemVer(),
            'systemData' => Common::systemData(),
            'viewTitle'  => '查看订单',
            'viewDescription' => false,
            'viewKeywords' => false
        ]);

        //输出模板
        return View::fetch($this->TemplateDirectoryPath . '/user/orders');
    }

    //评价列表
    public function comments()
    {
        //验证身份并返回数据
        $userData = Common::validateViewUserAuth();
        if ($userData[0] == false) {
            //跳转返回消息
            return Common::jumpUrl('/index/user/login', '请先登入');
        }

        //列表数据
        $listNum = 12; //每页个数

        //全部
        $result = Db::table('cards_comments')
            ->alias('a')
            ->where('a.ban', 'false')
            ->where('a.uid', $userData[1]['id'])
            ->leftJoin('cards b', 'a.cid = b.id')
            ->field([
                'a.id' => 'id',
                'a.cid' => 'cid',
                'a.uid' => 'uid',
                'a.number' => 'number',
                'a.content' => 'content',
                'a.name' => 'name',
                'a.ip' => 'ip',
                'a.date' => 'date',
                'b.content' => 'b_content',
                'b.introduction' => 'b_introduction',
                'b.img' => 'b_img',
                'b.price' => 'b_price',
                'b.good' => 'b_good',
                'b.comments' => 'b_comments',
                'b.look' => 'c_look'
            ])
            ->order('a.id', 'desc')
            ->paginate($listNum, true);

        $commentsListRaw = $result->render();
        $listData = $result->items();

        //分页变量;
        View::assign([
            'commentsListRaw'  => $commentsListRaw,
            'commentsListData'  => $listData,
            'commentsListNum'  => $listNum
        ]);

        //基础变量
        View::assign([
            'userData'  => $userData[1],
            'TemplateDirectory' => '/view/index/' . $this->TemplateDirectory . '/assets',
            'systemVer' => Common::systemVer(),
            'systemData' => Common::systemData(),
            'viewTitle'  => '全部评价',
            'viewDescription' => false,
            'viewKeywords' => false
        ]);

        //输出模板
        return View::fetch($this->TemplateDirectoryPath . '/user/comments');
    }

    //推荐列表
    public function goods()
    {
        //验证身份并返回数据
        $userData = Common::validateViewUserAuth();
        if ($userData[0] == false) {
            //跳转返回消息
            return Common::jumpUrl('/index/user/login', '请先登入');
        }

        //取Order列表数据
        $listNum = 12; //每页个数

        //全部
        $result = Db::table('good')
            ->alias('a')
            ->where('a.uid', $userData[1]['id'])
            ->leftJoin('cards b', 'a.pid = b.id')
            ->field([
                'a.id' => 'id',
                'a.pid' => 'cid',
                'a.uid' => 'uid',
                'a.ip' => 'ip',
                'a.date' => 'date',
                'b.content' => 'b_content',
                'b.introduction' => 'b_introduction',
                'b.img' => 'b_img',
                'b.price' => 'b_price',
                'b.good' => 'b_good',
                'b.comments' => 'b_comments',
                'b.look' => 'c_look'
            ])
            ->order('a.id', 'desc')
            ->paginate($listNum, true);



        $goodsListRaw = $result->render();
        $listData = $result->items();

        //Order分页变量;
        View::assign([
            'goodsListRaw'  => $goodsListRaw,
            'goodsListData'  => $listData,
            'goodsListNum'  => $listNum
        ]);

        //基础变量
        View::assign([
            'userData'  => $userData[1],
            'TemplateDirectory' => '/view/index/' . $this->TemplateDirectory . '/assets',
            'systemVer' => Common::systemVer(),
            'systemData' => Common::systemData(),
            'viewTitle'  => '全部推荐',
            'viewDescription' => false,
            'viewKeywords' => false
        ]);

        //输出模板
        return View::fetch($this->TemplateDirectoryPath . '/user/goods');
    }

    //登入
    public function login()
    {
        //验证身份并返回数据
        $userData = Common::validateViewUserAuth();
        if ($userData[0] == true) {
            //跳转返回消息
            return Common::jumpUrl('/index/user');
        }

        //基础变量
        View::assign([
            'TemplateDirectory' => '/view/index/' . $this->TemplateDirectory . '/assets',
            'systemVer' => Common::systemVer(),
            'systemData' => Common::systemData(),
            'viewTitle'  => '登入',
            'viewDescription' => false,
            'viewKeywords' => false
        ]);

        //输出模板
        return View::fetch($this->TemplateDirectoryPath . '/user/login');
    }

    //注册
    public function register()
    {
        //验证身份并返回数据
        $userData = Common::validateViewUserAuth();
        if ($userData[0] == true) {
            //跳转返回消息
            return Common::jumpUrl('/index/user');
        }

        //基础变量
        View::assign([
            'TemplateDirectory' => '/view/index/' . $this->TemplateDirectory . '/assets',
            'systemVer' => Common::systemVer(),
            'systemData' => Common::systemData(),
            'viewTitle'  => '注册',
            'viewDescription' => false,
            'viewKeywords' => false
        ]);

        //输出模板
        return View::fetch($this->TemplateDirectoryPath . '/user/register');
    }
}
