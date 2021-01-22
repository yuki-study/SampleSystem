<template>
  <div class="container" id="cryptocurrency">
    <div class="form-group row align-items-center">
      <div class="col-12 clearfix">
        <div class="float-left"><h1>仮想通貨-Watcher</h1></div>
        <div class="float-right" v-if="date">
          <span class="text-right">基準価格取得日時：{{ date }}</span>
        </div>
      </div>
    </div>
    <div class="form-group row align-items-center">
      <div class="col-12">
        <div class="alert alert-info">
          <span
            >現在時点のデータを取得する(binanceApiを使用してBTC建てとUSDT建てのデータを取得している)
          </span>
          <div class="text-right">
            <button type="button" class="btn btn-primary" @click="getDate()">
              取得
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group row" v-if="date">
      <div class="col-12">
        <div class="alert alert-info">
          <span class="col-3" v-if="sortBtcKey">
            {{ sortKeyVal[sortBtcKey] }}: {{ sortUsdtAsc ? "昇順" : "降順" }}
          </span>
          <span class="col-3" v-if="sortUsdtKey">
            {{ sortKeyVal[sortBtcKey] }}: {{ sortUsdtAsc ? "昇順" : "降順" }}
          </span>
        </div>
      </div>
      <div class="col-6">
        <table class="table table-sm">
          <thead class="thead-dark">
            <tr>
              <th @click="sortBtc('symbol')">通貨</th>
              <th @click="sortBtc('priceChangePercent')">24時間の変動率(%)</th>
              <th @click="sortBtc('bidPrice')">価格</th>
              <th @click="sortBtc('quoteVolume')">出来高</th>
            </tr>
          </thead>
          <tbody>
            <tr
              class="micro"
              v-for="binanceData in filterSortBtc"
              :key="binanceData.symbol"
            >
              <td>{{ binanceData.symbol }}</td>
              <td>{{ binanceData.priceChangePercent }}%</td>
              <td>{{ binanceData.bidPrice }}</td>
              <td>{{ binanceData.quoteVolume }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-6">
        <table class="table table-sm">
          <thead class="thead-dark">
            <tr>
              <th @click="sortUsdt('symbol')">通貨</th>
              <th @click="sortUsdt('priceChangePercent')">24時間の変動率(%)</th>
              <th @click="sortUsdt('bidPrice')">価格</th>
              <th @click="sortUsdt('quoteVolume')">出来高</th>
            </tr>
          </thead>
          <tbody>
            <tr
              class="micro"
              v-for="binanceData in filterSortUsdt"
              :key="binanceData.symbol"
            >
              <td>{{ binanceData.symbol }}</td>
              <td>{{ binanceData.priceChangePercent }}%</td>
              <td>{{ binanceData.bidPrice }}</td>
              <td>{{ binanceData.quoteVolume }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

 <script>
import moment from "moment";

export default {
  name: "news",
  data: () => ({
    binanceDatas: [],
    errors: [],
    date: null,
    sortBtcKey: "priceChangePercent", // 24時間の変動率
    sortUsdtKey: "priceChangePercent", // 24時間の変動率
    sortBtcAsc: false,
    sortUsdtAsc: false,
    sortKeyVal: {
      symbol: "通貨",
      priceChangePercent: "24時間の変動率(%)",
      bidPrice: "価格",
      quoteVolume: "出来高",
    },
    intervalId: undefined,
  }),
  created() {
    this.$store.commit("setIsActive", 3);
  },
  watch: {
    $route: function (to, from) {
      if (to.path !== from.path) {
        this.$clearInterval(this.intervalId); //ページ遷移時に定期処理を解除
      }
    },
    $route: function (to) {
      // 別ページから仮想通貨ページに遷移した時に定期処理実行;
      if (to.path === "/cryptocurrency") {
        let i = 0;
        this.intervalId = this.$setInterval(() => {
          console.log("定期実行API_route" + i);
          i = i + 1;
          this.getDateTeiki();
        }, 10000);
      }
    },
  },
  computed: {
    filterSortBtc: function () {
      var data = this.binanceDatas["BTC"];

      // ソート処理
      if (this.sortBtcKey) {
        let set = 1;
        this.sortBtcAsc ? (set = 1) : (set = -1);
        // 非同期通信なので入ってからソート実施
        if (data) {
          data.sort((a, b) => {
            if (a[this.sortBtcKey] < b[this.sortBtcKey]) return -1 * set;
            if (a[this.sortBtcKey] > b[this.sortBtcKey]) return 1 * set;
            return 0;
          });
        }
      }

      return data;
    },
    filterSortUsdt: function () {
      var data = this.binanceDatas["USDT"];

      // ソート処理
      if (this.sortUsdtKey) {
        let set = 1;
        this.sortUsdtAsc ? (set = 1) : (set = -1);
        // 非同期通信なので入ってからソート実施
        if (data) {
          data.sort((a, b) => {
            if (a[this.sortUsdtKey] < b[this.sortUsdtKey]) return -1 * set;
            if (a[this.sortUsdtKey] > b[this.sortUsdtKey]) return 1 * set;
            return 0;
          });
        }
      }

      return data;
    },
  },
  methods: {
    sortBtc(key) {
      this.sortBtcKey === key
        ? (this.sortUsdtAsc = !this.sortUsdtAsc)
        : (this.sortUsdtAsc = true);
      this.sortUsdtKey = key;
    },
    sortUsdt(key) {
      this.sortUsdtKey === key
        ? (this.sortUsdtAsc = !this.sortUsdtAsc)
        : (this.sortUsdtAsc = true);
      this.sortUsdtKey = key;
    },
    // APIからデータ取得
    async getDate() {
      let current_date = new Date();
      this.date = moment(current_date).format("YYYY/MM/DD HH:mm");

      this.getBinanceApi();
    },
    async getBinanceApi() {
      await axios
        .get(`/api/crypto`)
        .then((res) => {
          this.binanceDatas = res.data;
          console.log("API通信OK", res);
        })
        .catch((error) => {
          this.hanldeAjaxError(error);
          console.log("エラー");
        });
    },
    // APIからデータ取得
    async getDateTeiki() {
      let current_date = new Date();
      this.date = moment(current_date).format("YYYY/MM/DD HH:mm");

      this.getBinanceApi();
    },
    async getBinanceApi() {
      await axios
        .get(`/api/crypto`)
        .then((res) => {
          this.binanceDatas = res.data;
          // console.log("API通信OK", res);
        })
        .catch((error) => {
          this.hanldeAjaxError(error);
          console.log("エラー");
        });
    },
  },
};
</script>

<style scoped>
.micro {
  font-size: 5pt;
  line-height: 50%;
}
</style>