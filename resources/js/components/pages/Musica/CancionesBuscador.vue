<template>
  <div class="d-flex flex-column align-center justify-center" style="margin-top: 32px;">
    <span style="color: red; font-weight: bold;" v-if="!this.$store.getters.isUserLogged">INICIA SESIÓN PARA PODER AÑADIR Y VOTAR LAS CANCIONES</span>
    <v-text-field
      v-if="this.$store.getters.isUserLogged"
      v-model="search"
      label="Buscar canción"
      outlined
      dense
      class="w-100 w-md-75 w-lg-50 mx-auto"
      style="max-width: 700px; font-size: 1.2rem;"
      @keyup.enter="searchDeezer"
      append-icon="mdi-magnify"
      @click:append="searchDeezer"
    ></v-text-field>
    <v-list v-if="results.length" class="w-100 w-md-75 w-lg-50 mx-auto" style="max-width: 600px;">
      <v-list-item
        v-for="track in paginatedResults"
        :key="track.id"
        :active="selectedTrack && selectedTrack.id === track.id"
        :active-color="'purple darken-4'"
        @click="selectTrack(track)"
        class="flex-column flex-md-row align-center"
      >
        <template #prepend>
          <v-avatar size="48">
            <img :src="track.album.cover_medium" alt="cover" />
          </v-avatar>
        </template>
        <v-list-item-title>{{ track.title }}</v-list-item-title>
        <v-list-item-subtitle>{{ track.artist.name }}</v-list-item-subtitle>
        <template #append>
          <v-btn v-if="track.preview" icon @click.stop="playPreview(track)"><v-icon>mdi-play-circle</v-icon></v-btn>
          <span v-else style="color: #888; font-size: 0.9em;">Sin preview</span>
        </template>
      </v-list-item>
    </v-list>
    <div v-if="results.length > pageSize" class="d-flex justify-center align-center my-2">
      <v-btn :disabled="page === 1" @click="page--" icon><v-icon>mdi-chevron-left</v-icon></v-btn>
      <span style="margin: 0 12px;">Página {{ page }} de {{ totalPages }}</span>
      <v-btn :disabled="page === totalPages" @click="page++" icon><v-icon>mdi-chevron-right</v-icon></v-btn>
    </div>
    <div v-if="selectedTrack && !selectedTrack.preview" style="color: #888; margin-bottom: 16px;">Esta canción no tiene preview disponible.</div>
    <v-btn
      v-if="this.$store.getters.isUserLogged"
      color="purple darken-4"
      class="ma-2"
      style="width: 200px;"
      :disabled="!selectedTrack"
      @click="addTrack"
    >
      Añadir canción
    </v-btn>
  </div>
</template>

<script>
export default {
  props: {
    onSongAdded: Function
  },
  data() {
    return {
      search: '',
      results: [],
      selectedTrack: null,
      previewUrl: '',
      page: 1,
      pageSize: 5,
      audio: null,
    };
  },
  computed: {
    paginatedResults() {
      const start = (this.page - 1) * this.pageSize;
      return this.results.slice(start, start + this.pageSize);
    },
    totalPages() {
      return Math.ceil(this.results.length / this.pageSize);
    }
  },
  methods: {
    searchDeezer() {
      if (!this.search) return;
      this.page = 1;
      const callbackName = 'deezerCallback_' + Math.random().toString(36).substr(2, 9);
      window[callbackName] = (json) => {
        this.results = json.data;
        delete window[callbackName];
      };
      const script = document.createElement('script');
      script.src = `https://api.deezer.com/search?q=${encodeURIComponent(this.search)}&output=jsonp&callback=${callbackName}`;
      script.onload = () => { script.remove(); };
      document.body.appendChild(script);
    },
    selectTrack(track) {
      this.selectedTrack = track;
      this.previewUrl = track.preview || '';
    },
    playPreview(track) {
      if (track.preview) {
        if (!this.audio) {
          this.audio = new Audio();
        } else {
          this.audio.pause();
        }
        this.audio.src = track.preview;
        this.audio.play();
      }
    },
    async addTrack() {
      if (this.selectedTrack) {
        const payload = {
          title: this.selectedTrack.title,
          artist: this.selectedTrack.artist.name,
          photo: this.selectedTrack.album.cover_medium,
          url: this.selectedTrack.link,
          preview: this.selectedTrack.preview
        };
        // Obtener el token CSRF de la meta etiqueta
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        try {
          const response = await fetch('/canciones', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': token || ''
            },
            body: JSON.stringify(payload)
          });
          if (!response.ok) throw new Error('Error al guardar la canción');
          const data = await response.json();
          alert('Canción añadida correctamente: ' + data.title);
          // Limpiar búsqueda y resultados
          this.search = '';
          this.results = [];
          this.selectedTrack = null;
          if (typeof this.onSongAdded === 'function') this.onSongAdded();
        } catch (error) {
          alert('No se pudo añadir la canción');
        }
      }
    }
  }
}
</script>
