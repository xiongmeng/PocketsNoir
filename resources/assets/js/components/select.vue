<template>
    <div >
        <div class="container" >

            <template v-for="(option, idx) in options">
                <el-row :gutter="20">
                    <el-col :span="6" :offset="3" class="select"><el-button v-bind:type="option.type" @click="select(option, idx)">{{option.name}}</el-button></el-col>
                </el-row>
            </template>
        </div>

        <el-row class="nextBtn">
            <el-col :span="6" :offset="3"><el-button type="danger" @click="generate">下一步</el-button></el-col>
        </el-row>
    </div>
</template>

<script>

    export default {
        mounted() {
            console.log(window.location.href.split('?')[1])
            console.log('Component mounted.')
        },
        data (){
            return {
                options:[
                    {
                        name : '全部',
                        type : 'danger'
                    },
                    {
                        name : '恭贺新禧',
                        type : 'danger'
                    },
                    {
                        name : '考的好不好',
                        type : 'danger'
                    },
                    {
                        name : '全部',
                        type : 'danger'
                    },
                    {
                        name : '恭贺新禧',
                        type : 'danger'
                    },
                    {
                        name : '考的好不好',
                        type : 'danger'
                    },
                    {
                        name : '考的好不好',
                        type : 'danger'
                    },
                    {
                        name : '全部',
                        type : 'danger'
                    },{
                        name : '考的好不好',
                        type : 'danger'
                    },
                    {
                        name : '全部',
                        type : 'danger'
                    },{
                        name : '考的好不好',
                        type : 'danger'
                    },
                    {
                        name : '全部',
                        type : 'danger'
                    },
                ],
                arry:[]
            }
        },
        methods:{
            select: function (option, idx) {
                var that = this;
                var index = idx;
                // that.options[that.selected].type='danger';
                if(that.options[index].type == 'warning'){
                    that.options[index].type='danger';
                }else {
                    that.options[index].type='warning';
                }
            },
            generate: function () {
                var that = this;
                // $.post(
                //     "/generate?name=" + that.options[index].name,
                //     {},
                //     function (data) {
                //         that.$router.push('/share/' + encodeURIComponent(data.image));
                //     }
                // );
            }
        }
    }
</script>
<style>
    .container{
        height: 400px;
        overflow-y: auto;
    }
    .container>div{
        width: 100%;
        padding:0 !important;
    }
    .select{
        float: inherit;
        padding:10px 100px;
        margin: 0;
        width: 100%;
    }
    .select button{
        width: 70%;
        margin: 0 auto;
        display: block;
    }
    .nextBtn>div{
        float: inherit;
        padding:10px 100px;
        margin: 0;
        width: 100%;
    }
    .nextBtn>div>button{
        width: 60%;
        margin:20px auto;
        display: block;
        box-sizing: content-box;
    }
</style>