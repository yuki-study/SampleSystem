export default {
    install(vue) {
        vue.prototype.$setInterval = (func, intervalMilliSec) => {
            const id = setInterval(() => {
                // document.visibilityState が visible の状態が、ページ(タブ)がアクティブとなりこの状態で実行される
                // 確認できた
                if (document.visibilityState === 'visible') {
                    func()
                }
            }, intervalMilliSec)
            return id
        }
        // ページ遷移時の処理実行をクリア start
        // setIntervalをグローバルで管理する
        vue.prototype.$clearInterval = (id) => {
            clearInterval(id)
            //vue.prototype.$intervals = vue.prototype.$intervals.filter(i => i !== id)
        }
        vue.prototype.$clearAllIntervals = () => {
            if (vue.prototype.$intervals) {
                vue.prototype.$intervals.forEach(clearInterval)
                vue.prototype.$intervals = []
            }
        }
        // ページ遷移時の処理実行をクリア end
    }
}