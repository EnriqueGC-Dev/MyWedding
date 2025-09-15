<template>
  <v-app >
      <Navbar @OpenCloseNavbar="OpenCloseNavbar"></Navbar>
      <Sidebar @OpenCloseNavbar="OpenCloseNavbar" v-model="vShowNavbar"></Sidebar>

      <v-main class="d-flex align-center justify-center">
        <router-view :key="$route.fullPath" class="app-background"></router-view>
      </v-main>

    </v-app>
</template>

<script>

export default {

  data() {
      return {
        vShowNavbar: false,
      };
  },

  methods: {
    OpenCloseNavbar: function () {
        this.vShowNavbar = !this.vShowNavbar;
    },
  }
  
}
  
 </script>

<style>
.app-background {
  background-image: url('/images/background.jpg');
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center center;
  height: 100%;
  width: 100%;
}
</style>