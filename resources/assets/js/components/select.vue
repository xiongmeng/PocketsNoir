<template>
    <div >
        <div class="container" >
            <el-row :gutter="20" style="margin: 0">
                <!--<el-col :span="6" :offset="3" class="select"><el-button v-bind:type="option.type" @click="select(option, idx)">{{option.name}}</el-button></el-col>-->
                <el-col :span="6" :offset="3" class="select" style="padding-left: 0;padding-right: 0"><el-button @click="wholesfn">
                    <img :src="wholes?'/images/wholeslect.png':'/images/whole.png'" alt=""></el-button>
                </el-col>


            </el-row>
            <template v-for="(option, idx) in options">
                <el-row :gutter="20" style="margin: 0">
                    <!--<el-col :span="6" :offset="3" class="select"><el-button v-bind:type="option.type" @click="select(option, idx)">{{option.name}}</el-button></el-col>-->
                    <el-col :span="6" :offset="3" class="select" style="padding-left: 0;padding-right: 0"><el-button v-bind:type="option.type" @click="select(option, idx)">
                        <img :src="option.type==1?option.name:option.nams" :alt="option.nams"></el-button>
                    </el-col>


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
                wholes:false,
                options:[
                    {
                        name : '/images/happynewyear.png',
                        // type : 'danger',
                        type:1,

                        nams :'/images/happynewyearselect.png',
                    },
                    {
                        name : '/images/Notwelltested.png',
                        // type : 'danger',
                        type:1,
                        nams :'/images/Notwelltestedselect.png',
                    },
                    {
                        name : '/images/Notwelltested.png',
                        // type : 'danger',
                        type:1,
                        nams :'/images/Notwelltestedselect.png',
                    },
                    {
                        name : '/images/Prosperous.png',
                        // type : 'danger',
                        type:1,
                        nams :'/images/Prosperousselect.png',
                    },{
                        name : '/images/Howmuchdoyoumake.png',
                        // type : 'danger',
                        type:1,
                        nams :'/images/Howmuchdoyoumakeselect.png',
                    },
                    {
                        name : '/images/Healthy.png',
                        // type : 'danger',
                        type:1,
                        nams :'/images/Healthyselect.png',
                    },
                    {
                        name : '/images/peopleobject.png',
                        // type : 'danger',
                        type:1,
                        nams :'/images/peopleobjectselect.png',
                    },
                    {
                        name : '/images/moneyselect.png',
                        // type : 'danger',
                        type:1,
                        nams :'/images/money.png',
                    },
                    // {
                    //     name : '/images/money.png',
                    //     type : 'danger',
                    //     nam1 :'/images/moneyselect.png',
                    // },

                ],
                arry:[]
            }
        },
        methods:{
            select: function (option, idx) {
                var that = this;
                var index = idx;
                // that.options[that.selected].type='danger';
                if(that.options[index].type == 1){
                    that.options[index].type=2;
                }else {
                    that.options[index].type=1;
                }
                var s = 0;
                for (var i=0;i<this.options.length;i++){
                    if (this.options[i].type == 2){
                        s ++;
                    }
                }
                if (this.options.lengh == s){
                    this.wholes = true;
                }
            },
            wholesfn(){
                this.wholes = !this.wholes;
                if ( this.wholes ){
                    for (var i=0;i<this.options.length;i++){
                        this.options[i].type = 2

                    }
                }else{
                    for (var i=0;i<this.options.length;i++){
                        this.options[i].type = 1

                    }
                }
            },
            generate: function () {
                var that = this;
                $.post(
                    "/generate?name=" ,
                    {},
                    function (data) {
                        that.$router.push('/share/' + encodeURIComponent(data.image));
                    }
                );
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
        /* padding: 10px 100px; */
        margin: auto;
        height: 46px;
        overflow: hidden;
        width: 45%;
        border-radius: 12px;
        margin-top: 10px;

    }
    .select button{
        width: 70%;
        margin: 0 auto;
        display: block;
        /*height: 30px;*/
        overflow: hidden;
        background: #f7bc00;
        width: 100%;
        paddingp:0,
    }
    .select button img{
        height: 61px;
        margin-left: -25px;
        width: auto;
        width: auto;
        margin-top: -21px;
        border-radius: 18px;
        overflow: hidden;
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