
<template>
  <!-- 税率計算画面 -->
  <div class="container">
    <validation-observer ref="obs" v-slot="ObserverProps">
      <h1>税金計算</h1>
      <div class="jumbotron jumbotron-fluid">
        <div class="container">
          <h1 class="display-5">該当データの取得</h1>
          <p class="lead">
            対象年度、パターンを選択したあとに「取得」ボタンをクリックすると過去データの取得が可能です。<br />
            編集したい場合は入力項目を入力し「登録」ボタンを押下してください。
          </p>
          <!-- align-items-center縦の中央寄せ、justify-content-start横の先頭寄せ -->
          <div class="d-flex align-items-center justify-content-start">
            <label class="mt-2" for="year">対象年度:</label>
            <select id="year" v-model="targetYear" class="form-control col-4">
              <option
                v-for="(year, index) in yearList"
                :key="index"
                :value="year"
              >
                {{ year }}年
              </option>
            </select>
            <div class="custom-control custom-radio ml-3">
              <input
                type="radio"
                id="pattern1"
                class="custom-control-input"
                v-model="patternFlg"
                value="1"
              />
              <label class="custom-control-label" for="pattern1"
                >パターン１</label
              >
            </div>
            <div class="custom-control custom-radio">
              <input
                type="radio"
                id="pattern2"
                class="custom-control-input"
                v-model="patternFlg"
                value="2"
              />
              <label class="custom-control-label" for="pattern2"
                >パターン２</label
              >
            </div>
            <div class="custom-control custom-radio">
              <input
                type="radio"
                id="pattern3"
                class="custom-control-input"
                v-model="patternFlg"
                value="3"
              />
              <label class="custom-control-label" for="pattern3"
                >パターン３</label
              >
            </div>
            <div class="custom-control custom-radio">
              <input
                type="radio"
                id="pattern4"
                class="custom-control-input"
                v-model="patternFlg"
                value="4"
              />
              <label class="custom-control-label" for="pattern4"
                >パターン４</label
              >
            </div>
            <div class="custom-control custom-radio">
              <input
                type="radio"
                id="pattern5"
                class="custom-control-input"
                v-model="patternFlg"
                value="5"
              />
              <label class="custom-control-label" for="pattern5"
                >パターン５</label
              >
            </div>
          </div>
          <div class="text-right">
            <button
              type="button"
              class="btn btn-primary btn-lg"
              @click="getTaxData()"
            >
              取得
            </button>
          </div>
        </div>
      </div>
      <div class="alert alert-danger" role="alert" v-if="serverErrors">
        <ul>
          <li v-for="(error, key) in serverErrors" :key="key">
            {{ error }}
          </li>
        </ul>
      </div>
      <div class="row mt-2">
        <div class="col"><h3>パターン名の入力</h3></div>
      </div>
      <div class="row">
        <div class="col-4">
          <div class="form-group">
            <validation-provider
              name="パターン名"
              rules="length:100"
              v-slot="{ errors }"
            >
              <input
                type="text"
                class="form-control"
                :class="{ 'is-invalid': errors[0] }"
                v-model.trim="patternMemo"
                placeholder="30%経費の場合など"
              />
              <div :class="{ 'invalid-feedback': errors[0] }">
                {{ errors[0] }}
              </div>
            </validation-provider>
          </div>
        </div>
      </div>
      <div class="row mt-2">
        <div class="col"><h3>収入</h3></div>
      </div>
      <div class="row">
        <div class="col-4">
          <div class="form-group">
            <span class="badge badge-danger">必須</span>
            <validation-provider
              name="収入"
              rules="required|numeric|min_value:999999"
              v-slot="{ errors }"
            >
              <input
                type="text"
                class="form-control"
                :class="{ 'is-invalid': errors[0] }"
                v-model.trim.number="income"
                placeholder="金額（円）"
              />
              <div :class="{ 'invalid-feedback': errors[0] }">
                {{ errors[0] }}
              </div>
            </validation-provider>
          </div>
        </div>
      </div>
      <div class="row mt-2">
        <div class="col"><h3>経費</h3></div>
      </div>
      <div class="row align-items-center">
        <div class="col-4">
          <div class="form-group">
            <span class="badge badge-danger">必須</span>
            <validation-provider
              name="経費"
              rules="required|numeric"
              v-slot="{ errors }"
            >
              <input
                type="text"
                class="form-control"
                :class="{ 'is-invalid': errors[0] }"
                v-model.trim.number="expenses"
                placeholder="金額（円）"
              />
              <div :class="{ 'invalid-feedback': errors[0] }">
                {{ errors[0] }}
              </div>
            </validation-provider>
          </div>
        </div>
        <div class="col-3 bg-light">
          <div class="custom-control custom-radio">
            <input
              type="radio"
              id="blue"
              class="custom-control-input"
              v-model="declarationFlg"
              value="1"
            />
            <label class="custom-control-label" for="blue">青色申告</label>
          </div>
          <div class="custom-control custom-radio">
            <input
              type="radio"
              id="white"
              class="custom-control-input"
              v-model="declarationFlg"
              value="2"
            />
            <label class="custom-control-label" for="white">白色申告</label>
          </div>
        </div>
      </div>
      <div class="row align-items-center mt-2 mb-2">
        <div class="col-2">
          <h3>所得控除</h3>
        </div>
        <div class="col-2">
          <button class="btn btn-primary btn-lg" @click="createDeduction()">
            追加
          </button>
        </div>
      </div>
      <div
        class="row align-items-center mb-2"
        v-for="(deduction, index) in deductions"
        :key="index"
      >
        <div class="form-group col-3">
          <validation-provider
            name="控除項目名"
            rules="required"
            v-slot="{ errors }"
          >
            <span class="badge badge-danger">必須</span>
            <input
              type="text"
              class="form-control"
              v-model.trim="deduction.deductionName"
              placeholder="控除項目名"
              :class="{ 'is-invalid': errors[0] }"
            />
            <div :class="{ 'invalid-feedback': errors[0] }">
              {{ errors[0] }}
            </div>
          </validation-provider>
        </div>
        <div class="form-group col-2">
          <validation-provider
            name="控除金額"
            rules="numeric"
            v-slot="{ errors }"
          >
            <input
              type="text"
              class="form-control mt-4"
              v-model.trim.number="deduction.deductionMoney"
              placeholder="金額（円）"
            />
            <div :class="{ 'invalid-feedback': errors[0] }">
              {{ errors[0] }}
            </div>
          </validation-provider>
        </div>
        <button
          class="btn btn-danger btn-sm col-1 mt-2"
          @click="removeDeduction(index)"
        >
          削除
        </button>
      </div>
      <div class="row mt-2">
        <div class="col">
          <h3>各種計算</h3>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="alert alert-info">
            <strong>収入：</strong>{{ income | addComma }}
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="alert alert-info">
            <strong>経費：</strong>{{ expenses | addComma }}
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="alert alert-secondary">
            <strong>申告控除金額：</strong>{{ declarationLavel | addComma }}
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="alert alert-success">
            <strong>総所得金額：</strong
            >{{ totalIncomeAmountCalculation | addComma }}
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="alert alert-secondary">
            <strong>所得控除：</strong
            >{{ deductionMoneyCalculation | addComma }}
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="alert alert-success">
            <strong>課税所得：</strong
            >{{ taxableIncomeAmountCalculation | addComma }}
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="alert alert-info">
            <p>（課税所得金額×0.02）÷ 課税所得金額に対しての係数】＋2,000円</p>
            <strong>寄付金限度額：</strong>{{ donationCalculation | addComma }}
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="alert alert-warning">
            <p>
              (総所得金額 － 住民税基礎控除) * 所得割率 + 均等割額 ＝ 保険料
            </p>
            <div class="form-group row align-items-center">
              <label for="insuranceFeeDeduction" class="col-3"
                >住民税基礎控除 <span class="badge badge-danger">必須</span>
                <span
                  class="badge badge-primary"
                  v-tooltip.right="
                    '住民税基礎控除は所得税基礎控除とは別もので43万円になった。'
                  "
                  >?</span
                ></label
              >
              <validation-provider
                name="控除項目名"
                rules="required|numeric"
                v-slot="{ errors }"
              >
                <input
                  type="text"
                  id="insuranceFeeDeduction"
                  class="form-control form-control-sm"
                  v-model.trim.number="insuranceFeeDeduction"
                  placeholder="金額（円）"
                  :class="{ 'is-invalid': errors[0] }"
                />
                <div :class="{ 'invalid-feedback': errors[0] }">
                  {{ errors[0] }}
                </div>
              </validation-provider>
            </div>
            <div class="form-group row align-items-center">
              <label for="insuranceFeeIncome" class="col-3"
                >所得割率<span class="badge badge-danger">必須</span>
                <span
                  class="badge badge-primary"
                  v-tooltip.right="'練馬区のHP参照'"
                  >?</span
                ></label
              >
              <validation-provider
                name="所得割率"
                rules="required|max_value:100"
                v-slot="{ errors }"
              >
                <input
                  type="text"
                  id="insuranceFeeIncome"
                  class="form-control form-control-sm"
                  v-model.trim.number="insuranceFeeIncome"
                  placeholder="%"
                  :class="{ 'is-invalid': errors[0] }"
                />
                <div :class="{ 'invalid-feedback': errors[0] }">
                  {{ errors[0] }}
                </div>
              </validation-provider>
              <label for="insuranceFeeEqual" class="col-3"
                >均等割額<span class="badge badge-danger">必須</span>
                <span
                  class="badge badge-primary"
                  v-tooltip.right="'練馬区のHP参照'"
                  >?</span
                ></label
              >
              <validation-provider
                name="均等割額"
                rules="required|numeric"
                v-slot="{ errors }"
              >
                <input
                  type="text"
                  id="insuranceFeeEqual"
                  class="form-control form-control-sm"
                  v-model.trim.number="insuranceFeeEqual"
                  placeholder="金額（円）"
                  :class="{ 'is-invalid': errors[0] }"
                />
                <div :class="{ 'invalid-feedback': errors[0] }">
                  {{ errors[0] }}
                </div>
              </validation-provider>
            </div>
            <strong>保険料：</strong>{{ insuranceFeeCalculation | addComma }}
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="alert alert-warning">
            <p>（課税所得 * 所得割率） + 均等割額 ＝ 住民税</p>
            <div class="form-group row">
              <label for="municipalTaxIncome" class="col-3"
                >所得割率<span class="badge badge-danger">必須</span
                ><span
                  class="badge badge-primary"
                  v-tooltip.right="'練馬区のHP参照'"
                  >?</span
                ></label
              >
              <validation-provider
                name="所得割率"
                rules="required|max_value:100"
                v-slot="{ errors }"
              >
                <input
                  type="text"
                  id="municipalTaxIncome"
                  class="form-control form-control-sm"
                  v-model.trim.number="municipalTaxIncome"
                  placeholder="%"
                  :class="{ 'is-invalid': errors[0] }"
                />
                <div :class="{ 'invalid-feedback': errors[0] }">
                  {{ errors[0] }}
                </div>
              </validation-provider>
              <label for="municipalTaxEqual" class="col-3"
                >均等割額<span class="badge badge-danger">必須</span
                ><span
                  class="badge badge-primary"
                  v-tooltip.right="'練馬区のHP参照'"
                  >?</span
                ></label
              >
              <validation-provider
                name="均等割額"
                rules="required|numeric"
                v-slot="{ errors }"
              >
                <input
                  type="text"
                  id="municipalTaxEqual"
                  class="form-control form-control-sm"
                  v-model.trim.number="municipalTaxEqual"
                  placeholder="金額（円）"
                  :class="{ 'is-invalid': errors[0] }"
                />
                <div :class="{ 'invalid-feedback': errors[0] }">
                  {{ errors[0] }}
                </div>
              </validation-provider>
            </div>
            <strong>住民税：</strong>{{ municipalTaxCalculation | addComma }}
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="alert alert-warning">
            <p>（課税所得 * 所得税率） - 控除額 ＝ 所得税</p>
            <strong>所得税：</strong>{{ incomeTaxCalculation | addComma }}
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="alert alert-warning">
            <p>月々の年金額 * 12 ＝ 年金</p>
            <div class="form-group row">
              <label for="pension" class="col-2"
                >月々の年金額<span class="badge badge-danger">必須</span></label
              >
              <validation-provider
                name="月々の年金額"
                rules="required|numeric"
                v-slot="{ errors }"
              >
                <input
                  type="text"
                  id="pension"
                  class="form-control form-control-sm"
                  v-model.trim.number="pension"
                  placeholder="金額（円）"
                  :class="{ 'is-invalid': errors[0] }"
                />
                <div :class="{ 'invalid-feedback': errors[0] }">
                  {{ errors[0] }}
                </div>
              </validation-provider>
            </div>
            <strong>年金：</strong>{{ pensionCalculation | addComma }}
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="alert alert-success">
            <p>すべての税金合計</p>
            <strong>合計：</strong>{{ totalCalculation | addComma }}
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="text-right">
            <button
              type="button"
              class="btn btn-primary btn-lg"
              @click="submit()"
            >
              登録
            </button>
          </div>
        </div>
      </div>
    </validation-observer>
  </div>
