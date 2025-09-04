<template>
  <v-app-bar>
    <!-- Mostrar solo en móvil -->
    <v-app-bar-nav-icon class="d-lg-none" @click="OpenCloseNavbar()"></v-app-bar-nav-icon>

    <!-- Mostrar solo en desktop, centrado horizontalmente -->
    <div class="d-none d-lg-flex justify-center flex-grow-1">
      <v-btn class="navbar-logo" @click="ToInfo()">INFORMACIÓN</v-btn>
      <v-btn class="navbar-logo" @click="ToMusica()">MÚSICA</v-btn>
      <img @click="ToInicio()" src="../../images/logo-boda.png" alt="divider" class="navbar-logo">
      <v-btn class="navbar-logo" @click="ToFotos()">FOTOS</v-btn>
      <v-btn class="navbar-logo" @click="ToConfirmacion()">ASISTENCIA</v-btn>
    </div>

    <!-- Botón de login a la derecha -->
  <v-btn v-if="!this.$store.getters.isUserLogged"color="primary" class="login-btn" @click="showLoginDialog = true">Iniciar sesión</v-btn>
  <v-btn v-if="this.$store.getters.isUserLogged" color="primary" class="login-btn" @click="logout()">Cerrar sesión</v-btn>
  
  <Login 
  v-model="showLoginDialog"
  @cancel="showLoginDialog = false"/>

  </v-app-bar>
</template>

<script>
import Login from '../dialogs/Login.vue';

export default {
  components: { Login },
  data() {
    return {
      showLoginDialog: false
    };
  },
  methods: {
    ToInicio() {
      this.$router.push('/inicio').catch(() => {});
    },
    ToInfo() {
      this.$router.push('/informacion-util').catch(() => {});
    },
    ToMusica() {
      this.$router.push('/musica').catch(() => {});
    },
    ToFotos() {
      this.$router.push('/fotos').catch(() => {});
    },
    ToConfirmacion() {
      this.$router.push('/confirmacion-asistencia').catch(() => {});
    },
    OpenCloseNavbar() {
      this.$emit('OpenCloseNavbar');
    },
    logout() {
      this.$store.dispatch("setLogout");
    }
  }
}
</script>
<style scoped>
.navbar-logo {
  height: 100%;
  max-height: 56px; /* Altura típica de v-app-bar */
  max-width: 120px;
  object-fit: contain;
  display: block;
  margin-left: 18px;
  margin-right: 18px;
  align-self: center;
}

.login-btn {
  position: absolute;
  right: 0;
  margin-right: 16px;
}
</style>