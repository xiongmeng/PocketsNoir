<template>
    <div class="container">
        <span>长按图片分享</span>
        <img  v-bind:src="$route.params.image"></img>
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
        top: 40px;
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
