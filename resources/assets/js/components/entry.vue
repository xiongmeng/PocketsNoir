<template>
    <div class="container">
        <el-button type="danger"><router-link to="upload">收红包</router-link></el-button>
    </div>
</template>

<script>

    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data (){
            return {
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
    .container>button{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%,-50%);

    }
</style>