<template>
  <div class="container-fluid bg-dark mb-3">
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">メニュー</a>
        <div class="collapse navbar-collapse" id="navbarText">
          <ul class="navbar-nav mr-auto">
            <li
              class="nav-item"
              :class="{ active: isActive === '1' }"
              @click="change('1')"
            >
              <router-link to="/" class="nav-link"
                ><i class="fas fa-school"></i>学習帳</router-link
              >
            </li>
            <li
              class="nav-item"
              :class="{ active: isActive === '2' }"
              @click="change('2')"
            >
              <router-link to="/tax-calculation" class="nav-link"
                ><i class="fas fa-calculator"></i>税金計算</router-link
              >
            </li>
            <li
              class="nav-item"
              :class="{ active: isActive === '3' }"
              @click="change('3')"
            >
              <router-link to="/cryptocurrency" class="nav-link"
                ><i class="fab fa-bitcoin"></i>仮想通貨</router-link
              >
            </li>
            <li
              class="nav-item"
              :class="{ active: isActive === '4' }"
              @click="change('4')"
            >
              <router-link to="/vue-study" class="nav-link"
                ><i class="fab fa-bitcoin"></i
                >Vueライフサイクル確認</router-link
              >
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </div>
</template>

 <script>
export default {
  data() {
    return {
      isActive: "1",
    };
  },
  methods: {
    change: function (num) {
      this.isActive = num;
    },
  },
};
</script>

<style scoped>
</style>