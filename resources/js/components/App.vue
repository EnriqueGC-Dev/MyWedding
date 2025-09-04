<template>
    <v-app class="bg-grey-lighten-3">
      <Navbar @OpenCloseNavbar="OpenCloseNavbar"></Navbar>
      <Sidebar v-model="vShowNavbar"></Sidebar>

      <v-main class="d-flex align-center justify-center">
        <router-view :key="$route.fullPath"></router-view>
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