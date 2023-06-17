<?php

namespace app\index\controller;

//TP类
use think\facade\View;
use think\facade\Db;

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

        if($userData[1]['avatar'] == ''){
            $userData[1]['avatar'] = 'https://img1.imgtp.com/2023/06/15/By2uYcJf.png';
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

    //订单列表
    public function order()
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
            'viewTitle'  => '查看订单',
            'viewDescription' => false,
            'viewKeywords' => false
        ]);

        //输出模板
        return View::fetch($this->TemplateDirectoryPath . '/user/order');
    }

    //登入
    public function login()
    {

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
