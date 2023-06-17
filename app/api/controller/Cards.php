<?php

namespace app\api\controller;

//TP类
use think\facade\Request;
use think\exception\ValidateException;
use think\facade\Db;

//验证类
use app\api\validate\Cards as CardsValidate;

//公共类
use app\Common\Common;


class Cards
{
    //默认卡片封禁状态ON/OFF:0/1
    const DefSetCardsBan = false;
    //默认卡片展示状态ON/OFF:0/1
    const DefSetCardsStatus = false;
    //默认添加卡片上传图片个数
    const DefSetCardsImgNum = 9;
    //默认添加卡片标签个数
    const DefSetCardsTagNum = 3;

    //添加-POST
    public function add()
    {
        //防手抖
        $preventClicks = Common::preventClicks('LastPostTime');
        if ($preventClicks[0] == false) {
            //返回数据
            return Common::create(['prompt' => $preventClicks[1]], '添加失败', 500);
        }

        //获取数据
        $uid = '0';

        $price = Request::param('price');
        $content = Request::param('content');
        $introduction = Request::param('introduction');

        $img = json_decode(Request::param('img'), true);
        $tag = json_decode(Request::param('tag'), true);
        //判断是否上传图片
        if (empty($img)) {
            return Common::create(['img' => '请上传图片'], '添加失败', 400);
        }

        $status = self::DefSetCardsStatus;
        $ban = self::DefSetCardsBan;

        //免验证
        $time = date('Y-m-d H:i:s');
        $ip = Common::getIp();

        //验证参数是否合法
        try {
            $tryData = [
                'uid' => $uid,
                'content' => $content,
                'introduction' => $introduction,

                'price' => $price,

                'ban' => $ban,
                'status' => $status
            ];
            validate(CardsValidate::class)->batch(true)
                ->check($tryData);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            $cardsvalidateerror = $e->getError();
            return Common::create($cardsvalidateerror, '添加失败', 400);
        }

        //获取Cards数据库对象
        $result = Db::table('cards');

        //判断卡模式
        $data = array(); //清空数组
        //构建数据格式
        $data = [
            'uid' => $uid,
            'price' => $price,
            'content' => $content,
            'introduction' => $introduction,

            'status' => var_export($status, true),
            'ban' => var_export($ban, true),

            'date' => $time,
            'ip' => $ip,
        ];

        //Cards写入库
        $CardId = $result->insertGetId($data);
        //Cards写入失败返回
        if (!$CardId) {
            return Common::create(['cards' => '写入失败'], '添加失败', 400);
        }

        //判断是否上传图片
        if (!empty($img)) {
            //判断图片数量
            if (sizeof($img) > self::DefSetCardsImgNum) {
                return Common::create(['img' => 'img超过数量限制'], '添加失败', 400);
            }
            //获取img数据库对象
            $result = Db::table('img');
            //构建数据数组
            $data = array(); //清空数组
            foreach ($img as $key => $value) {
                $data[$key]['aid'] = 1;
                $data[$key]['pid'] = $CardId;
                $data[$key]['url'] = $value;
                $data[$key]['date'] = $time;
            }
            //img写入数据库若失败返回
            if (!$result->insertAll($data)) {
                return Common::create(['img' => 'img写入失败'], '添加失败', 400);
            }

            //获取card数据库对象
            $result = Db::table('cards');
            //cards更新数据库若失败返回
            if (!$result->where('id', $CardId)->update(['img' => $img[0]])) {
                return Common::create(['cards.img' => 'cards.img更新失败'], '添加失败', 400);
            }
        }

        //判断是否选择标签
        if (!empty($tag)) {
            //判断图片数量
            if (sizeof($tag) > self::DefSetCardsTagNum) {
                return Common::create(['tag' => 'tag超过数量限制'], '添加失败', 400);
            }
            //获取tag_map数据库对象
            $result = Db::table('cards_tag_map');
            //构建数据数组
            $data = array(); //清空数组
            foreach ($tag as $key => $value) {
                $data[$key]['cid'] = $CardId;
                $data[$key]['tid'] = $value;
                $data[$key]['date'] = $time;
            }
            //tag写入数据库若失败返回
            if (!$result->insertAll($data)) {
                return Common::create(['tag' => 'tag写入失败'], '添加失败', 400);
            }

            //获取card数据库对象
            $result = Db::table('cards');
            //cards更新数据库若失败返回
            if (!$result->where('id', $CardId)->update(['tag' => Json_encode($tag)])) {
                return Common::create(['cards.tag' => 'cards.tag更新失败'], '添加失败', 400);
            }
        }
        //返回数据
        return Common::create(['id' => $CardId], '添加成功', 200);
    }

