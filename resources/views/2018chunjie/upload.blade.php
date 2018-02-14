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
    .text{
        height: 80px;
        line-height: 80px;
        text-align: center;
    }
</style>
    </head>

    <body >
        <div id="app" style="width:100%;height:100%;"></div>
            <div class="container" :style="'background:url('+imgs+')'+';background-size: 100%;'">
                <img src="/images/logo.png" class="pic">
                <div class="el-row" style="margin-left: -10px; margin-right: -10px;">
                    <div class="userPhone el-col el-col-6 el-col-offset-3" style="padding-left: 10px; padding-right: 10px;">
                        <img v-bind:src="avatar">
                    </div>
                </div>
                <div class='text' >
                    {{avatar}}
                </div>
                <div class='positionloding' v-if='loading'>
                    <img src="/images/loading.gif">
                </div>
               <div class='positionloding' v-if='loading'>
                    <img src="/images/loading.gif">
                </div>
               
                
            </div>
        
    </body>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript" charset="utf-8"></script>
    
    <script type="text/javascript" charset="utf-8">
        wx.config(<?php echo EasyWeChat::officialAccount()->jssdk->buildConfig(array('chooseImage', 'previewImage', 'uploadImage'), false) ?>);
    </script>
    <script src="/js/vue.js" type="text/javascript" charset="utf-8"></script>
    <script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <script>
        var Constant={
            avatar : "<?= $user->getAvatar()?>",
            nickname: "<?= $user->getNickname()?>",
            openid: "<?= $user->getId()?>"
        }
        var vm = new Vue({
            el:'#app',
            data: {

                avatar : Constant.avatar,
                nickname: Constant.nickname,
                shoukuanma: '',
                queryCount: 0,
                setout:'',
                imgs:"/images/bg.png",
                loading:false,
                image:'',
                status:'',
                
            },
            methods:{
                ahrefupload(){
                    // this.$router.push({ path: '/share?'+this.shoukuanma })
                     // this.$router.push('/share/' + encodeURIComponent(this.image));
                },
                choose: function () {
                    console.log("has click the choose!");
                    var that = this;
                    wx.chooseImage({
                        count: 1, // 默认9
                        sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                        sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                        success: function (data) {
                            var localIds = data.localIds[0].toString(); // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                            console.log(localIds);

                            wx.uploadImage({
                                localId: localIds, // 需要上传的图片的本地ID，由chooseImage接口获得
                                isShowProgressTips: 1, // 默认为1，显示进度提示
                                success: function (res) {
                                    var mediaId = res.serverId; // 返回图片的服务器端ID
                                    // $(".myimg").attr("src", localIds);
                                    that.shoukuanma = localIds;
                                    console.log("获取到mediaId:" + mediaId);

                                    that.queryCount = 0;

                                            $.post(
                                        "/shoukuanma?serverId=" + mediaId,
                                        {},
                                        function () {
                                            if (that.queryCount < 5) {
                                                that.loading = true
                                                that.setout=setInterval(function(){that.queryQrcode( mediaId);that.queryCount++}, 2000);
                                            }
                                        }
                                    );

                                },
                                fail: function (error) {
                                    var localIds = '';
                                    alert(Json.stringify(error));

                                }
                            });
                        }
                    });
                },
                queryQrcode: function (serverId) {
                    var that= this;
                    // this.queryCount++;
                    console.log(this.queryCount)
                    if (that.queryCount > 5) {
                        clearInterval(that.setout)
                    }
                    $.ajax({
                        url : "/qrcode?serverId=" + serverId,
                        type : 'get'
                    }).done(function (data){
                        if(data.image){
                            that.status ='';
                            that.image = data.image;
                            that.loading = false
                            clearInterval(that.setout);
                            window.location.href ='#/share/'+encodeURIComponent(that.image)
                        }else{
                            if (that.queryCount > 5) {
                                that.status = '您上传的二维码图片无法识别或存在问题！请重新上传';
                                that.loading = false
                                clearInterval(that.setout);
                            }
                            
                        }
                    });
                }
            }
        });
    </script>
</html>
