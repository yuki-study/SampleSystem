<template>
  <div class="container h-100" id="stady">
    <div class="row align-items-center">
      <div class="col-10 mx-auto">
        <div class="list-group">
          <a
            href="#!"
            class="list-group-item list-group-item-action list-group-item-light"
            >税金計算</a
          >
        </div>
      </div>
    </div>
    <div class="row align-items-center">
      <div class="col-6 mx-auto">
        <div class="jumbotron">I'm vertically centered</div>
      </div>
    </div>
    <div class="row align-items-center">
      <div class="col-6 mx-auto">
        <div class="jumbotron">I'm vertically centered</div>
      </div>
    </div>
  </div>
</template>

 <script>
export default {
  data() {
    return {
      email: null,
      email2: null,
    };
  },
  created() {
    this.$store.commit("setIsActive", 1);
  },
  mounted() {},
  methods: {},
};
</script>

<style scoped>
</style>