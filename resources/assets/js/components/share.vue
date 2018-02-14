<template>
    <div class="container">
        <span>你的神器制作完成啦，请长按图片保存</span>
        <img  v-bind:src="$route.params.image"></img>
    </div>
</template>

<script>

    export default {
        mounted() {
            /*wx.config({
                debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来
                appId: Constant.openid, // 必填，公众号的唯一标识
                timestamp: global_vars.wxJsConfig.timestamp, // 必填，生成签名的时间戳
                nonceStr: global_vars.wxJsConfig.nonceStr, // 必填，生成签名的随机串
                signature: global_vars.wxJsConfig.signature,// 必填，签名，见附录1
                url:global_vars.wxJsConfig.url,
                jsApiList: [
                    //'checkJsApi',//判断当前客户端版本是否支持指定JS接口
                    "onMenuShareTimeline",
                    "onMenuShareAppMessage"
                ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
            });
            wx.ready(function () {
                //var link = window.location.href;
                var protocol = window.location.protocol;
                var host =  window.location.host;
                //分享朋友圈
                wx.onMenuShareTimeline({
                    title: '我躲过亲戚奇葩问题，还把红包收了',
                    link: protocol+'//'+host+'/login',
                    desc: '善待“新年恐惧症”患者，人人有责！', // 分享描述
                    // imgUrl: protocol+'//'+host+'/wx/img/shop_img.png',// 自定义图标
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
                    link: protocol+'//'+host+'/login', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                    //imgUrl: protocol+'//'+host+'/wx/img/shop_img.png', // 自定义图标
                    type: 'link', // 分享类型,music、video或link，不填默认为link
                    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
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
            });*/
        },
        data (){
            return {
                imgs:"/images/bg.png",
            }
        }
    }
</script>
<style>

    img{


        width: 100%;
        height: auto;
    }
    .container{
        height: 100%;
        width: 100%;
        position: relative;

    }
    span {
        position: absolute;
        margin: auto;

        display: block;
        height: 30px;
        line-height: 30px;
        text-align: center;
        opacity: 0.7;
        background: #2e3436;
        color: #fff;
        left:0;
        right: 0;
    }



</style>
