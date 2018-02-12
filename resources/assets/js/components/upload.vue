<template>
    <div class="container">
        <el-row :gutter="20">
            <el-col :span="6" :offset="3" class="userPhone"><img  v-bind:src="avatar"></el-col>
        </el-row>
        <el-row :gutter="20">
            <el-col :span="6" :offset="3"  class="userName"><div class="grid-content bg-purple">{{nickname}}</div></el-col>
        </el-row>
        <el-row :gutter="20">
             <el-col :span="6" :offset="3" class="codeBth"><div class="grid-content bg-purple"><el-button type="danger" @click="choose">上传二维收款码</el-button></div></el-col>
        </el-row>
        <!--<el-row>-->
            <!--<el-col :span="6" :offset="3"><img width="300px" height="300px" v-bind:src="shoukuanma"></el-col>-->
        <!--</el-row>-->
        <el-row>
            <el-col :span="6" class="nextBtn" :offset="3"><el-button type="danger"><router-link to="select">下一步</router-link></el-button></el-col>
        </el-row>
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
                shoukuanma: 'https://ss0.bdstatic.com/5aV1bjqh_Q23odCf/static/superman/img/logo_top_ca79a146.png',
                queryCount: 0,
                setout:'',
            }
        },
        methods:{
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
                                            that.setout=setInterval(function(){that.queryQrcode( mediaId);that.queryCount++}, 1000);
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
                        that.shoukuanma = data.image;
                        clearInterval(that.setout);
                    }
                });
            }
        }
    }
</script>
<style>
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
    }
</style>