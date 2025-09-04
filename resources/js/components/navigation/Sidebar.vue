<template>
  <v-navigation-drawer style="height: 100vh;"  v-model="vShowNavbar" app permanent class="bg-grey-lighten-3">
            <v-list density="compact" :elevation="3">
              <v-list-item
                  :title=$store.state.user_name
                  :subtitle=$store.state.user_mail>
              </v-list-item>
            </v-list>
            <v-list>
                <v-list-item
                v-for="item in items"
                :key="item.title"
                link
                @click="item.action ? item.action() : null"
                >
                <v-list-item-icon>
                    <v-icon>{{ item.icon }}</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                    <v-list-item-title>{{ item.title }}</v-list-item-title>
                </v-list-item-content>
                </v-list-item>
            </v-list>
            <template v-slot:append>
              <div class="pa-2">
                <v-btn color="first" block @click="logout()">
                  cerrar sesión
                </v-btn>
              </div>
            </template>
  </v-navigation-drawer>  
</template>
   
   <script>
    export default {
     data () {
      return {
        items: [
          { title: 'Dashboard', icon: 'mdi-view-dashboard' },
          { title: 'Account', icon: 'mdi-account-box', action: () => this.$router.push('/account') },
          { title: 'Settings', icon: 'mdi-cog', action: () => this.$router.push('/settings') },
          { title: 'Users', icon: 'mdi-account-multiple', action: () => this.$router.push('/users') },
          { title: 'Admin', icon: 'mdi-gavel' },
        ],

      }
     },

     methods: {
        logout: function () {
            this.$store.dispatch("setLogout");
            this.$router.push("/");
        }
    },

    }
   </script>