<?php

namespace app\api\validate;

use think\Validate;

class User extends Validate
{
    //定义验证规则
    protected $rule =   [
        'email'  => 'require|email|unique:user',
        'name'  => 'require|length:3,12',
        'password'   => 'require|length:5,12',
        'phone'   => 'require|length:11|unique:user',
    ];

    //定义错误信息
    protected $message  =   [
        'email.require' => '邮箱不得为空',
        'email.email' => '邮箱格式错误',
        'email.unique' => '邮箱已存在',

        'name.require' => '用户名不得为空',
        'name.length'     => '用户名超出范围(3-12)',

        'password.require' => '密码不得为空',
        'password.length'     => '密码超出范围(5-12)',

        'phone.require' => '电话号不得为空',
        'phone.length'     => '电话号超出范围(5-12)',
        'phone.unique' => '电话号已存在',
    ];

    //验证场景-登入
    protected function sceneLogin()
    {
        return $this->only(['email', 'password'])
            ->remove('email', 'unique');
    }

    //验证场景-添加
    protected function sceneAdd()
    {
        return $this->only(['email', 'name', 'password', 'phone']);
    }

    //验证场景-编辑
    protected function sceneEdit()
    {
        return $this->only(['name', 'password', 'password', 'phone'])
            ->remove('password', 'require');
    }
}
