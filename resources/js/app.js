require('./bootstrap');

window.Vue=require('vue')
import router from './router'
import ViewUI from 'view-design';
import store  from './store';
import 'view-design/dist/styles/iview.css';
Vue.use(ViewUI);
import common from './common'
Vue.mixin(common);

Vue.component('mainapp',require('./components/mainapp.vue').default)
const app = new Vue({
    el:'#app',
    router,store
}) 
