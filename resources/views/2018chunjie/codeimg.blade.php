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
            html,body,#app{
                font-size:100%;
                /*background-size:100%;*/
                height: 100%;
                width: 100%;
                background-size:100% 100%; 
            }

    #app{
        background: #606060;
        overflow-y: auto;
    }
    .container{
        width:80%;
        margin: 10px auto;
        height:100%;
        position: relative;
    }
    img{
        height: auto;
        width: 100%;
    }

</style>
    </head>

    <body >
        <div id="app" style="width:100%;height:100%;">
            <div class="container">

                    <img src="/images/Help1.png" >
                    <img src="/images/Help2.png" >
                    <img src="/images/Help3.png" >
                    <img src="/images/Help4.png" >
                    <img src="/images/Help5.png" >
                    <img src="/images/Help6.png" >
                    <img src="/images/Help7.png" >
                    <img src="/images/Help8.png" v-on:click="ahref" >


            </div>
        </div>
    </body>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" charset="utf-8">
        wx.config(<?php echo EasyWeChat::officialAccount()->jssdk->buildConfig(array('chooseImage', 'previewImage', 'uploadImage'), false) ?>);
    </script>
    <script src="/js/vue.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <script>
        var Constant={
            avatar : "<?= $user->getAvatar()?>",
            nickname: "<?= $user->getNickname()?>",
            openid: "<?= $user->getId()?>"
        }
        var num = new Date().getTime();
        var vm = new Vue({
            el:'#app',
             mounted() {
                console.log('Component mounted.')
            },
            data (){
                return {
                    imgs:"/images/bg.png",
                }
            },
            methods:{
                ahref(){
                    // this.$router.push({ path: '/upload' })
                    window.location.href ='/entry'
                }
            }
        })
    </script>
    

</html>
