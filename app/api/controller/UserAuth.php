<?php

namespace app\api\controller;

//TP类
use think\facade\Request;
use think\exception\ValidateException;
use think\facade\Db;
use think\facade\Cache;

//验证类
use app\api\validate\User as UserValidate;

//类
use app\Common\Common;


class UserAuth
{
    //默认注册账户封禁状态ON/OFF:0/1
    const DefSetUserBan = 'false';

    //设置登入凭证
    protected static function setUuid($id, $time)
    {
        //整理数据
        $uuid = Common::get_uuid();
        //设置uuid
        if (Cache::store('redis')->set($uuid, $id, $time)) {
            //整理返回数据
            $data = [
                'uuid' => $uuid
            ];
        } else {
            $data = false;
        }
        return $data;
    }

    //登入-POST
    public function login()
    {
        $email = Request::param('email');
        $password = Request::param('password');

        //验证参数是否合法
        try {
            validate(UserValidate::class)->batch(true)
                ->scene('login')
                ->check([
                    'email'  => $email,
                    'password'   => $password,
                ]);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            $uservalidateerror = $e->getError();
            return Common::create($uservalidateerror, '登入失败', 401);
        }

        //获取数据对象
        $result = Db::table('user')
            ->where('email', $email)
            ->where('password', sha1($password));

        //验证账号是否否存在
        if (empty($result->find())) {
            return Common::create([], '用户名或密码错误', 401);
        } else {
            //更新登入记录
            if (!$result->update(['login_date' => date('Y/m/d H:i:s')])) {
                return Common::create($result, '登录失败', 500);
            }

            //设置登入凭证
            $data = self::setUuid($result->value('id'), 3600 * 3);
            //返回数据
            if (!$data) {
                return Common::create($data, '登录失败', 500);
            }
            return Common::create($data, '登录成功', 200);
        }
    }

    //注册-POST
    public function register()
    {
        $name = Request::param('name');
        $password = Request::param('password');
        $email = Request::param('email');
        $phone = Request::param('phone');

        //验证参数是否合法
        try {
            validate(UserValidate::class)->batch(true)
                ->scene('add')
                ->check([
                    'email' => $email,
                    'name'  => $name,
                    'password'   => $password,
                    'phone'   => $phone,
                ]);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            $uservalidateerror = $e->getError();
            return Common::create($uservalidateerror, '注册失败', 401);
        }

        //获取数据库对象
        $result = Db::table('user');
        $time = date('Y/m/d H:i:s');
        $ip = Common::getIp();
        //整理数据
        $data = [
            'name' => $name,
            'password' => sha1($password),
            'email' => $email,
            'phone' => $phone,
            'ban' => self::DefSetUserBan,
            'login_date' => $time,
            'registration_date' => $time,
            'ip' => $ip,
        ];
        //写入库
        $id = $result->insertGetId($data);
        if (!$id) {
            return Common::create([], '写入失败', 501);
        } else {
            //设置登入凭证
            $data = self::setUuid($id, 3600 * 3);
            //返回数据
            if (!$data) {
                return Common::create($data, '注册成功，登入失败', 500);
            }
            //返回数据
            return Common::create($data, '注册成功，跳转成功', 200);
        }
    }

    //注销-POST
    public function logout()
    {
        //验证身份并返回数据
        $userData = Common::validateUserAuth();
        if (!empty($userData[0])) {
            //验证失败返回提示
            return Common::create([], $userData[1], $userData[0]);
        }

        $uuid = Request::param('user_uuid');

        //清除uuid
        if (Cache::store('redis')->delete($uuid)) {
            return Common::create([], '注销成功', 200);
        }

        //返回数据
        return Common::create([], '注销失败', 500);
    }
}
