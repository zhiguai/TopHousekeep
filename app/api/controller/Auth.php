<?php

namespace app\api\controller;

//TP类
use think\facade\Request;
use think\exception\ValidateException;
use think\facade\Db;

//验证类
use app\api\validate\Admin as AdminValidate;

//类
use app\Common\Common;


class Auth
{

    //登入-POST
    public function login()
    {
        $name = Request::param('name');
        $password = Request::param('password');

        //验证参数是否合法
        try {
            validate(AdminValidate::class)->batch(true)
                ->scene('login')
                ->check([
                    'name'  => $name,
                    'password'   => $password,
                ]);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            $uservalidateerror = $e->getError();
            return Common::create($uservalidateerror, '登入失败', 401);
        }

        //获取数据对象
        $result = Db::table('admin')
            ->where('name', $name)
            ->where('password', sha1($password));

        //验证账号是否否存在
        if (empty($result->find())) {
            return Common::create([], '用户名或密码错误', 401);
        } else {
            //整理数据
            $uuid = Common::get_uuid();
            //更新uuid
            $result->update(['uuid' => $uuid]);
            //整理返回数据
            $data = [
                'uuid' => $uuid
            ];
            //返回数据
            return Common::create($data, '登录成功', 200);
        }
    }

    //注销-POST
    public function logout()
    {
        //验证身份并返回数据
        $adminData = Common::validateAuth();
        if (!empty($adminData[0])) {
            return Common::create([], $adminData[1], $adminData[0]);
        }

        //获取数据对象
        $result = Db::table('admin')
            ->where('id', $adminData['id']);

        //整理数据
        $uuid = Null;
        //更新uuid
        $result->update(['uuid' => $uuid]);

        //返回数据
        return Common::create([], '注销成功', 200);
    }
}
