import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

import home from './components/pages/home'
import bulletinboard from './components/pages/bulletinboard'
import mainboard from './admin/pages/board'
import admin from './admin/pages/adminUsers'

const routes = [
    {
        path:'/',
        component: home
    },
    {
        path:'/board',
        component: bulletinboard
    },
    {
        path:'/mainboard',
        component: mainboard
    },
    {
        path:'/admin',
        component: admin
    }

]

export default new Router({
    mode: 'history',
    routes
})