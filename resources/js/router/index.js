import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/pages/Home.vue';
import Confirmacion from '../components/pages/Confirmacion.vue';
import Fotos from '../components/pages/Fotos.vue';
import Info from '../components/pages/Info.vue';
import Musica from '../components/pages/Musica.vue';

const routes = [
    { path: '/', component: Home },
    { path: '/inicio', component: Home },
    { path: '/confirmacion-asistencia', component: Confirmacion },
    { path: '/fotos', component: Fotos },
    { path: '/informacion-util', component: Info },
    { path: '/musica', component: Musica },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;