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
        <title>Pocket 黑店</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <style type="text/css"> 
            *{
                padding: 0 ;
                margin: 0;
            }
            html,body{
                font-size:100%;
                /*background-size:100%;*/
                height: 100%;
                width: 100%;
                background-size:100% 100%; 
            }
        </style>
    </head>

    <body >
    <div id="app" style="width:100%;height:100%;"></div>
    <div class="container" :style="'background:url('+imgs+')'+';background-size: 100%;'">
        <img src="/images/logo.png" class="pic">
        <div class="danger" type="danger" v-on:click="ahref" >
            <img src="/images/collectmoney.png" alt=""  class="collectimg">
            <img src="/images/logo.png" alt="">
            <!--<span class="collect">

                <router-link to="upload">收红包</router-link>-->
            <!--</span>-->
        </div>
        <div class="collectcord" v-on:click="code">如何获取收款二维码图片</div>
    </div>
    </body>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        wx.config(<?php echo EasyWeChat::officialAccount()->jssdk->buildConfig(array('chooseImage', 'previewImage', 'uploadImage'), false) ?>);
    </script>

    <script>
        var Constant={
            avatar : "<?= $user->getAvatar()?>",
            nickname: "<?= $user->getNickname()?>",
            openid: "<?= $user->getId()?>"
        }
        var num = new Date().getTime();
    </script>
<!--    <script src="/js/app.js?v=201802141020" type="text/javascript"></script>-->
<!--    <script src="{{mix('js/app.js')}}" type="text/javascript"></script>-->
    <script>
        var app = new Vue({
            el: '#app',
        });
    </script>
</html>
