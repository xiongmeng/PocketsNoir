<!doctype html>
<?php
/** @var $user \Overtrue\Socialite\User */
/** @var $original [] */
/** @var $shopUser [] */
?>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>讨红包神器</title>

    <!-- Fonts -->
    <!--        <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">-->
    <link rel="stylesheet" href="/css/index.css">
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

        .positionloding{
            height: 100%;
            width: 100%;
            position: fixed;
            left: 0;
            top: 0;
            background: #666;
            opacity: .7;
        }
        .positionloding img{
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            width: 10%;
            height: auto;
        }
        .pic{
            position: fixed;
            bottom: 10px;
            left: 30%;
            width: 40%;
            height: auto;
        }
        .box{
            height: 100%;
            width: 100%;
            overflow-y: auto;
            background: url("/images/bg.png") 0% 0% / 100%;
        }
        .container{
            height: 100%;
            width: 100%;
            /*overflow-y: auto;*/
        }
        .userPhone{
            width: 132px;
            height: 132px;
            margin: 100px auto 0;
            padding: 0 !important;
            float: inherit;
            box-sizing: content-box;
            border-radius: 50%;
            overflow:hidden;
        }
        .userPhone img{
            width: 100%;
            height: 100%;
        }
        .userName{
            text-align: center;
            color:white;;
            height: 50px;
            font-size: 18px;
            line-height: 70px;
            text-align: center;
            padding: 0;
            margin: 0;
            width: 100%;
        }
        .codeBth{
            float: inherit;
            padding: 0;
            margin: 0;
            width: 100%;
        }
        .codeBth button{
            margin: 0 auto 50px;
            display: block;
        }
        .nextBtn{
            float: inherit;
            padding: 0;
            margin: 0;
            width: 80%;
            color: #fff;
            margin-left: 10%;
            line-height: 28px;

        }
        .nextBtn button{
            margin: 0 auto;
            display: block;
            background-color: #f7bc00;
            border-color: #f7bc00;
            color: #d5000f;
            font-width: 600;
            padding: 10px 32px;
            border-radius: 5px;
        }
        .nextBtn button span{
            color: #d5000f;
        }
        a{
            text-decoration: none;
            color: #d5000f;
        }
        .crod .el-button--danger{
            background-color: #f7bc00;
            border-color: #f7bc00;
            padding: 16px 7px;
            border-radius: 5px;
        }
        .crod .el-button--danger span{
            color: #fff;
            padding: 10px 20px;
            background-color: #d5000f;
            border-color: #d5000f;
            color: #f7bc00;
            font-width: 600;

            border-radius: 5px;

        }
        .width{
            width: 100px;
            margin: 0 auto;
        }
        .el-col.el-col-6{
            float: none;
            margin: 0 auto;
        }


    </style>
</head>

