<!doctype html>
<?php
/** @var $user \Overtrue\Socialite\User */
?>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>领取奖品</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <style type="text/css">
        *{
            padding: 0 ;
            margin: 0;
            font-family: '微软雅黑';
        }
        html,body{
            font-size:100%;
            /*background-size:100%;*/
            height: 100%;
            width: 100%;
            background-size:100% 100%;
        }

        .pic{
            position: fixed;
            bottom: 10px;
            left: 30%;
            width: 40%;
            height: auto;
        }
        .container{
            width:100%;
            height:100%;
            position: relative;
        }
        .container> .danger{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%);
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            background: #d5000f;

        }
        .collectimg{
            width: 172px;
            border-radius: 50%;
            margin-left: -11px;
            margin-top: -11px;


        }
        .collectcord{
            position: absolute;
            top: 70%;
            /* left: 50%; */
            /* right: 50%; */
            color: #f7bc00;
            margin: auto;
            text-align: center;
            height: 20px;
            width: 200px;
            width: 100%;
            line-height: 20px;
            font-size: 14px;

        }
    </style>
</head>

<body >
<div id="app" style="width:100%;height:100%;">
    <p>恭喜<?= $user->getName()?> 获得 <span><?= $point ?></span>积分</p>
    <p>同时附赠一张会员卡哦，点击以下链接一并领取</p>

    <p><a href="https://j.youzan.com/ex9syY">我是一个按钮，可以点击，也可以自动跳转</a></p>

    <p>openId:<?= $user->getId()?></p>
</div>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript" charset="utf-8">
//    $.ajax({
//        url : "/jsCfg",
//        type : 'get'
//    }).done(function (data){
//        var data1 = data;
//        console.log(data);
//        wx.config(data);
//    });

//    $.ajax({
//        url : "/auth",
//        type : 'get'
//    }).done(function (data){
//        var data1 = data;
//        console.log(data);
//        wx.config(data);
//    });

    wx.ready(function () {
        //var link = window.location.href;
        var protocol = window.location.protocol;
        var host =  window.location.host;
        //分享朋友圈
        wx.onMenuShareTimeline({
            title: '我躲过亲戚奇葩问题，还把红包收了',
            link: 'http://mp.sylicod.com/entry', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            desc: '善待“新年恐惧症”患者，人人有责！', // 分享描述
            imgUrl: protocol+'//'+host+'/images/sueyi.png',// 自定义图标
            trigger: function (res) {

            },
            success: function (res) {

            },
            cancel: function (res) {

            },
            fail: function (res) {

            }
        });
        //分享给好友
        wx.onMenuShareAppMessage({
            title: '我躲过亲戚奇葩问题，还把红包收了', // 分享标题
            desc: '善待“新年恐惧症”患者，人人有责！', // 分享描述
            link: 'http://mp.sylicod.com/entry', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: protocol+'//'+host+'/images/sueyi.png',// 自定义图标
            // type: 'link', // 分享类型,music、video或link，不填默认为link
            // dataUrl: 'http://mp.sylicod.com/entry', // 如果type是music或video，则要提供数据链接，默认为空
            success: function (data) {
                // 用户确认分享后执行的回调函数
                console.log(data)
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.error(function (res) {
            console.log(res);
        });
    });
</script>
<script>
    var num = new Date().getTime();
</script>
<!--    <script src="/js/app.js?v=201802141020" type="text/javascript"></script>-->
<script>

</script>
</html>
