<template>
    <div class="container" :style="'background:url('+imgs+')'+';background-size: 100%;'">
        <div class="danger" type="danger" v-on:click="ahref" >
            <img src="/images/collectmoney.png" alt=""  class="collectimg">
            <img src="/images/logo.png" alt="">
             <!--<span class="collect">
            
                 <!--<router-link to="upload">收红包</router-link>-->
             <!--</span>-->
        </div>
        <div class="collectcord" v-on:click="code">如何获取收款二维码图片</div>
    </div>
</template>

<script>

    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data (){
            return {
                imgs:"/images/bg.png",
            }
        },
        methods:{
            ahref(){
                this.$router.push({ path: '/upload' })
            },
            code(){
                this.$router.push({ path: '/codeimg' })
            }
        }
    }
</script>
<style>
    .container{
        width:100%;
        height:100%;
        position: relative;
    }
    .container> .danger{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        background: #d5000f;

    }
    .collectimg{
        width: 172px;
        border-radius: 50%;
        margin-left: -11px;
        margin-top: -11px;


    }
    .collectcord{
        position: absolute;
        top: 70%;
        /* left: 50%; */
        /* right: 50%; */
        color: #f7bc00;
        margin: auto;
        text-align: center;
        height: 20px;
        width: 200px;
        width: 100%;
        line-height: 20px;
        font-size: 14px;

    }
</style>