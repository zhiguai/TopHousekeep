<?php

namespace app\index\controller;

//TP类
use think\facade\View;
use think\facade\Db;
use think\facade\Request;

//类
use app\common\Common;

class Cards
{

    //获取模板路径
    var $TemplateDirectoryPath;
    var $TemplateDirectory;
    function __construct()
    {
        $this->TemplateDirectoryPath = Common::get_templateDirectory()[0];
        $this->TemplateDirectory = Common::get_templateDirectory()[1];
    }

    //Index
    public function index()
    {
        //参数
        $ip = Common::getIp();

        //取Cards列表数据
        $listNum = 12; //每页个数
        $result = Db::table('cards')->where('ban', 'false')->where('status', 'true')->order('id', 'desc')
            ->paginate($listNum, true);

        $cardsListRaw = $result->render();
        $listData = $result->items();

        //取标签数据
        $result = Db::table('cards_tag')->where('status', 'true')->select()->toArray();
        $cardsTagData = $result;
        View::assign('cardsTagData', json_encode($cardsTagData));

        //取Good状态合并到$listData数据
        for ($i = 0; $i < sizeof($listData); $i++) {
            $resultGood = Db::table('good')->where('aid', 1)->where('ip', $ip);
            //查找对应封面
            if ($resultGood->where('pid', $listData[$i]['id'])->findOrEmpty() == []) {
                //未点赞
                $listData[$i]['ipGood'] = false;
            } else {
                //已点赞
                $listData[$i]['ipGood'] = true;
            }
        }

        //Cards分页变量;
        View::assign([
            'cardsListRaw'  => $cardsListRaw,
            'cardsListData'  => $listData,
            'cardsListNum'  => $listNum
        ]);

        //基础变量
        View::assign([
            'TemplateDirectory' => '/view/index/' . $this->TemplateDirectory . '/assets',
            'systemVer' => Common::systemVer(),
            'systemData' => Common::systemData(),
            'viewTitle'  => '卡片墙',
            'viewDescription' => false,
            'viewKeywords' => false
        ]);

        //输出模板
        return View::fetch($this->TemplateDirectoryPath . '/cards');
    }

    //购买
    public function buy()
    {
        //验证身份并返回数据
        $userData = Common::validateViewUserAuth();
        if ($userData[0] == false) {
            //跳转返回消息
            return Common::jumpUrl('/index/user/login', '请先登入');
        }

        //参数
        $id = Request::param('id');

        //验证ID取Cards数据
        $result = Db::table('cards')->where('ban', 'false')->where('id', $id)->findOrEmpty();
        if (!$result) {
            return Common::jumpUrl('/index/Cards', '卡片ID不存在');
        }
        $cardData = $result;

        //卡片变量
        View::assign([
            'cardData' => $cardData
        ]);

        //基础变量
        View::assign([
            'userData'  => $userData[1],
            'TemplateDirectory' => '/view/index/' . $this->TemplateDirectory . '/assets',
            'systemVer' => Common::systemVer(),
            'systemData' => Common::systemData(),
            'viewTitle'  => '购买服务',
            'viewDescription' => false,
            'viewKeywords' => false
        ]);

        //输出模板
        return View::fetch($this->TemplateDirectoryPath . '/buy');
    }

