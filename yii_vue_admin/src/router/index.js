import Vue from 'vue'
import Router from 'vue-router'

import Login from '@/pages/Login'

Vue.use(Router);

export const constantRouterMap = [
    {
        path: '/',
        redirect: '/login'
    },

    {
        path: '/login',
        component: Login
    },

    {
        path: '/403',
        component: resolve => require(['../pages/403.vue'], resolve),
        meta: { title: '403' }
    },
    {
        path: '/404',
        component: resolve => require(['../pages/404.vue'], resolve),
        meta: { title: '404' }
    },
    {
        path: '/pages/cms/articles/edit',
        component: resolve => require(['../pages/cms/articles/edit.vue'], resolve),
        meta: { title: 'edit' }
    },

    {
        path: '../pages/layout/home.vue',
        component: resolve => require(['../pages/layout/home.vue'], resolve),
        meta: { title: '公共部分' },
    },
];

export default new Router({
    scrollBehavior: () => ({ y: 0 }),
    routes: constantRouterMap
});

