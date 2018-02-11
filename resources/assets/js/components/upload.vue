<template>
    <div class="container">
        <el-row :gutter="20">
            <el-col :span="6" :offset="3"><img v-bind:src="avatar"></el-col>
        </el-row>
        <el-row :gutter="20">
            <el-col :span="6" :offset="3"><div class="grid-content bg-purple">{{nickname}}</div></el-col>
        </el-row>
        <el-row :gutter="20">
             <el-col :span="6" :offset="3"><div class="grid-content bg-purple"><el-button type="danger" @click="choose">上传二维收款码</el-button></div></el-col>
        </el-row>
        <el-row>
            <el-col :span="6" :offset="3"><img width="300px" height="300px" v-bind:src="shoukuanma"></el-col>
        </el-row>
        <el-row>
            <el-col :span="6" :offset="3"><el-button type="danger"><router-link to="select">下一步</router-link></el-button></el-col>
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
                queryCount: 0
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
                                        setTimeout("that.queryQrcode(mediaId)", 1000);
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
                $.ajax({
                    url : "/qrcode?serverId=" + mediaId,
                    type : 'get'
                }).done(function (data){
                    // if(data.)
                    //TBD 循环
                });
            }
        }
    }
</script>
