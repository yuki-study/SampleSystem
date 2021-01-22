export default {
    data() {
        return {
        }
    },
    computed: {
    },
    methods: {
        // 共通エラー処理
        hanldeAjaxError(error) {
            console.log("test");
            let options = {
                position: 'top-center',
                duration: 5000,
                fullWidth: true,
            };

            switch (error.response.status) {
                case 401:
                    this.$toasted.error("Unauthorized：未認証", options);
                    break;
                case 404:
                    this.$toasted.error("Not Found：リソースを発見できません", options);
                    break;
                case 405:
                    this.$toasted.error("Method Not Allowed：", options);
                    break;
                case 422:
                    this.$toasted.error("Unprocessable Entity：リクエストエラー", options);
                    break;
                case 500:
                    this.$toasted.error("サーバー側で処理方法がわからない事態が発生", options);
                    break;
                default:
                    this.$toasted.error("定義していないエラー", options);
                    break;
            }
        },
        // 共通成功メッセージ処理
        successMessage(message) {
            let options = {
                position: 'top-center',
                duration: 5000,
                fullWidth: true,
            };

            this.$toasted.success(message, options);
        },
        // 共通注意メッセージ処理
        infoMessage(message) {
            let options = {
                position: 'top-center',
                duration: 5000,
                fullWidth: true,
            };

            this.$toasted.info(message, options);
        },
        // 共通成功メッセージ処理
        errorMessage(message) {
            let options = {
                position: 'top-center',
                duration: 5000,
                fullWidth: true,
            };

            this.$toasted.error(message, options);
        },
    }
}