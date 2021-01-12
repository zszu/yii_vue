// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.

import Vue from 'vue'
import App from './App'
import router from './router'
import ElementUI from 'element-ui';


//富文本编辑器
import VueQuillEditor from 'vue-quill-editor'
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'quill/dist/quill.bubble.css'
Vue.use(VueQuillEditor);

import 'element-ui/lib/theme-chalk/index.css';    // 默认主题
import '../static/css/icon.css';

import "babel-polyfill";
//引入公共的 部分
import store from './store';

import http from './components/js/http.js';
import * as cusFilter from './components/js/filters.js';

// 导出的是对象，可以直接通过key和value来获得过滤器的名和过滤器的方法
Object.keys(cusFilter).forEach(key => {
    Vue.filter(key, cusFilter[key])
});

// 全局注册
Vue.use(ElementUI, { size: 'small' });

Vue.config.productionTip = false;
Vue.prototype.$http_ = http;




//刷新的时候重新加载路由
let localRules = window.localStorage.getItem('menus');

if(localRules){
    localRules = JSON.parse(localRules);

    store.dispatch('GenerateRoutes',localRules).then(() => {
        router.addRoutes(store.getters.addRouters)
    })
}


//使用钩子函数对路由进行权限跳转
router.beforeEach((to, from, next) => {
    next();
});
/* eslint-disable no-new */
// new Vue({
//   el: '#app',
//   router,
//   components: { App },
//   template: '<App/>'
// })
new Vue({
    router,
    render: h => h(App)
}).$mount('#app');
