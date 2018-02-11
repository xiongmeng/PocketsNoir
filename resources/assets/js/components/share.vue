<template>
    <div class="container">
        <span>长按图片分享</span>
        <img width="500px" height="500px" v-bind:src="$route.params.image"></img>
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
