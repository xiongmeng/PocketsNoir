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
    <title>领取积分</title>

    <!-- Fonts -->
    <!--        <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">-->
    <link rel="stylesheet" href="/css/index.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/css/wx_no.css">

</head>

<body style="    background: #f6f7f8;">
<div id="app" style="width:100%;height:100%;overflow: hidden;">
    {{--31a1fa--}}
    {{--<div class="container" :style="'background:url('+imgs+')'+';background-size: 100%;'">--}}
    <div class="container" >

        <div class="el-row">
            <div class="shop">
                黑口袋品牌商城
            </div>
        </div>
        <div class="el-row" style="height: 88px; padding-top: 10px;">
            <div class="userPhone ">
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
        <div class="el-row">
            <div style="text-align: center;">
                <div class="grid-content bg-purple dev100"> 黑口袋送你 <span class="span100">100</span> 积分</div>

            </div>
        </div>
        <div class="el-row">
            <div style="text-align: center;">
                <div class="grid-content bg-purple jifen">  「1积分 &nbsp;= &nbsp;1元」</div>

            </div>
        </div>

        <div class="el-row" style="height: 60px;line-height: 60px">
            <div style="text-align: center;font-size: 800;">
                <button v-if="receive_no=='0'" type="button" @click="receive()" class="btn">领取积分</button>
                <button  v-if="receive_no=='1'" type="button"   class="btn_no">已领取</button>

            </div>
        </div>
        <div class="el-row">
            <div style="text-align: center;">
                <div class="grid-content bg-purple wx_code" > 长按识别小程序码，在【我的】查看积分</div>

            </div>
        </div>
        <div class="el-row">
            <div class="wx_img">
                <img width="110px" height="110px" src="https://pn-activity.oss-cn-shenzhen.aliyuncs.com/vipShop/static/gh_fb5108c84462_1280.jpg">
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
        <div class="success_img">
            <img src="/images/success.png" alt="" style="height: 40px;width: auto;margin: auto">
        </div>
        <div class="receive_jifen">积分领取成功！</div>
        <div class="WxAppJifen">快去小程序-我的积分中查看详情吧！</div>
        <div style="height: 50px;padding-top: 10px;">
            <div style="text-align: center;">
                <button type="button" @click="close_btn()" class="confirm_btn">确认</button>

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
