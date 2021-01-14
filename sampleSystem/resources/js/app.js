require('./bootstrap');
// アイコンの読み込み
require("./fontawesome");

window.Vue = require('vue');

Vue.component('navbar', require('./components/Navbar.vue').default);

import Vue from 'vue'

// VeeValidationの設定 --start
import { extend, ValidationProvider, ValidationObserver, localize } from 'vee-validate';
// import { required } from 'vee-validate/dist/rules';
import * as rules from 'vee-validate/dist/rules' // 全てのバリデーションルール
import ja from 'vee-validate/dist/locale/ja.json';
// forループで全てのバリデーションルールをextendで登録する
for (let rule in rules) {
    extend(rule, {
        ...rules[rule], // add the rule
        message: ja.messages[rule] // 日本語メッセージを登録
    });
}
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver); // 入力エラー時のボタンへdisabledを設定する時に使える
localize({ ja });
// VeeValidationの設定 --end

// ツールチップ --start
import VTooltip from 'v-tooltip'
Vue.use(VTooltip)
// ツールチップ --end

// ルーティングの定義をインポートする
import router from './router'



const app = new Vue({
    el: '#app',
    router,
});
