import Vue from "vue"
import Vuex from "vuex"
import createPersistedState from "vuex-persistedstate"

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        isActive: 1
    },
    mutations: {
        setIsActive(state, isActive) {
            state.isActive = isActive
        }
    },
    // `createPersistedState()`でインスタンス作成。引数に設定を書く
    plugins: [createPersistedState(
        { // ストレージのキーを指定。デフォルトではvuex
            key: 'vuex_yuuki',

            // 管理対象のステートを指定。pathsを書かない時は`modules`に書いたモジュールに含まれるステート全て。`[]`の時はどれも保存されない
            // paths: [
            //     'auth.isLoggedIn',
            //     'master.dataSelected'
            // ],

            // ストレージの種類を指定する。デフォルトではローカルストレージ
            storage: window.sessionStorage
        }
    )]
});