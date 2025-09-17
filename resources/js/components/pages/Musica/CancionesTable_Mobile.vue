<template>
  <div>
    <h2 class="my-4 text-center">Canciones añadidas</h2>
    <v-select
      v-model="sortBy"
      :items="sortOptions"
      label="Ordenar por"
      class="mb-4 w-100"
      hide-details
      dense
    />
    <v-list v-if="sortedSongs.length" class="w-100" style="max-width:100vw;">
      <v-list-item v-for="song in songsPage" :key="song.id" class="flex-column align-start py-2 px-1">
        <div class="d-flex align-center w-100">
          <v-avatar size="40" class="mr-2">
            <img :src="song.photo" alt="cover" />
          </v-avatar>
          <div class="flex-grow-1">
            <div class="font-weight-bold" style="font-size:1.1em;">{{ song.title }}</div>
            <div class="text-caption grey--text">{{ song.artist }}<span v-if="song.user && song.user.name"> — Añadida por: {{ song.user.name }}</span></div>
          </div>
          <v-btn icon @click.stop="togglePreview(song)">
            <v-icon>{{ isPlaying(song) ? 'mdi-stop-circle' : 'mdi-play-circle' }}</v-icon>
          </v-btn>
        </div>
        <div class="d-flex align-center mt-1 w-100 justify-space-between">
          <div>
            <v-btn flat icon :color="userLiked(song) ? 'deep-purple-accent-4' : ''" @click.stop="vote(song, 'like')">
              <span style="font-size: 1.3em;">👍</span>
            </v-btn>
            <span style="margin-left: 2px; color: #8B5CF6; font-size: 1.2em;">{{ song.likes }}</span>
            <v-btn flat icon :color="userDisliked(song) ? 'red-darken-2' : ''" @click.stop="vote(song, 'dislike')">
              <span style="font-size: 1.3em;">👎</span>
            </v-btn>
            <span style="margin-left: 2px; color: #EF4444; font-size: 1.2em;">{{ song.dislikes }}</span>
          </div>
          
        </div>
      </v-list-item>
      <div>
            <v-btn text icon @click="currentPage=1"><v-icon>mdi-skip-previous-outline</v-icon></v-btn>
            <v-btn text icon @click="changePage(-1)"><v-icon>mdi-chevron-left</v-icon></v-btn>
            <span>{{ currentPage }}</span>
            <v-btn text icon @click="changePage(1)"><v-icon>mdi-chevron-right</v-icon></v-btn>
            <v-btn text icon @click="lastPage()"><v-icon>mdi-skip-next-outline</v-icon></v-btn>
          </div>
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
  playingSongId: null,
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
      if ((!val && oldVal) || (val && !oldVal)) {
        this.fetchSongs();
      }
    },
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
    }, 2000);
  },
  beforeUnmount() {
    if (this.refreshInterval) clearInterval(this.refreshInterval);
  },
  methods: {
    async fetchUser() {
      try {
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
        this.fetchSongs();
      }
    },
    async fetchSongs() {
      try {
        const response = await fetch('/canciones-list', { credentials: 'include' });
        if (!response.ok) throw new Error('Error al cargar las canciones');
        let songs = await response.json();
        // Consultar el endpoint proxy del backend para cada deezer_id y añadir el campo preview
        songs = await Promise.all(songs.map(async song => {
          if (song.deezer_id) {
            try {
              const proxyRes = await fetch(`/deezer-preview/${song.deezer_id}`);
              if (proxyRes.ok) {
                const proxyData = await proxyRes.json();
                if (proxyData && proxyData.preview) song.preview = proxyData.preview;
              }
            } catch {}
          }
          return song;
        }));
        this.songs = songs;
        this.changePage(0);
      } catch (e) {
        this.songs = [];
      }
    },
    togglePreview(song) {
      if (!song.preview) {
        this.showSnackbar('Esta canción no tiene preview disponible.', 'red');
        return;
      }
      if (this.isPlaying(song)) {
        this.stopPreview();
      } else {
        this.playPreview(song);
      }
    },
    playPreview(song) {
      if (song.preview) {
        const audioTest = document.createElement('audio');
        const canPlay = audioTest.canPlayType('audio/mpeg') || audioTest.canPlayType('audio/mp3') || audioTest.canPlayType('audio/ogg');
        if (!canPlay) {
          this.showSnackbar('Tu navegador no soporta la reproducción de este formato.', 'red');
          return;
        }
        if (!this.audio) {
          this.audio = new Audio();
          this.audio.addEventListener('ended', () => {
            this.playingSongId = null;
          });
        } else {
          this.audio.pause();
        }
        this.audio.src = song.preview;
        this.audio.play().catch(() => {
          this.showSnackbar('No se pudo reproducir el preview. Formato o URL no soportada.', 'red');
        });
        this.playingSongId = song.id;
      } else {
        this.showSnackbar('Esta canción no tiene preview disponible.', 'red');
      }
    },
    stopPreview() {
      if (this.audio) {
        this.audio.pause();
        this.audio.currentTime = 0;
      }
      this.playingSongId = null;
    },
    isPlaying(song) {
      return this.playingSongId === song.id;
    },
    userLiked(song) {
      if (!song.user_like || !this.userId) return false;
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
