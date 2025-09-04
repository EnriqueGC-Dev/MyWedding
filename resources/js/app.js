import { createApp } from 'vue';
import store from './store/store';
import '@mdi/font/css/materialdesignicons.css';
import { es } from 'vuetify/locale'

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

// Components
import App from './components/App.vue';

import Navbar from './components/navigation/Navbar.vue';
import Sidebar from './components/navigation/Sidebar.vue';

//Router
import router from './router';


const app = createApp(App)
const vuetify = createVuetify({
  locale: {
    locale: 'es',
    fallback: 'es',
    messages: { es },
  },
  icons: {
    defaultSet: 'mdi',
  },
  components,
  directives,
  theme: {
    themes: {
      light: {
        dark: false,
        colors: {
          primary: '#113065',
          first: '#0f1056',
          second: '#151269', 
          thirth: '#81b1ce',
          fourth: '#aad6ec',
          cancel: "#FF0000",
          link: "#409fff",
        },
      },
    },
  },
})

app.use(store);
app.use(vuetify)
app.use(router)

app.component('App', App)
app.component('Navbar', Navbar)
app.component('Sidebar', Sidebar)

app.mount('#app');