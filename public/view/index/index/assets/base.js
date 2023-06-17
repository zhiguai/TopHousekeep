//基础
var apiUrlCardsAdd = '/api/Cards/add'//添加卡
var apiUrlCardsGood = '/api/Cards/good'//添加卡
var apiUrlCardsCommentsAdd = '/api/CardsComments/add'//添加评论

var apiUrlUserAuthLogin = '/api/UserAuth/login'//登入
var apiUrlUserAuthRegister = '/api/UserAuth/register'//注册
var apiUrlUserAuthLogout = '/api/UserAuth/logout'//注销

//初始化标签
function ViewCardsTag(arr) {
    var CardsTagData = arr;
    for (let i = 0; i < $(".css-cards-primary-subtitle").length; i++) {
        //jq的坑$()取不到class的对象，取回的是数组，要变对象要套个$();
        var tagList = JSON.parse($($(".css-cards-primary-subtitle")[i])[0].attributes[1].value);
        $($(".css-cards-primary-subtitle")[i]).append('Tag：');
        for (let j = 0; j < tagList.length; j++) {
            for (const key in CardsTagData) {
                if (tagList[j] == CardsTagData[key]['id']) {
                    $($(".css-cards-primary-subtitle")[i]).append('<a href="/index/Cards/tag?value=' + CardsTagData[key]['id'] + '">' + CardsTagData[key]['name'] + '</a> ')
                }
            }
        }
    }
}

//历史路由记录
const historyUrl = () => {
    //历史路径
    var historyUrl = $.cookie('historyUrl');
    //console.log('1 ' + historyUrl);

    if (historyUrl == undefined) {
        historyUrl = [];
    } else {
        historyUrl = JSON.parse(historyUrl);
    }

    if (historyUrl[historyUrl.length - 1] != window.location.href && historyUrl[historyUrl.length - 1] != '') {
        //刷新不计入
        if (historyUrl[historyUrl.length - 2] != window.location.href) {
            //确保不是返回
            historyUrl[historyUrl.length] = window.location.href;
            $.cookie('historyUrl', JSON.stringify(historyUrl), { path: '/' });
        }else{
            //返回清除记录
            historyUrl.pop();
            $.cookie('historyUrl', JSON.stringify(historyUrl), { path: '/' });
        }

    }
}
historyUrl();

//跳转至卡片详情
$('.js-jumpurl-cardId').attr('style', 'cursor:pointer;');
$('.js-jumpurl-cardId').click(function () {
    jumpUrl('/index/Cards/card/id/' + $(this).attr('value'), 0);
});

//点赞
$('.js-Btn-Update-CardsGood').click(function () {
    if ($(this).val() == 'false') {
        return;
    }
    data = {
        'id': $(this).val(),
    };
    //提交数据
    var result = apiAjax0(data, apiUrlCardsGood, 'POST');
    if (result) {
        //成功
        mdui.snackbar({
            message: '点赞成功',
            position: 'left-top'
        });
        $(this).attr('class', 'css-card-actions-good-1 mdui-btn mdui-float-right');
        $(this).val(false);
        $(this).html('<i class="mdui-icon material-icons">favorite</i>点赞' + result.data.Num);
        return;
    }
});

//返回来时的路由
$('.js-jumpurl-BackUp').attr('style', $('.js-jumpurl-BackUp').attr('style') + 'z-index: 99999;');
$('.js-jumpurl-BackUp').click(function () {
    var historyUrl = JSON.parse($.cookie('historyUrl'));
    if (historyUrl[historyUrl.length - 2] != '') {
        window.location.href = historyUrl[historyUrl.length - 2];
    } else {
        return;
    }
});