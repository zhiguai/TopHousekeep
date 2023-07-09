//基础
var apiUrlCardsAdd = '/api/Cards/add'//添加卡
var apiUrlCardsGood = '/api/Cards/good'//推荐
var apiUrlCardsCommentsAdd = '/api/CardsComments/add'//添加评论

var apiUrlUserAuthLogin = '/api/UserAuth/login'//登入
var apiUrlUserAuthRegister = '/api/UserAuth/register'//注册
var apiUrlUserAuthLogout = '/api/UserAuth/logout'//注销

var apiUrlOrderAdd = '/api/Order/add'//添加订单
var apiUrlOrderEnd = '/api/Order/end'//结束订单

var apiUserEdit = '/api/User/edit';//编辑用户

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
    //0级重置
    if($('#jsParameter').attr('jsTabClass') <= 4){
        $.removeCookie('historyUrl', { path: '/' });
    }

    //历史路径
    var historyUrl = $.cookie('historyUrl');
    //测试
    //console.log(historyUrl);

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

$(function(){
    $('.avatar').each(function (index, domEle) {
        if ($(domEle).attr('src') == "") { $(domEle).attr('src', "https://img1.imgtp.com/2023/06/15/By2uYcJf.png") }
    })
    //处理不加载图片
    $('img').each(function (index, domEle) {
        if ($(domEle).attr('src') == "") { $(domEle).attr('src', "https://img1.imgtp.com/2023/06/20/2sqWClRH.png") }
    })
})