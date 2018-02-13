<template>
    <div class="container">

            <img src="/images/Help1.png" >
            <img src="/images/Help2.png" >
            <img src="/images/Help3.png" >
            <img src="/images/Help4.png" >
            <img src="/images/Help5.png" >
            <img src="/images/Help6.png" >
            <img src="/images/Help7.png" >
            <img src="/images/Help8.png" v-on:click="ahref" >


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
                // this.$router.push({ path: '/upload' })
                window.location.href ='/entry'
            }
        }
    }
</script>
<style>
    .box{
        background: #606060;
        overflow-y: auto;
    }
    .container{
        width:80%;
        margin: 10px auto;
        height:100%;
        position: relative;
    }
    img{
        height: auto;
        width: 100%;
    }

</style>