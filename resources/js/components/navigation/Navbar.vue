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
    <v-btn color="primary" class="login-btn" @click="ToLogin()">Iniciar sesión</v-btn>
  </v-app-bar>
    ToLogin: function () {
      this.$router.push('/login').catch(() => {});
    },
</template>

<script>

export default {

  data() {
      return {
      };
  },

  methods: {
    ToInicio: function () {
      this.$router.push('/inicio').catch(() => {});
    },

    ToInfo: function () {
      this.$router.push('/informacion-util').catch(() => {});
    },

    ToMusica: function () {
      this.$router.push('/musica').catch(() => {});
    },

    ToFotos: function () {
      this.$router.push('/fotos').catch(() => {});
    },

    ToConfirmacion: function () {
      this.$router.push('/confirmacion-asistencia').catch(() => {});
    },

    OpenCloseNavbar: function () {
      this.$emit('OpenCloseNavbar');
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
  top: 50%;
  transform: translateY(-50%);
  margin-right: 16px;
}
</style>