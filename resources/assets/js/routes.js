import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter);

export default new VueRouter({
    routes: [
        {
            name:"entry",
            path:'/',
            component: resolve =>void(require(['./components/entry.vue'], resolve))
        },
        {
            name:"upload",
            path:'/upload',
            component: resolve =>void(require(['./components/upload.vue'], resolve))
        },
        {
            name:"select",
            path:'/select',
            component: resolve =>void(require(['./components/select.vue'], resolve))
        },
        {
            name:"share",
            path:'/share/:image',
            component: resolve =>void(require(['./components/share.vue'], resolve))
        }
    ]
})