</template>


 <script>
import mixin from "../mixin";

export default {
  mixins: [mixin],
  data() {
    return {
      yearList: [],
      targetYear: null, // 対象年度
      patternFlg: 1, // パターン
      patternMemo: null, // パターンメモ
      income: null, // 収入
      expenses: null, // 経費
      declarationFlg: 1, // 申告フラグ
      deductions: [
        // 所得控除内訳
        {
          deductionName: "基礎年金",
          deductionMoney: 480000,
        },
        {
          deductionName: "年金",
          deductionMoney: null,
        },
        {
          deductionName: "保険",
          deductionMoney: null,
        },
        {
          deductionName: "小規模企業共済",
          deductionMoney: 840000,
        },
        {
          deductionName: "寄付金",
          deductionMoney: null,
        },
      ],
      insuranceFeeDeduction: 430000, // 住民税基礎年金控除額
      insuranceFeeIncome: 9.5, // 国保所得割率
      insuranceFeeEqual: 52000, // 国保均等割額
      municipalTaxIncome: 10, // 住民税所得割率
      municipalTaxEqual: 5000, // 住民税所得割率
      pension: 16500, // 年金額
      serverErrors: null, // サーバーエラーの格納
    };
  },
  created() {
    // 選択年度の作成
    const year = new Date().getFullYear();
    const num = 10;
    // 指定年まで配列に追加
    for (let i = 0; i < num; i++) {
      this.yearList.unshift(year - i);
    }
    // 初期値の設定
    this.targetYear = year;
  },
  filters: {
    addComma: function (value) {
      if (value) {
        return value.toLocaleString();
      }
      return value;
    },
  },
  watch: {
    // income: function () {
    //   console.log("変更検知");
    //   this.declarationLavelSet();
    // },
  },
  computed: {
    // 申告控除金額
    declarationLavel: function () {
      if (this.declarationFlg == 1) {
        return 650000;
      } else {
        return 100000;
      }
    },
    // 総所得金額の計算
    totalIncomeAmountCalculation: function () {
      if (this.income > 0) {
        if (this.income < this.expenses + this.declarationLavel) {
          return 0;
        }
        return this.income - (this.expenses + this.declarationLavel);
      }
      return 0;
    },
    // 所得控除の計算
    deductionMoneyCalculation: function () {
      let sum = 0;
      this.deductions.forEach((value, i) => {
        sum += value.deductionMoney;
      });
      return sum;
    },
    // 課税所得の計算
    taxableIncomeAmountCalculation: function () {
      if (this.totalIncomeAmountCalculation > 0) {
        if (
          this.totalIncomeAmountCalculation < this.deductionMoneyCalculation
        ) {
          return 0;
        }
        return (
          this.totalIncomeAmountCalculation - this.deductionMoneyCalculation
        );
      }
      return 0;
    },
    // 寄付金限度額の計算
    // https://furu-sato.com/magazine/2142/採用
    donationCalculation: function () {
      if (this.taxableIncomeAmountCalculation > 0) {
        if (this.taxableIncomeAmountCalculation <= 1950000) {
          return (this.taxableIncomeAmountCalculation * 0.02) / 0.84895 + 2000;
        } else if (this.taxableIncomeAmountCalculation <= 3300000) {
          return (this.taxableIncomeAmountCalculation * 0.02) / 0.7979 + 2000;
        } else if (this.taxableIncomeAmountCalculation <= 6950000) {
          return (this.taxableIncomeAmountCalculation * 0.02) / 0.6958 + 2000;
        } else if (this.taxableIncomeAmountCalculation <= 9000000) {
          return (this.taxableIncomeAmountCalculation * 0.02) / 0.66517 + 2000;
        } else if (this.taxableIncomeAmountCalculation <= 18000000) {
          return (this.taxableIncomeAmountCalculation * 0.02) / 0.5937 + 2000;
        } else if (this.taxableIncomeAmountCalculation <= 40000000) {
          return (this.taxableIncomeAmountCalculation * 0.02) / 0.4916 + 2000;
        } else if (this.taxableIncomeAmountCalculation > 40000000) {
          return (this.taxableIncomeAmountCalculation * 0.02) / 0.44055 + 2000;
        }
      }
      return 0;
    },
    // 保険料の計算
    insuranceFeeCalculation: function () {
      if (
        this.totalIncomeAmountCalculation > 0 &&
        this.totalIncomeAmountCalculation > this.insuranceFeeDeduction
      ) {
        return (
          (this.totalIncomeAmountCalculation - this.insuranceFeeDeduction) *
            (this.insuranceFeeIncome * 0.01) +
          this.insuranceFeeEqual
        );
      }
      return 0;
    },
    // 住民税の計算
    municipalTaxCalculation: function () {
      if (this.taxableIncomeAmountCalculation > 0) {
        return (
          this.taxableIncomeAmountCalculation *
            (this.municipalTaxIncome * 0.01) +
          this.municipalTaxEqual
        );
      }
      return 0;
    },
    // 所得税の計算
    incomeTaxCalculation: function () {
      if (this.taxableIncomeAmountCalculation > 0) {
        if (this.taxableIncomeAmountCalculation <= 1950000) {
          return this.taxableIncomeAmountCalculation * 0.05;
        } else if (this.taxableIncomeAmountCalculation <= 3300000) {
          return this.taxableIncomeAmountCalculation * 0.1 - 97500;
        } else if (this.taxableIncomeAmountCalculation <= 6950000) {
          return this.taxableIncomeAmountCalculation * 0.2 - 427500;
        } else if (this.taxableIncomeAmountCalculation <= 9000000) {
          return this.taxableIncomeAmountCalculation * 0.23 - 636000;
        } else if (this.taxableIncomeAmountCalculation <= 18000000) {
          return this.taxableIncomeAmountCalculation * 0.33 - 1536000;
        } else if (this.taxableIncomeAmountCalculation <= 40000000) {
          return this.taxableIncomeAmountCalculation * 0.4 - 2796000;
        } else if (this.taxableIncomeAmountCalculation > 40000000) {
          return this.taxableIncomeAmountCalculation * 0.45 - 4796000;
        }
      }
      return 0;
    },
    // 年金の計算
    pensionCalculation: function () {
      return this.pension * 12;
    },
    // 住民税の計算
    totalCalculation: function () {
      return (
        this.insuranceFeeCalculation +
        this.municipalTaxCalculation +
        this.incomeTaxCalculation +
        this.pensionCalculation
      );
    },
  },
  mounted() {},
  methods: {
    // 所得控除配列から1件削除
    removeDeduction(index) {
      this.deductions.splice(index, 1);
    },
    // 所得控除配列を追加
    createDeduction() {
      let deductionInfo = {
        deductionName: "",
        deductionMoney: null,
      };
      this.deductions.push(deductionInfo);
    },
    async getTaxData() {
      const res = await axios
        .get(`/api/tax/${this.targetYear}/${this.patternFlg}`)
        .then((res) => {
          // thenで通信成功時の処理を記載します
          console.log("success", res.data);
        })
        .catch((error) => {
          this.hanldeAjaxError(error);
        });
    },
    async submit() {
      const isValid = await this.$refs.obs.validate();

      // エラーがない場合API呼び出し
      if (!isValid) {
        const res = await axios
          .post("/api/tax", {
            targetYear: this.targetYear,
            patternFlg: this.patternFlg,
            patternMemo: this.patternMemo,
            income: this.income,
            expenses: this.expenses,
            declarationFlg: this.declarationFlg,
            deductions: this.deductions,
            insuranceFeeDeduction: this.insuranceFeeDeduction,
            insuranceFeeIncome: this.insuranceFeeIncome,
            insuranceFeeEqual: this.insuranceFeeEqual,
            municipalTaxIncome: this.municipalTaxIncome,
            municipalTaxEqual: this.municipalTaxEqual,
            pension: this.pension,
          })
          .then((res) => {
            // thenで通信成功時の処理を記載します
            console.log("success", res.data);
            this.successMessage("登録完了しました。");
          })
          .catch((error) => {
            // catchで通信失敗又はバリデーションエラー時の処理を記載します
            if (error.response.status == 422) {
              let errors = {};
              for (var key in error.response.data.errors) {
                errors[key] = error.response.data.errors[key].join();
              }
              this.serverErrors = errors;
              this.hanldeAjaxError(error);
            } else {
              this.hanldeAjaxError(error);
            }
          });
      }
    },
  },
};
</script>

<style scoped>
.invalid-feedback {
  display: block;
}
</style>