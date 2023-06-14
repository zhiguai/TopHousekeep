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
        //安装检测
        @$file = fopen("../lock.txt", "r");
        if (!$file) {
            header("location:/system/install");
            exit;
        }

        $this->TemplateDirectoryPath = Common::get_templateDirectory()[0];
        $this->TemplateDirectory = Common::get_templateDirectory()[1];
    }

    //输出
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
        return View::fetch($this->TemplateDirectoryPath . '/login');
    }

    //输出
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
        return View::fetch($this->TemplateDirectoryPath . '/login');
    }   
}