    //卡片详情
    public function card()
    {
        //参数
        $ip = Common::getIp();
        $id = Request::param('id');

        //验证ID取Cards数据
        $result = Db::table('cards')->where('ban', 'false')->where('id', $id)->findOrEmpty();
        if (!$result) {
            return Common::jumpUrl('/index/Cards', '卡片不存在，可能已被下架');
        }
        $cardData = $result;

        //防止快速刷新网页增加浏览量
        $preventClicks = Common::preventClicks('LastGetTimeCardID' . $id, 60);
        if ($preventClicks[0] == true) {
            //获取Cards数据库对象
            $resultCards = Db::table('cards')->where('ban', 'false')->where('id', $id);
            //更新视图字段
            if (!$resultCards->inc('look')->update()) {
                return Common::create(['cards.look' => 'cards.look更新失败'], '无效浏览', 400);
            };
            $cardData['look'] = $cardData['look'] + 1;
        }

        //取img数据
        $result = Db::table('img')
        ->where('aid', 1)
        ->where('pid', $cardData['id'])
        ->order('id')
        ->select()
        ->toArray();
        $imgData = $result;
        $cardImgUrl = array_pop($imgData);
        if($cardImgUrl == null){
            $cardImgUrl = false;
        }

        //取Tag数据
        $result = Db::table('cards_tag')->where('status', 'true')->select()->toArray();
        $cardsTagData = $result;
        View::assign('cardsTagData', json_encode($cardsTagData));

        //获取评论列表
        $listNum = 6; //每页个数
        $result = Db::table('cards_comments')
            ->alias('a')
            ->where('a.cid', $id)
            ->where('a.ban', 'false')
            ->leftJoin('user b', 'a.uid = b.id')
            ->field([
                'a.id' => 'id',
                'a.uid' => 'uid',
                'a.cid' => 'cid',
                'a.content' => 'content',
                'a.name' => 'name',
                'a.ip' => 'ip',
                'a.date' => 'date',
                'b.name' => 'b_name',
                'b.avatar' => 'b_avatar'
            ])
            ->order('id', 'desc')
            ->paginate($listNum, true);
        $cardsCommentsListRaw = $result->render();
        $listData = $result->items();

        //取Good状态合并到$cardData数据
        if (Db::table('good')->where('aid', 1)->where('ip', $ip)->where('pid', $id)->findOrEmpty() == []) {
            //未点赞
            $cardData['ipGood'] = false;
        } else {
            //已点赞
            $cardData['ipGood'] = true;
        }

        //评论列表变量
        View::assign([
            'cardsCommentsListRaw'  => $cardsCommentsListRaw,
            'cardsCommentsListData'  => $listData,
            'cardsCommentsListNum'  => $listNum
        ]);

        //卡片变量
        View::assign([
            'cardData' => $cardData,
            'imgData' => $imgData,
            'cardImgUrl' => $cardImgUrl
        ]);

        //基础变量
        View::assign([
            'TemplateDirectory' => '/view/index/' . $this->TemplateDirectory . '/assets',
            'systemVer' => Common::systemVer(),
            'systemData' => Common::systemData(),
            'viewTitle'  => '商品介绍',
            'viewDescription' => $cardData['introduction'],
            'viewKeywords' => '完美家政,商品介绍'
        ]);

        //输出模板
        return View::fetch($this->TemplateDirectoryPath . '/card');
    }

    //卡片搜索
    public function search()
    {

        //参数
        $ip = Common::getIp();
        $search = Request::param('search');
        $viewTitle = '搜索';

        if ($search == true) {
            //参数
            $value = Request::param('value');
            $viewTitle = $value . '的搜索结果';

            //验证Value
            if (!$value) {
                return Common::jumpUrl('/index/Cards/search', '请输入要搜索内容');
            }

            $result = Db::table('cards')->where('ban', 'false')->where('status', 'true');

            //dd($result);
            //取Cards列表
            $listNum = 2; //每页个数
            $result = $result->where('id|content|introduction', 'like', '%' . $value . '%')->order('id', 'desc')
                ->paginate($listNum, true);
            $cardsListRaw = $result->render();
            $listData = $result->items();

            //组合Good状态到$listData列表
            for ($i = 0; $i < sizeof($listData); $i++) {
                $resultGood = Db::table('good')->where('aid', 1)->where('ip', $ip);
                //查找对应封面
                if ($resultGood->where('pid', $listData[$i]['id'])->findOrEmpty() == []) {
                    //未点赞
                    $listData[$i]['ipGood'] = false;
                } else {
                    //已点赞
                    $listData[$i]['ipGood'] = true;
                }
            }
        } else {
            //定义为空
            $cardsListRaw = null;
            $listData = [];
            $listNum = [];
        }

        //取Tag数据
        $result = Db::table('cards_tag')->where('status', 'true')->select()->toArray();
        $cardsTagData = $result;
        View::assign('cardsTagData', json_encode($cardsTagData));

        //Cards分页变量
        View::assign([
            'cardsListRaw'  => $cardsListRaw,
            'cardsListData'  => $listData,
            'cardsListNum'  => $listNum
        ]);

        //基础变量
        View::assign([
            'TemplateDirectory' => '/view/index/' . $this->TemplateDirectory . '/assets',
            'systemVer' => Common::systemVer(),
            'systemData' => Common::systemData(),
            'viewTitle'  => $viewTitle,
            'viewDescription' => false,
            'viewKeywords' => false
        ]);

        //输出模板
        return View::fetch($this->TemplateDirectoryPath . '/cards-search');
    }

