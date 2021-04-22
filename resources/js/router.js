import Vue from 'vue';
import Router from 'vue-router';

// 引用頁面的 Component
import example from './components/ExampleComponent.vue';

// 使用 Vue Router
Vue.use(Router);

// Route 設定
export const routes = [
  { path: '/', component: example, name:'home'},
  { path: '*', redirect: '/home' },
];

// 建立 Vue Router instance
const router = new Router({
  mode: 'history',
  routes
});

export default router;