<body >
<div id="app" style="width:100%;height:100%;overflow: hidden">
    {{--31a1fa--}}
    {{--<div class="container" :style="'background:url('+imgs+')'+';background-size: 100%;'">--}}
    <div class="container" :style="'background:#31a1fa'+';background-size: 100%;'">
        {{--<img src="/images/logo.png" class="pic">--}}
        <div class="el-row" style="height: 10%;padding-top: 5%">
            <div style="text-align: center;font-size: 800;">
                黑口袋会员商城
            </div>
        </div>
        <div class="el-row" style="">
            <div class="userPhone el-col el-col-6 " style="width: 36%;">
                <img v-bind:src="avatar">
            </div>
        </div>
        <!--  <div class='text' >

         </div> -->
        <div class="el-row" style="margin-left: -10px; margin-right: -10px;    ">
            <div class="userName el-col el-col-6 " style="width: 36%;">
                <div class="grid-content bg-purple"> <?= $user->getNickname() ?></div>

            </div>
        </div>
        <div class="el-row" style="height: 40px;line-height: 40px">
            <div style="text-align: center;font-size: 800;">
                <div class="grid-content bg-purple"> 黑口袋送你100积分</div>

            </div>
        </div>
        <div class="el-row" style="height: 40px;line-height: 40px">
            <div style="text-align: center;font-size: 800;">
                <div class="grid-content bg-purple">  1元=1积分</div>

            </div>
        </div>

        <div class="el-row" style="height: 60px;line-height: 60px">
            <div style="text-align: center;font-size: 800;">
                <button v-if="receive_no=='0'" type="button" @click="receive()" style="height: 26px; background: #ec5ae8 ;height: 42px;width: 80%;margin: auto;font-size: 24px">领取积分</button>
                <button  v-if="receive_no=='1'" type="button"   style="height: 26px; background: #929292 ;height: 42px;width: 80%;margin: auto;font-size: 28px">已领取</button>

            </div>
        </div>
        <div class="el-row" style="height: 40px;line-height: 40px">
            <div style="text-align: center;font-size: 800;">
                <div class="grid-content bg-purple"> 回到小程序【我的】即可查看积分</div>

            </div>
        </div>
        <div class="el-row">
            <div class="el-col el-col-6 width"  >
                <img width="80px" height="80px" src="https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=567838369,2634694507&fm=26&gp=0.jpg">
            </div>
        </div>
        <div class="el-row" style="height: 80px;line-height: 80px">
            <div style="text-align: center;font-size: 800;">
                <div class="grid-content bg-purple"> pocket  noir</div>

            </div>
        </div>



        <div class="el-row"  v-if="status">
            <div class="nextBtn el-col el-col-6 " style="margin-top: 20px;" v-on:click="ahrefupload">
            </div>
        </div>
        <div class='positionloding' v-if='loading'>
            <img src="/images/loading.gif">
        </div>


    </div>
</div>

</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript" charset="utf-8">
    wx.config(<?php echo EasyWeChat::officialAccount()->jssdk->buildConfig(array('onMenuShareTimeline', 'onMenuShareAppMessage','chooseImage', 'previewImage', 'uploadImage'), false) ?>);
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
<script src="/js/vue.min.js" type="text/javascript" charset="utf-8"></script>
<script src="/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
<script>
    var Constant={
        avatar : "<?= $user->getAvatar()?>",
        nickname: "<?= $user->getNickname()?>",
        openid: "<?= $user->getId()?>",
        receive_no:"<?= $shopUser->is_subscribe_no_receive ?>"
    }
    var vm = new Vue({
        el:'#app',
        data(){
            return {
                avatar : Constant.avatar,
                nickname: Constant.nickname,
                shoukuanma: '',
                queryCount: 0,
                setout:'',
                imgs:"/images/bg.png",
                loading:false,
                image:'',
                status:'',
                receive_no:Constant.receive_no
            }


        },
        methods:{
            ahrefupload(){
                // this.$router.push({ path: '/share?'+this.shoukuanma })
                // this.$router.push('/share/' + encodeURIComponent(this.image));
            },
            receive: function(){
                var that= this;

                $.ajax({
                    url: "/subscribe_no_receive",
                    dataType: "json",   //返回格式为json
                    async: true,//请求是否异步，默认为异步，这也是ajax重要特性
                    data: {},    //参数值
                    type: "get",   //请求方式

                    success: function (req) {
                        //请求成功时处理
                        if(req.code==1){
                            that.receive_no = 1;
                            alert('领取成功！')
                        }else {
                            alert('领取失败了！')
                        }

                    }
                })
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

                $.ajax({
                    url : "/qrcode?serverId=" + serverId,
                    type : 'get'
                }).done(function (data){
                    if(data.image){
                        that.status ='';
                        that.image = data.image;
                        that.loading = false
                        clearInterval(that.setout);
                        window.location.href ='/share'
                    }else{
                        if (that.queryCount > 4) {
                            that.status = '您上传的二维码图片无法识别或存在问题！请重新上传';
                            alert(that.status);
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
