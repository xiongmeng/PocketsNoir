<template>
    <div class="container">
        <template v-for="(option, idx) in options">
            <el-row :gutter="20">
                <el-col :span="6" :offset="3"><el-button v-bind:type="option.type" @click="select(option, idx)">{{option.name}}</el-button></el-col>
            </el-row>
        </template>
        <el-row>
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
                        type : 'warning'
                    },
                    {
                        name : '恭贺新禧',
                        type : 'danger'
                    },
                    {
                        name : '考的好不好',
                        type : 'danger'
                    }
                ],
                selected : 0
            }
        },
        methods:{
            select: function (option, idx) {
                var that = this;
                that.options[that.selected].type='danger';
                that.selected = idx;
                that.options[that.selected].type='warning';
            },
            generate: function () {
                var that = this;
                $.post(
                    "/generate?name=" + that.options[that.selected].name,
                    {},
                    function (data) {
                        that.$router.push('/share/' + encodeURIComponent(data.image));
                    }
                );
            }
        }
    }
</script>
