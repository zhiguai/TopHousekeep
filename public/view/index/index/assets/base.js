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

//跳转至卡片详情
$('.js-jumpurl-cardId').attr('style', 'cursor:pointer;');
$('.js-jumpurl-cardId').click(function () {
    jumpUrl('/index/Cards/card/id/' + $(this).attr('value'), 0);
});

//分享
//获取当前分享URL
var NowSharePageUrl = window.location.protocol + "//" + window.location.host + '/index/Cards/card/id/';
//获取当前唯一分享内容
function GetShareContent() {
    var ShareUrl = NowSharePageUrl + $('#dialog-share').val();
    return content = NowPageShareData['title'] + '\n' + NowPageShareData['content'] + '\n来自' + $('title').text() + '\n' + ShareUrl;
}
//初始化分享按钮
$('.js-Shareurl-cardId').attr('class', 'mdui-icon iconfont mdui-float-right mdui-color-theme-accent css-caard-header-btn-round mdui-m-l-1 js-Shareurl-cardId');
$('.js-Shareurl-cardId').attr('style', 'cursor:pointer;');
$('.js-Shareurl-cardId').click(function () {
    //新建弹窗对象
    var dialogShare = new mdui.Dialog('#dialog-share');
    // //更新分享弹窗CardID
    $('#dialog-share').val($(this).attr('value'));

    //选择数据获取方式
    if ($(this).attr('state') == 'card') {
        //console.log($('#CardDataTitle').text());
        //整理数据
        var cardIdTitle = $('#CardDataTitle').text().trim();
        var cardIdContent = $('#CardDataTag').text().trim() + '\n' + $('#CardDataContent').text().trim();
    } else {
        //取对象
        var thisCardIdP2 = $(this).parent();
        var thisCardIdP1 = $(this).parent().parent();
        //整理数据
        var cardIdTitle = '[' + thisCardIdP2[0]['innerText'].split("\n")[0] + ']-' + thisCardIdP2.next()[0]['innerText'];
        var cardIdContent = thisCardIdP1.siblings().filter('.mdui-typo')[0]['innerText'];
    }

    //打包数据
    NowPageShareData = { title: cardIdTitle, content: cardIdContent };//当前卡片数据

    //更新分享弹窗内容
    $('#dialog-share').find('textarea').text(GetShareContent());

    //弹窗
    dialogShare.open();
});

//分享至微博
$('.js-jumpurl-wb-cardId').click(function () {
    var ShareUrl = 'https://service.weibo.com/share/share.php?url=' + NowSharePageUrl + $('#dialog-share').val() + '&title=' + encodeURI(NowPageShareData['title'] + '\n' + NowPageShareData['content'] + '\n来自' + $('title').text());
    window.open(ShareUrl, '_blank');
});
// //分享至QQ
// $('.js-jumpurl-qq-cardId').click(function () {
// });
// //分享至WX
// $('.js-jumpurl-wx-cardId').click(function () {
// });
//复制分享内容
$('.js-copyurl-cardId').click(function () {
    //整理分享数据
    var content = GetShareContent()
    var state = copyText(content);
    //console.log(state);
    if (!state) {
        //更新为无法复制弹窗/Content
        $(this).attr('style', 'display:none;')
        $('#dialog-share').find("textarea").parent().parent().attr('style', '');
        $('#dialog-share').find("textarea").siblings("div").filter(".mdui-typo-caption-opacity").text('请手动复制分享内容');
        $('#dialog-share').find("textarea").text(content);
    }
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
    var historyUrl = document.referrer;
    if (historyUrl != '') {
        window.location.href = historyUrl;
    } else {
        return;
    }
});