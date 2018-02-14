<template>
    <div class="container" :style="'background:url('+imgs+')'+';background-size: 100%;'">
        <img src="/images/logo.png" class="pic">
        <div class="el-row" style="margin-left: -10px; margin-right: -10px;">
            <div class="userPhone el-col el-col-6 el-col-offset-3" style="padding-left: 10px; padding-right: 10px;">
                <img  v-bind:src="avatar">
            </div>
        </div>
       <!--  <el-row :gutter="20">
            <el-col :span="6" :offset="3" class="userPhone"><img  v-bind:src="avatar"></el-col>
        </el-row> -->
        <div class="el-row" style="margin-left: -10px; margin-right: -10px;">
            <div class="userName el-col el-col-6 el-col-offset-3" style="padding-left: 10px; padding-right: 10px;">
                <div class="grid-content bg-purple">{{nickname}}</div>
            </div>
        </div>
        <div class="el-row" style="margin-left: -10px; margin-right: -10px;">
            <div class="codeBth el-col el-col-6 el-col-offset-3" style="padding-left: 10px; padding-right: 10px;">
                <div class="grid-content bg-purple crod" style="height: 65px;">
                    <button type="button" class="el-button el-button--danger" v-on:click='choose'><!----><!----><span>上传二维收款码</span></button>
                </div>
            </div>
        </div>
        <div class="el-row"  v-if="shoukuanma">
            <div class="el-col el-col-6 width" style="margin: 0px auto; float: none;" v-if="shoukuanma">
                <img width="100px" height="100px" v-bind:src="shoukuanma">
            </div>
        </div>
        <div class="el-row"  v-if="shoukuanma">
            <div class="nextBtn el-col el-col-6 el-col-offset-3" style="margin-top: 20px;" v-on:click="ahrefupload">
                <button type="button" class="el-button el-button--danger"><!----><!----><span><a  class="">下一步</a></span></button>
            </div>
        </div>
       <!--  <el-row :gutter="20">
            <el-col :span="6" :offset="3"  class="userName"><div class="grid-content bg-purple">{{nickname}}</div></el-col>
        </el-row>
        <el-row :gutter="20">
             <el-col :span="6" :offset="3" class="codeBth"><div class="grid-content bg-purple crod" style="height: 65px"><el-button type="danger" @click="choose">上传二维收款码</el-button></div></el-col>
        </el-row>
        <el-row>
            <el-col :span="6" style="margin: 0 auto; float: none"><img width="100px" height="100px" v-if="shoukuanma" v-bind:src="shoukuanma"></el-col>
        </el-row>
        <el-row>
            <el-col v-if="shoukuanma" :span="6" class="nextBtn" :offset="3" style="    margin-top: 20px;" v-on:click="ahrefupload"><el-button type="danger"><router-link to="share">下一步</router-link></el-button></el-col>
        </el-row> -->
        <div class='positionloding' v-if='loading'>
            <img src="/images/loading.gif">
        </div>
    </div>
     
</template>

<script>

    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data (){
            return {
                avatar : Constant.avatar,
                nickname: Constant.nickname,
                shoukuanma: '',
                queryCount: 0,
                setout:'',
                imgs:"/images/bg.png",
                loading:false,
                image:'',
            }
        },
        methods:{
            ahrefupload(){
                // this.$router.push({ path: '/share?'+this.shoukuanma })
                 this.$router.push('/share/' + encodeURIComponent(this.image));
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
                        that.image = data.image;
                        // alert(data.image)
                        that.loading = false
                        clearInterval(that.setout);
                        that.$router.push('/share/' + encodeURIComponent(that.image));
                    }
                });
            }
        }
    }
</script>
<style>
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
        overflow-y: auto;
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
        height: 80px;
        font-size: 18px;
        line-height: 35px;
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
        width: 100%;
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

    /*.crod .el-button--danger{
        background-color: #d5000f;
        border-color: #d5000f;
        padding: 16px 7px;
        border-radius: 5px;
    }
    .crod .el-button--danger span{
        color: #fff;
        padding: 10px 20px;
        background-color: #f7bc00;
        border-color: #f7bc00;
        color: #d5000f;
        font-width: 600;

        border-radius: 5px;

    }*/
</style>