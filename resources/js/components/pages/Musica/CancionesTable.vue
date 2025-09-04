<template>
  <div>
    <h2 class="my-4">Canciones añadidas</h2>
  <v-list v-if="songs.length" class="w-100 w-md-75 w-lg-50 mx-auto" style="max-width: 700px;">
  <v-list-item v-for="song in songs" :key="song.id" class="flex-column flex-md-row align-center">
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
          <span style="margin-left: 12px; color: #8B5CF6;">👍 {{ song.likes }}</span>
          <span style="margin-left: 12px; color: #EF4444;">👎 {{ song.dislikes }}</span>
        </template>
      </v-list-item>
    </v-list>
    <div v-else class="text-center my-4">No hay canciones añadidas aún.</div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      songs: [],
      audio: null,
      refreshInterval: null
    }
  },
  mounted() {
    this.fetchSongs();
    this.refreshInterval = setInterval(this.fetchSongs, 5000); // 30 segundos
  },
  beforeUnmount() {
    if (this.refreshInterval) clearInterval(this.refreshInterval);
  },
  methods: {
    async fetchSongs() {
      try {
        const response = await fetch('/canciones-list');
        if (!response.ok) throw new Error('Error al cargar las canciones');
        this.songs = await response.json();
      } catch (e) {
        this.songs = [];
      }
    },
    playPreview(song) {
      if (song.preview) {
        if (!this.audio) {
          this.audio = new Audio();
        } else {
          this.audio.pause();
        }
        this.audio.src = song.preview;
        this.audio.play();
      }
    }
  }
}
</script>