    //编辑-POST
    public function edit()
    {

        //验证身份并返回数据
        $adminData = Common::validateAuth();
        if (!empty($adminData[0])) {
            return Common::create([], $adminData[1], $adminData[0]);
        }

        //获取数据
        $id = Request::param('id');
        $uid = '0';
        $price = Request::param('price');
        $content = Request::param('content');
        $introduction = Request::param('introduction');

        $img = json_decode(Request::param('img'), true);
        
        //判断是否上传图片
        if (sizeof($img) < 1) {
            return Common::create(['img' => '请上传图片'], '编辑失败', 400);
        }
        //判断图片数量
        if (sizeof($img) > self::DefSetCardsImgNum) {
            return Common::create(['img' => 'img超过数量限制'], '编辑失败', 400);
        }
        $tag = json_decode(Request::param('tag'), true);
        //判断Tag数量
        if (sizeof($tag) > self::DefSetCardsTagNum) {
            return Common::create(['tag' => 'tag超过数量限制'], '编辑失败', 400);
        }

        $top = (Request::param('top') != 'false');
        $ban = (Request::param('ban') != 'false');
        $status = (Request::param('status') != 'false');

        //免验证
        $time = date('Y-m-d H:i:s');
        $ip = Common::getIp();

        //验证参数是否合法
        try {
            $tryData = [
                'uid' => $uid,
                'content' => $content,
                'introduction' => $introduction,

                'price' => $price,

                'ban' => $ban,
                'status' => $status
            ];
            validate(CardsValidate::class)->batch(true)
                ->check($tryData);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            $cardsvalidateerror = $e->getError();
            return Common::create($cardsvalidateerror, '添加失败', 400);
        }
        //获取Cards数据库对象
        $result = Db::table('cards')->where('id', $id);
        $resultCardData = $result->find();
        if (!$resultCardData) {
            return Common::create([], 'id不存在', 400);
        }

        //判断卡模式
        $data = array(); //清空数组
        //构建数据格式
        $data = [
            'uid' => $uid,
            'price' => $price,
            'content' => $content,
            'introduction' => $introduction,

            'top' => var_export($top, true),
            'status' => var_export($status, true),
            'ban' => var_export($ban, true),

            'date' => $time,
            'ip' => $ip,
        ];
        //Cards写入库
        if (!$result->update($data)) {
            return Common::create(['cards' => '更新失败'], '编辑失败', 400);
        }

        //获取img数据库对象
        $result = Db::table('img');
        //清理原始数据
        $resultCardId = $result->where('pid', $id);
        if ($resultCardId->find()) {
            $resultCardId->delete();
        }
        //判断是否写入新数据
        if (!empty($img)) {
            //清空数组
            $data = array();
            //构建数据数组
            foreach ($img as $key => $value) {
                $data[$key]['aid'] = 1;
                $data[$key]['pid'] = $id;
                $data[$key]['url'] = $value;
                $data[$key]['date'] = $time;
            }

            //img写入数据库若失败返回
            if (!$result->insertAll($data)) {
                return Common::create(['img' => '写入失败'], '编辑失败', 400);
            }
            //判断是否需要更新视图字段
            if ($resultCardData['img'] != $img[0]) {
                //获取card数据库对象
                $result = Db::table('cards');
                //cards更新数据库若失败返回
                if (!$result->where('id', $id)->update(['img' => $img[0]])) {
                    return Common::create(['cards.img' => '更新失败'], '添加失败', 400);
                }
            }
        }

        //获取tag数据库对象
        $result = Db::table('cards_tag_map');
        //清理原始数据
        $resultCardId = $result->where('cid', $id);
        if ($resultCardId->find()) {
            $resultCardId->delete();
        }
        //判断是否写入新数据
        if (!empty($tag)) {
            //清空数组
            $data = array();
            //构建数据数组
            foreach ($tag as $key => $value) {
                $data[$key]['cid'] = $id;
                $data[$key]['tid'] = $value;
                $data[$key]['date'] = $time;
            }
            //tag_map写入数据库若失败返回
            if (!$result->insertAll($data)) {
                return Common::create(['img' => '写入失败'], '添加失败', 400);
            }
            //判断是否需要更新视图字段
            if ($resultCardData['tag'] != Json_encode($tag)) {
                //获取card数据库对象
                $result = Db::table('cards');
                //cards更新数据库若失败返回
                if (!$result->where('id', $id)->update(['tag' => Json_encode($tag)])) {
                    return Common::create(['cards.tag' => 'cards.tag更新失败'], '添加失败', 400);
                }
            }
        }

        //返回数据
        return Common::create(['id' => $id], '编辑成功', 200);
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

        //获取Cards数据库对象
        $result = Db::table('cards')->where('id', $id);
        if (!$result->find()) {
            return Common::create([], 'id不存在', 400);
        }
        $result->delete();

        //获取img数据库对象
        $result = Db::table('img')->where('pid', $id);
        if ($result->find()) {
            $result->delete();
        }

        //获取tag数据库对象
        $result = Db::table('cards_tag_map')->where('cid', $id);
        if ($result->find()) {
            $result->delete();
        }

        //获取comments数据库对象
        $result = Db::table('cards_comments')->where('cid', $id);
        if ($result->find()) {
            $result->delete();
        }

        //返回数据
        return Common::create([], '删除成功', 200);
    }

    //推荐-POST
    public function good()
    {
        //获取数据
        $id = Request::param('id');
        $ip = Common::getIp();
        $time = date('Y-m-d H:i:s');

        //获取Cards数据库对象
        $resultCards = Db::table('cards')->where('id', $id);
        $resultCardsData = $resultCards->find();
        if (!$resultCardsData) {
            return Common::create([], 'id不存在', 400);
        }

        //获取good数据库对象
        $resultGood = Db::table('good');
        if ($resultGood->where('pid', $id)->where('ip', $ip)->find()) {
            return Common::create(['tip' => '请勿重复点赞'], '点赞失败', 400);
        }

        //更新视图字段
        if (!$resultCards->inc('good')->update()) {
            return Common::create(['cards.good' => 'cards.good更新失败'], '点赞失败', 400);
        };

        $data = ['aid' => '1', 'pid' => $id, 'ip' => $ip, 'time' => $time];
        if (!$resultGood->insert($data)) {
            return Common::create(['good' => 'good写入失败'], '点赞失败', 400);
        };

        //返回数据
        return Common::create(['Num' => $resultCardsData['good'] + 1], '点赞成功', 200);
    }
}
