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
            <button
              type="button"
              class="btn btn-primary"
              @click="clickBinance()"
            >
              取得
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="getMarketCap()"
            >
              MarketCap取得
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group row" v-if="date">
      <div class="col-12">
        <div class="alert alert-info">
          <div class="card-group">
            <div class="card">
              <div class="card-body">
                <p class="card-text">
                  <span v-if="sortBtcKey">
                    {{ sortKeyVal[sortBtcKey] }}:
                    {{ sortBtcAsc ? "昇順" : "降順" }}
                  </span>
                </p>
                <p class="card-text">
                  <span v-if="sortBtcKey">
                    <label for="volumeBtc">出来高</label>
                    <input
                      type="range"
                      class="custom-range"
                      min="0"
                      max="200"
                      step="10"
                      id="volumeBtc"
                      v-model="volumeBtc"
                    />
                    {{ volumeBtc }}
                  </span>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12">
        <table class="table table-sm">
          <thead class="thead-dark">
            <tr>
              <th @click="sortBtc('symbol')">通貨</th>
              <th @click="sortBtc('priceChangePercent')">24時間の変動率(%)</th>
              <th @click="sortBtc('bidPrice')">価格</th>
              <th @click="sortBtc('quoteVolume')">出来高</th>
              <th>ランキング</th>
              <th>追加日</th>
              <th>USDT値段</th>
              <th>1時間の変動率</th>
              <th>24時間の変動率</th>
              <th>7日間の変動率</th>
            </tr>
          </thead>
          <tbody>
            <tr
              class="micro"
              v-for="binanceData in filterSortBtc"
              :key="binanceData.symbol"
            >
              <td>{{ binanceData.symbol }}</td>
              <td
                :class="[
                  priceChangePercentColor(binanceData.priceChangePercent) ===
                  true
                    ? 'table-primary'
                    : 'table-danger',
                ]"
              >
                {{ binanceData.priceChangePercent }}%
              </td>
              <td
                :class="[
                  bidPriceColor(
                    binanceData.symbol,
                    'BTC',
                    binanceData.bidPrice
                  ) === true
                    ? 'table-primary'
                    : 'table-danger',
                ]"
              >
                {{ binanceData.bidPrice }}({{
                  bidPriceGetData(
                    binanceData.symbol,
                    "BTC",
                    binanceData.bidPrice
                  )
                }})%
              </td>
              <td
                :class="[
                  quoteVolumeColor(
                    binanceData.symbol,
                    'BTC',
                    binanceData.quoteVolume
                  ) === true
                    ? 'table-primary'
                    : 'table-danger',
                ]"
              >
                {{ binanceData.quoteVolume }}({{
                  quoteVolumeGetData(
                    binanceData.symbol,
                    "BTC",
                    binanceData.quoteVolume
                  )
                }})%
              </td>
              <td>
                <strong class="badge badge-success">{{
                  getRanking(binanceData.symbol)
                }}</strong>
              </td>
              <td>
                {{ getDateAdd(binanceData.symbol) }}
              </td>
              <td>
                {{ getUsdtPrice(binanceData.symbol) }}
              </td>
              <td
                :class="[
                  getColorChange1h(binanceData.symbol) === true
                    ? 'table-primary'
                    : 'table-danger',
                ]"
              >
                {{ getUsdtChange1h(binanceData.symbol) }}
              </td>
              <td
                :class="[
                  getColorChange24h(binanceData.symbol) === true
                    ? 'table-primary'
                    : 'table-danger',
                ]"
              >
                {{ getUsdtChange24h(binanceData.symbol) }}
              </td>
              <td
                :class="[
                  getColorChange7d(binanceData.symbol) === true
                    ? 'table-primary'
                    : 'table-danger',
                ]"
              >
                {{ getUsdtChange7d(binanceData.symbol) }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import moment from "moment";

export default {
  name: "Cryptocurrency",
  data: () => ({
    criteriaBinanceDatas: null,
    binanceDatas: [],
    errors: [],
    date: null,
    sortBtcKey: "priceChangePercent", // 24時間の変動率
    sortBtcAsc: false,
    sortKeyVal: {
      symbol: "通貨",
      priceChangePercent: "24時間の変動率(%)",
      bidPrice: "価格",
      quoteVolume: "出来高",
    },
    volumeBtc: 100,
    intervalId: undefined,
  }),
  created() {
    this.$store.commit("setIsActive", 3);
  },
  watch: {
    // $route: function (to, from) {
    //   if (to.path !== from.path) {
    //     console.log("clearInterval");
    //     this.$clearInterval(this.intervalId); //ページ遷移時に定期処理を解除
    //   }
    // },
  },
  computed: {
    ...mapState(["marketCap"]),
    filterSortBtc: function () {
      if (this.binanceDatas["BTC"]) {
        var data = this.binanceDatas["BTC"];
        let datas = [];
        if (this.volumeBtc) {
          data.forEach((value, key) => {
            if (value["quoteVolume"] >= this.volumeBtc) {
              datas.push(value);
            }
          });
          data = datas;
        }

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
      }
      return [];
    },
  },
  methods: {
    sortBtc(key) {
      this.sortBtcKey === key
        ? (this.sortBtcAsc = !this.sortBtcAsc)
        : (this.sortBtcAsc = true);
      this.sortBtcKey = key;
    },
    clickBinance() {
      this.$clearInterval(this.intervalId); //ページ遷移時に定期処理を解除
      // １回目のデータ取得ができたら定期実行処理
      if (this.getDate()) {
        let i = 0;
        this.intervalId = this.$setInterval(() => {
          console.log("定期実行API_clickBinance" + i);
          i = i + 1;
          this.getDate();
        }, 10000);
      }
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
          // 初回のみ登録する
          if (this.criteriaBinanceDatas === null) {
            this.criteriaBinanceDatas = this.binanceDatas;
          }
          console.log("API通信OK");
        })
        .catch((error) => {
          this.hanldeAjaxError(error);
          console.log("エラー");
        });
    },
    // 24時間の変動率色付け処理
    priceChangePercentColor: function (priceChangePercent) {
      //　先頭の１文字を見て色わけをする
      var str = String(priceChangePercent).slice(0, 1);
      var colorFlg = true;
      if (str == "-") {
        colorFlg = false;
      }
      return colorFlg;
    },
    bidPriceGetData: function (symbol, str, current) {
      //　基準日時点の価格を取得
      let taget = this.criteriaBinanceDatas[str].find((data) => {
        return data.symbol === symbol;
      });
      //return taget.bidPrice;
      let sum = ((current - taget.bidPrice) / taget.bidPrice) * 100;
      return Math.round(sum * 100) / 100;
    },
    bidPriceColor: function (symbol, str, current) {
      //　基準日時点の価格を取得
      let taget = this.criteriaBinanceDatas[str].find((data) => {
        return data.symbol === symbol;
      });
      let sum = current - taget.bidPrice;
      var str = String(sum).slice(0, 1);
      var colorFlg = true;
      if (str == "-") {
        colorFlg = false;
      }
      return colorFlg;
    },
    quoteVolumeGetData: function (symbol, str, current) {
      //　基準日時点の出来高を取得
      let taget = this.criteriaBinanceDatas[str].find((data) => {
        return data.symbol === symbol;
      });
      //return taget.quoteVolume;
      let sum = ((current - taget.quoteVolume) / taget.quoteVolume) * 100;
      return Math.round(sum * 100) / 100;
    },
    quoteVolumeColor: function (symbol, str, current) {
      //　基準日時点の価格を取得
      let taget = this.criteriaBinanceDatas[str].find((data) => {
        return data.symbol === symbol;
      });
      let sum = current - taget.quoteVolume;
      var str = String(sum).slice(0, 1);
      var colorFlg = true;
      if (str == "-") {
        colorFlg = false;
      }
      return colorFlg;
    },
    async getMarketCap() {
      await axios
        .get(`/api/marketCap`)
        .then((res) => {
          this.$store.commit("setMarketCap", res.data.data);
          console.log("API通信OK");
        })
        .catch((error) => {
          this.hanldeAjaxError(error);
          console.log("エラー");
        });
    },
    // マーケットキャップデータより表示されているコインのデータを取得して返却
    getMarketCapFilter: function (symbol) {
      //　基準日時点の出来高を取得
      let taget = this.$store.state.marketCap.find((data) => {
        if (symbol.indexOf(data.symbol) > -1) {
          return data;
        }
      });

      return taget;
    },
    // ランキングデータを返却
    getRanking: function (symbol) {
      //　基準日時点の出来高を取得
      let taget = this.getMarketCapFilter(symbol);

      if (!taget) {
        return "外";
      }
      return taget.cmc_rank;
    },
    // 追加日を返却
    getDateAdd: function (symbol) {
      //　基準日時点の出来高を取得
      let taget = this.getMarketCapFilter(symbol);

      if (!taget) {
        return "不明";
      }
      return moment(taget.date_added).format("YYYY/MM/DD");
    },
    // USDTの値段
    getUsdtPrice: function (symbol) {
      //　基準日時点の出来高を取得
      let taget = this.getMarketCapFilter(symbol);

      if (!taget) {
        return "不明";
      }
      return taget.quote.USD.price;
    },
    // USDTの1時間での変動値
    getUsdtChange1h: function (symbol) {
      //　基準日時点の出来高を取得
      let taget = this.getMarketCapFilter(symbol);

      if (!taget) {
        return "不明";
      }
      return taget.quote.USD.percent_change_1h;
    },
    // USDTの24時間での変動値
    getUsdtChange24h: function (symbol) {
      //　基準日時点の出来高を取得
      let taget = this.getMarketCapFilter(symbol);

      if (!taget) {
        return "不明";
      }
      return taget.quote.USD.percent_change_24h;
    },
    // USDTの7日間での変動値
    getUsdtChange7d: function (symbol) {
      //　基準日時点の出来高を取得
      let taget = this.getMarketCapFilter(symbol);

      if (!taget) {
        return "不明";
      }
      return taget.quote.USD.percent_change_7d;
    },
    // カラーフラグを返却
    getColorFlg: function (val) {
      let str = String(val).slice(0, 1);
      let colorFlg = true;
      if (str == "-") {
        colorFlg = false;
      }
      return colorFlg;
    },
    // USDTの1時間のカラー状態を返却
    getColorChange1h: function (symbol) {
      let taget = this.getMarketCapFilter(symbol);

      if (!taget) {
        return false;
      }

      return this.getColorFlg(taget.quote.USD.percent_change_1h);
    },
    // USDTの24時間のカラー状態を返却
    getColorChange24h: function (symbol) {
      let taget = this.getMarketCapFilter(symbol);

      if (!taget) {
        return false;
      }

      return this.getColorFlg(taget.quote.USD.percent_change_24h);
    },
    // USDTの7日間のカラー状態を返却
    getColorChange7d: function (symbol) {
      let taget = this.getMarketCapFilter(symbol);

      if (!taget) {
        return false;
      }

      return this.getColorFlg(taget.quote.USD.percent_change_7d);
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
