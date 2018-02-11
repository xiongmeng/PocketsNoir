<!doctype html>
<?php
/** @var $user \Overtrue\Socialite\User */
?>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />

        <title>Pocket 黑店</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    </head>

    <body style="background:url(http://pn-activity.oss-cn-shenzhen.aliyuncs.com/program/bg.jpeg)">
    <div id="app"></div>
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
    </script>
    <script src="/js/app.js?v=3" type="text/javascript"></script>
</html>
