<template>
  <div>
    <Navbar />
    <!-- 子コンポーネントの切り替え時にフォーム内容をキャッシュする -->
    <keep-alive>
      <RouterView />
    </keep-alive>
  </div>
</template>

<script>
import Navbar from './components/Navbar.vue'

export default {
  components: {
    Navbar
  },
}

</script>