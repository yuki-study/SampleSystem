<template>
  <div class="container-fluid bg-dark mb-3">
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">メニュー</a>
        <div class="collapse navbar-collapse" id="navbarText">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <router-link to="/" class="nav-link"><i class="fas fa-lightbulb"></i>Main</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/tax-calculation" class="nav-link"><i class="fas fa-calculator"></i>税金計算</router-link>
            </li>
            <li class="nav-item">
              <router-link to="/" class="nav-link" disabled>未定</router-link>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </div>
</template>
 