    //TAGS集合
    public function tags()
    {

        //参数
        $ip = Common::getIp();
        //传入Tid
        $value = Request::param('value');

        //验证Value
        if ($value == '') {
            $viewTitle = '全部标签';
            $searchValue = false;
            $tagData = [];
        } else {
            $requestTag = Db::table('cards_tag')->where('id', $value)->findOrEmpty();
            if (!$requestTag) {
                return Common::jumpUrl('/index/Cards/tags', 'Tag已被删除或不存在');
            }
            $viewTitle = '关于' . $requestTag['name'] . '的卡片合集';
            $searchValue = $requestTag['name'];
            $tagData = $requestTag;
        }

        //取cards_tag_map列表
        $listNum = 3; //每页个数
        $result = Db::table('cards_tag_map')
            ->where('tid', $value)
            ->order('id', 'desc')
            ->paginate($listNum, true);
        $cardsListRaw = $result->render();
        $TaglistData = $result->items();
        $listData = [];

        //组合Good状态到$listData列表
        for ($i = 0; $i < sizeof($TaglistData); $i++) {
            //取Cards数据
            $requestCards = Db::table('cards')->where('ban', 'false')->where('status', 'true')->where('id', $TaglistData[$i]['cid'])->findOrEmpty();
            if ($requestCards) {
                $resultGood = Db::table('good')->where('aid', 1)->where('ip', $ip);
                //查找对应封面
                if ($resultGood->where('pid', $requestCards['id'])->findOrEmpty() == []) {
                    //未点赞
                    $requestCards['ipGood'] = false;
                } else {
                    //已点赞
                    $requestCards['ipGood'] = true;
                }
                //插入最终列表数据
                array_push($listData, $requestCards);
            }
        }

        //取Tag数据
        $result = Db::table('cards_tag')->where('status', 'true')->select()->toArray();
        $cardsTagData = $result;
        //View::assign('cardsTagData', json_encode($cardsTagData));
        View::assign('cardsTagData', $cardsTagData);

        //变量
        View::assign([
            'cardsListData'  => $listData,
            'tagData' => $tagData
        ]);

        //基础变量
        View::assign([
            'TemplateDirectory' => '/view/index/' . $this->TemplateDirectory . '/assets',
            'systemVer' => Common::systemVer(),
            'systemData' => Common::systemData(),
            'viewTitle'  => $viewTitle,
            'viewDescription' => false,
            'viewKeywords' => false,
            'searchValue'  => $searchValue
        ]);

        //输出模板
        return View::fetch($this->TemplateDirectoryPath . '/cards-tags');
    }

    //TAG集
    public function tag()
    {

        //参数
        $ip = Common::getIp();
        //传入Tid
        $value = Request::param('value');
        $value = 4;

        //验证Value
        if (!$value) {
            return Common::jumpUrl('/index/Cards/search', '请输入Tag');
        }
        $requestTag = Db::table('cards_tag')->where('id', $value)->findOrEmpty();
        if (!$requestTag) {
            return Common::jumpUrl('/index/Cards/search', 'Tag已被删除或不存在');
        }

        $viewTitle = '关于' . $requestTag['name'] . '的卡片合集';

        //取cards_tag_map列表
        $listNum = 12; //每页个数
        $result = Db::table('cards_tag_map')
            ->where('tid', $value)
            ->order('id', 'desc')
            ->paginate($listNum, true);
        $cardsListRaw = $result->render();
        $TaglistData = $result->items();
        $listData = [];

        //组合Good状态到$listData列表
        for ($i = 0; $i < sizeof($TaglistData); $i++) {
            //取Cards数据
            $requestCards = Db::table('cards')->where('ban', 'false')->where('status', 'true')->where('id', $TaglistData[$i]['cid'])->findOrEmpty();
            if ($requestCards) {
                $resultGood = Db::table('good')->where('aid', 1)->where('ip', $ip);
                //查找对应封面
                if ($resultGood->where('pid', $requestCards['id'])->findOrEmpty() == []) {
                    //未点赞
                    $requestCards['ipGood'] = false;
                } else {
                    //已点赞
                    $requestCards['ipGood'] = true;
                }
                //插入最终列表数据
                array_push($listData, $requestCards);
            }
        }

        //取Tag数据
        $result = Db::table('cards_tag')->where('status', 'true')->select()->toArray();
        $cardsTagData = $result;
        //View::assign('cardsTagData', json_encode($cardsTagData));
        View::assign('cardsTagData', $cardsTagData);

        //Cards分页变量
        View::assign([
            'cardsListRaw'  => $cardsListRaw,
            'cardsListData'  => $listData,
            'cardsListNum'  => $listNum
        ]);

        //基础变量
        View::assign([
            'TemplateDirectory' => '/view/index/' . $this->TemplateDirectory . '/assets',
            'systemVer' => Common::systemVer(),
            'systemData' => Common::systemData(),
            'viewTitle'  => $viewTitle,
            'viewDescription' => false,
            'viewKeywords' => false,
            'searchValue'  => $requestTag['name']
        ]);

        //输出模板
        return View::fetch($this->TemplateDirectoryPath . '/cards-tag');
    }
}
