import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import Main from './components/Main.vue'
import Login from './components/Login.vue'
import TaxCalculation from './components/TaxCalculation.vue'

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter)

// パスとコンポーネントのマッピング
const routes = [
    {
        path: '/',
        component: Main
    },
    {
        path: '/tax-calculation',
        component: TaxCalculation
    }
]


// VueRouterインスタンスを作成する
const router = new VueRouter({
    mode: 'history',
    routes
})

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router