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
    <title>个人号领取积分</title>

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
            width: 92%;
            max-height: 80%;
            min-height: 470px;
            overflow: hidden;
            border-radius: 10px 10px 10px;
            margin-top: 10px;

            position: relative;
            top: 0px;
            left: 4%;
        }
        .userPhone{

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
            height: 50px;
            font-size: 18px;
            line-height: 50px;
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
        .check_clone{
            width: 100%;
            height: 100%;
            opacity: 0.3;
            position: absolute;
            top: 0;
            left: 0;
            background: #666;
        }
        .success{
            width:90%;
            height: 200px;
            position: fixed;
            top: 0;
            left:0;
            right: 0;
            bottom: 0;
            margin: auto;
            background: #fff;
            border-radius: 10px;
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
        .btn_no{
            height: 40px;
            background: rgb(146, 146, 146);
            width: 70%;
            margin: auto;
            line-height: 40px;
            font-size: 22px;
            border-radius: 15px;
        }
        .btn{
            height: 40px;
            background: #ff0049;
            width: 70%;
            margin: auto;
            line-height: 40px;
            font-size: 22px;
            border-radius: 15px;
        }
        .confirm_btn{
            width: 66px;
            height: 32px;
            line-height: 32px;
            background: #000;
            color: #fff;
            font-size: 14px;
            border-radius: 11px;
            font-family: '微软雅黑';
        }
    </style>
</head>

<body style="    background: #f6f7f8;">
<div id="app" style="width:100%;height:100%;overflow: hidden;">
    {{--31a1fa--}}
    {{--<div class="container" :style="'background:url('+imgs+')'+';background-size: 100%;'">--}}
    <div class="container" :style="'background:#fff'+';background-size: 100%;'">
        {{--<img src="/images/logo.png" class="pic">--}}
        <div class="el-row" style="height: 10%;padding-top: 5%">
            <div style="text-align: center;font-weight: 900;font-size: 20px;">
                黑口袋会员商城
            </div>
        </div>
        <div class="el-row" style="height: 106px;
    padding-top: 10px;">
            <div class="userPhone el-col el-col-6 ">
                <img v-bind:src="avatar">
            </div>
        </div>
        <!--  <div class='text' >

         </div> -->
        <div class="el-row" style="margin-left: -10px; margin-right: -10px;    ">
            <div class="userName el-col el-col-6 " style="width: 80%;">
                <div class="grid-content bg-purple"> <?= $user->getNickname() ?></div>

            </div>
        </div>
        <div class="el-row" style="height: 40px;line-height: 40px">
            <div style="text-align: center;font-size: 800;">
                <div class="grid-content bg-purple"> 黑口袋送你 <span style="color: #5400e1;font-size: 30px">100</span> 积分</div>

            </div>
        </div>
        <div class="el-row" style="height: 40px;line-height: 40px">
            <div style="text-align: center;font-size: 800;">
                <div class="grid-content bg-purple"  style="color: #5400e1;font-size: 24px">  「1元=1积分」</div>

            </div>
        </div>

        <div class="el-row" style="height: 60px;line-height: 60px">
            <div style="text-align: center;font-size: 800;">
                <button v-if="receive_no=='0'" type="button" @click="receive()" class="btn">领取积分</button>
                <button  v-if="receive_no=='1'" type="button"   class="btn_no">已领取</button>

            </div>
        </div>
        <div class="el-row" style="height: 36px;">
            <div style="text-align: center;">
                <div class="grid-content bg-purple" style="font-size: 14px;"> 回到小程序【我的】即可查看积分</div>

            </div>
        </div>
        <div class="el-row">
            <div class="el-col el-col-6 width"  >
                <img width="80px" height="80px" src="https://pn-activity.oss-cn-shenzhen.aliyuncs.com/vipShop/static/gh_fb5108c84462_1280.jpg">
            </div>
        </div>
        <div class="el-row" style="height: 15px;">

        </div>

        <div class="el-row"  v-if="status">
            <div class="nextBtn el-col el-col-6 " style="margin-top: 20px;" v-on:click="ahrefupload">
            </div>
        </div>
        <div class='positionloding' v-if='loading'>
            <img src="/images/loading.gif">
        </div>


    </div>
    <div class="check_clone" v-if="status_key == '1'">

    </div>
    <div class="success" v-if="status_key =='1'">
        <div style="height: 60px;padding-top: 10px;text-align: center;line-height: 60px">
            <img src="/images/success.png" alt="" style="height: 30px;width: auto;margin: auto">
        </div>
        <div style="height: 32px;text-align: center; white-space:nowrap;">积分领取成功！</div>
        <div style="height: 32px;line-height: 32px;text-align: center; white-space:nowrap;overflow:hidden">快去小程序-我的积分中查看详情吧！</div>
        <div style="height: 50px;padding-top: 10px;">
            <div style="text-align: center;">
                <button v-if="receive_no=='0'" type="button" @click="close_btn()" class="confirm_btn">确认</button>

            </div>
        </div>
    </div>
</div>

</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript" charset="utf-8">
    wx.config(<?php echo EasyWeChat::officialAccount()->jssdk->buildConfig(array('onMenuShareTimeline', 'onMenuShareAppMessage','chooseImage', 'previewImage', 'uploadImage'), false) ?>);
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
                receive_no:Constant.receive_no,
                status_key:0,
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
                            that.status_key = 1;
                        }else {
                            alert('领取失败了！')
                        }

                    }
                })
            },
            close_btn:function(){
                this.status_key = 0;
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
