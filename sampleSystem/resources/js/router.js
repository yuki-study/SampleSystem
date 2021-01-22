import Vue from 'vue'
import VueRouter from 'vue-router'

// ページコンポーネントをインポートする
import Stady from './components/Stady.vue'
import TaxCalculation from './components/TaxCalculation.vue'
import Cryptocurrency from './components/Cryptocurrency.vue'
import VueStudy from './components/VueStudy.vue'

// VueRouterプラグインを使用する
// これによって<RouterView />コンポーネントなどを使うことができる
Vue.use(VueRouter)

// パスとコンポーネントのマッピング
const routes = [
    {
        path: '/',
        component: Stady
    },
    {
        path: '/tax-calculation',
        component: TaxCalculation
    },
    {
        path: '/cryptocurrency',
        component: Cryptocurrency
    },
    {
        path: '/vue-study',
        component: VueStudy
    },
    {
        path: '*',
        component: Stady
    },
]


// VueRouterインスタンスを作成する
const router = new VueRouter({
    mode: 'history',
    routes
})

// VueRouterインスタンスをエクスポートする
// app.jsでインポートするため
export default router