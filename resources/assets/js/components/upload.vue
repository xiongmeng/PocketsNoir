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
                nickname: Constant.nickname
            }
        },
        methods:{
            choose: function () {
                console.log("has click the choose!");
                wx.chooseImage({
                    count: 1, // 默认9
                    sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                    sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                    success: function (res) {
                        var localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
                        console.log(localIds);
                        wx.previewImage({
                            current: '', // 当前显示图片的http链接
                            urls: localIds
                        });
                    }
                });
            }
        }
    }
</script>
