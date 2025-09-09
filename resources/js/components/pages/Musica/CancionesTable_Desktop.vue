<template>
  <div>
    <h2 class="my-4">Canciones añadidas</h2>
    <v-select
      v-model="sortBy"
      :items="sortOptions"
      label="Ordenar por"
      class="mb-4 w-100 w-md-50 w-lg-25 mx-auto"
      hide-details
      dense
    />
    <v-list v-if="sortedSongs.length" class="w-100 w-md-75 w-lg-50 mx-auto" style="max-width: 700px;">
      <v-list-item v-for="song in songsPage" :key="song.id" class="flex-column flex-md-row align-center">
        <template #prepend>
          <v-avatar size="48">
            <img :src="song.photo" alt="cover" />
          </v-avatar>
        </template>
        <v-list-item-title>{{ song.title }}</v-list-item-title>
        <v-list-item-subtitle>
          {{ song.artist }}
          <span v-if="song.user && song.user.name" class="ms-2 text-caption text-grey-darken-1">— Añadida por: {{ song.user.name }}</span>
        </v-list-item-subtitle>
        <template #append>
          <v-btn icon @click.stop="playPreview(song)"><v-icon>mdi-play-circle</v-icon></v-btn>
          <v-btn flat icon class="ma-2" :color="userLiked(song) ? 'deep-purple-accent-4' : ''" @click.stop="vote(song, 'like')">
            <span style="font-size: 1.5em;">👍</span>
          </v-btn>
          <span style="margin-left: 4px; color: #8B5CF6; font-size: 30px;">{{ song.likes }}</span>
          <v-btn flat icon class="ma-2" :color="userDisliked(song) ? 'red-darken-2' : ''" @click.stop="vote(song, 'dislike')">
            <span style="font-size: 1.5em;">👎</span>
          </v-btn>
          <span style="margin-left: 4px; color: #EF4444; font-size: 30px;">{{ song.dislikes }}</span>
        </template>
      </v-list-item>
      <v-btn text icon class="ml-2" @click="currentPage=1"><v-icon>mdi-skip-previous-outline</v-icon></v-btn>
      <v-btn text icon class="mr-2" @click="changePage(-1)"><v-icon>mdi-chevron-left</v-icon></v-btn> 
      <span>{{ currentPage }}</span>
      <v-btn text icon class="ml-2" @click="changePage(1)"><v-icon>mdi-chevron-right</v-icon></v-btn>
      <v-btn text icon class="ml-2" @click="lastPage()"><v-icon>mdi-skip-next-outline</v-icon></v-btn>

      
  </v-list>
  <div v-else class="text-center my-4">No hay canciones añadidas aún.</div>
    <v-snackbar v-model="snackbar.show" :color="snackbar.color" timeout="2000">
      {{ snackbar.text }}
    </v-snackbar>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  data() {
    return {
      songs: [],
      songsPage: [],
      audio: null,
      refreshInterval: null,
      userId: null,
      sortBy: 'likes',
      currentPage: 1,
      itemsPerPage: 5,
      start: 0,
      end: 5,
      sortOptions: [
        { title: 'Más popular', value: 'likes' },
        { title: 'Orden alfabético', value: 'alphabetical' },
        { title: 'Añadidas más antiguas', value: 'oldest' },
        { title: 'Añadidas más recientes', value: 'newest' }
      ],
      snackbar: {
        show: false,
        text: '',
        color: 'primary'
      }
    }
  },
  computed: {
    sortedSongs() {
      let arr = [...this.songs];
      if (this.sortBy === 'likes') {
        arr.sort((a, b) => {
          if (b.likes !== a.likes) return b.likes - a.likes;
          return a.dislikes - b.dislikes;
        });
      } else if (this.sortBy === 'alphabetical') {
        arr.sort((a, b) => a.title.localeCompare(b.title));
      } else if (this.sortBy === 'oldest') {
        arr.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
      } else if (this.sortBy === 'newest') {
        arr.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
      }
      return arr;
    }
  },
  watch: {
    userId(val, oldVal) {
      // Si el usuario cierra sesión o inicia sesión, refresca la lista para actualizar los colores
      if ((!val && oldVal) || (val && !oldVal)) {
        this.fetchSongs();
      }
    },
    // Si el usuario cierra sesión desde fuera, limpiar userId y refrescar
    '$store.getters.isUserLogged'(val) {
      if (!val) {
        this.userId = null;
        this.fetchSongs();
      }
    }
  },
  async mounted() {
    await this.fetchUser();
    this.fetchSongs();
        this.refreshInterval = setInterval(async () => {
      await this.fetchUser();
      this.fetchSongs();
    }, 500);

  },
  beforeUnmount() {
    if (this.refreshInterval) clearInterval(this.refreshInterval);
  },
  methods: {
    async fetchUser() {
      try {
        // Intenta obtener el id del usuario desde el store o desde la API
        let id = null;
        if (this.$store && this.$store.getters && this.$store.getters.user_id) {
          id = this.$store.getters.user_id;
        } else {
          const res = await axios.get('/data');
          id = res.data && res.data.id ? res.data.id : null;
        }
        this.userId = id !== null ? Number(id) : null;
      } catch {
        this.userId = null;
        // Si ocurre error (por ejemplo, tras logout), refresca canciones para limpiar colores
        this.fetchSongs();
      }
    },
    async fetchSongs() {
      try {
        const response = await fetch('/canciones-list', { credentials: 'include' });
        if (!response.ok) throw new Error('Error al cargar las canciones');
        this.songs = await response.json();
        this.changePage(0); // Actualiza la página actual
      } catch (e) {
        this.songs = [];
      }
    },
    playPreview(song) {
      if (song.preview) {
        // Validar que la URL sea reproducible (formato soportado)
        const audioTest = document.createElement('audio');
        const canPlay = audioTest.canPlayType('audio/mpeg') || audioTest.canPlayType('audio/mp3') || audioTest.canPlayType('audio/ogg');
        if (!canPlay) {
          this.showSnackbar('Tu navegador no soporta la reproducción de este formato.', 'red');
          return;
        }
        if (!this.audio) {
          this.audio = new Audio();
        } else {
          this.audio.pause();
        }
        this.audio.src = song.preview;
        this.audio.play().catch(() => {
          this.showSnackbar('No se pudo reproducir el preview. Formato o URL no soportada.', 'red');
        });
      } else {
        this.showSnackbar('Esta canción no tiene preview disponible.', 'red');
      }
    },
    userLiked(song) {
      if (!song.user_like || !this.userId) return false;
      // Asegura que ambos sean del mismo tipo (número)
      return song.user_like.map(Number).includes(Number(this.userId));
    },
    userDisliked(song) {
      if (!song.user_dislike || !this.userId) return false;
      return song.user_dislike.map(Number).includes(Number(this.userId));
    },
    async vote(song, type) {
      if (!this.$store.getters.isUserLogged) {
        this.showSnackbar('Debes iniciar sesión para votar.', 'red');
        return;
      }
      const alreadyLiked = this.userLiked(song);
      const alreadyDisliked = this.userDisliked(song);
      let action = type;
      let feedback = '';
      let color = 'primary';
      if ((type === 'like' && alreadyLiked)) {
        action = 'like';
        feedback = 'Like eliminado';
        color = 'grey';
      } else if ((type === 'dislike' && alreadyDisliked)) {
        action = 'dislike';
        feedback = 'Dislike eliminado';
        color = 'grey';
      } else if (type === 'like' && alreadyDisliked) {
        action = 'switch_to_like';
        feedback = '¡Cambiado a like!';
        color = 'deep-purple-accent-4';
      } else if (type === 'dislike' && alreadyLiked) {
        action = 'switch_to_dislike';
        feedback = '¡Cambiado a dislike!';
        color = 'red-darken-2';
      } else if (type === 'like') {
        feedback = '¡Te gusta esta canción!';
        color = 'deep-purple-accent-4';
      } else if (type === 'dislike') {
        feedback = 'No te gusta esta canción';
        color = 'red-darken-2';
      }
      try {
        await axios.post(`/canciones/${song.id}/vote`, { action });
        this.fetchSongs();
        this.showSnackbar(feedback, color);
      } catch (e) {
        this.showSnackbar('Error al votar', 'red');
      }
    },
    showSnackbar(text, color = 'primary') {
      this.snackbar.text = text;
      this.snackbar.color = color;
      this.snackbar.show = true;
    },

    changePage(value) {
      const totalPages = Math.ceil(this.sortedSongs.length / this.itemsPerPage);
      this.currentPage += value;
      if (this.currentPage < 1) this.currentPage = 1;
      if (this.currentPage > totalPages) this.currentPage = totalPages;
      this.start = (this.currentPage - 1) * this.itemsPerPage;
      this.end = this.start + this.itemsPerPage;
      this.songsPage = this.sortedSongs.slice(this.start, this.end);      
    },

    lastPage() {
      const totalPages = Math.ceil(this.sortedSongs.length / this.itemsPerPage);
      this.currentPage = totalPages;
      this.start = (this.currentPage - 1) * this.itemsPerPage;
      this.end = this.start + this.itemsPerPage;
      this.songsPage = this.sortedSongs.slice(this.start, this.end);      
    }
  }
}
</script>
