<?php

namespace app\api\controller;

//TP类
use think\facade\Request;
use think\exception\ValidateException;
use think\facade\Db;

//验证类
use app\api\validate\User as UserValidate;

//类
use app\Common\Common;

class User
{

    //更新-POST
    public function edit()
    {
        //防手抖
        $preventClicks = Common::preventClicks('LastPostTime');
        if ($preventClicks[0] == false) {
            //返回数据
            return Common::create(['prompt' => $preventClicks[1]], '添加失败', 500);
        }

        $user_uuid = Request::param('user_uuid');

        $uid = Request::param('uid');
        $ban = Request::param('ban');
        $name = Request::param('name');
        $email = Request::param('email');
        $phone = Request::param('phone');
        $password = Request::param('password');

        $data = [
            'email' => $email,
            'name'  => $name,
            'password'   => $password,
            'phone'   => $phone,
        ];

        if ($user_uuid != '') {
            //验证用户身份并返回数据
            $userData = Common::validateUserAuth();
            if (!empty($userData[0])) {
                return Common::create([], $userData[1], $userData[0]);
            }
        } else {
            //验证管理员身份并返回数据
            $adminData = Common::validateAuth();
            if (!empty($adminData[0])) {
                return Common::create([], $adminData[1], $adminData[0]);
            }
            $data['ban'] = $ban;
        }

        //获取数据库对象
        $result = Db::table('user')->where('id', $uid);
        $resultID = $result->find();
        if (!$resultID) {
            return Common::create([], 'id不存在', 400);
        }

        //是否修改电话
        if ($phone == $resultID['phone']) {
            unset($data['phone']);
        }

        //是否修改邮箱
        if ($email == $resultID['email']) {
            unset($data['email']);
        }

        //验证参数是否合法
        try {
            validate(UserValidate::class)->batch(true)
                ->scene('edit')
                ->check($data);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            $uservalidateerror = $e->getError();
            return Common::create($uservalidateerror, '更新失败', 401);
        }

        //是否修改密码
        if ($password == '') {
            unset($data['password']);
        } else {
            $data['password'] = sha1($data['password']);
        }

        //user写入库
        if (!$result->update($data)) {
            return Common::create(['user' => '更新失败'], '更新失败', 400);
        }
        return Common::create([], '更新成功', 200);
    }

    //删除-POST
    public function delete()
    {

        //验证身份并返回数据
        $adminData = Common::validateAuth();
        if (!empty($adminData[0])) {
            return Common::create([], $adminData[1], $adminData[0]);
        }

        //获取数据
        $id = Request::param('id');

        //获取数据库对象
        $result = Db::table('user')->where('id', $id);
        if (!$result->find()) {
            return Common::create([], 'id不存在', 400);
        }
        $result->delete();

        //返回数据
        return Common::create([], '删除成功', 200);
    }
}
