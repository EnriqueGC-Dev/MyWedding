<template>
            <v-container fluid class="pa-4">
                <h2 class="mb-6 text-center font-weight-bold">Galería de Fotos y Videos</h2>
                <div v-if="$store.getters.isUserLogged" class="d-flex justify-center mb-4" style="gap: 12px;">
                    <!-- Input para subir archivo (galería o archivos) -->
                    <input
                        ref="fileInput"
                        type="file"
                        accept="image/*,video/*"
                        multiple
                        style="display:none"
                        @change="handleFileUpload"
                    />
                    <!-- Input para tomar foto (solo imagen, cámara) -->
                    <input
                        ref="cameraInput"
                        type="file"
                        accept="image/*"
                        capture="environment"
                        style="display:none"
                        @change="handleFileUpload"
                    />
                    <template v-if="isMobile">
                        <v-btn class="confirm-btn-bg"  @click="$refs.cameraInput.click()">
                            <v-icon left>mdi-camera</v-icon>
                            Tomar foto
                        </v-btn>
                        <v-btn class="confirm-btn-bg"  @click="$refs.fileInput.click()">
                            <v-icon left>mdi-upload</v-icon>
                            Subir archivo
                        </v-btn>
                    </template>
                    <template v-else>
                        <v-btn class="confirm-btn-bg"  @click="$refs.fileInput.click()">
                            <v-icon left>mdi-upload</v-icon>
                            Subir fotos o videos
                        </v-btn>
                    </template>
                </div>
                <div v-if="mediaList.length === 0" class="text-center grey--text mt-8">No hay imágenes ni videos para mostrar.</div>
                <v-simple-table class="media-table" v-else>
                    <tbody>
                        <tr v-for="row in pagedRows" :key="row[0]?.name">
                            <td v-for="media in row" :key="media.name" :style="{padding:'12px',verticalAlign:'top',width: isMobile ? '50%' : '25%'}">
                                <div class="media-card" @click="openDialog(media)">
                                    <video v-if="media.type === 'video'" :src="media.url" controls class="media-video" />
                                    <img v-else :src="media.url" :alt="media.name" class="media-img" loading="lazy" />
                                    <div class="media-date">{{ formatDate(media.mtime) }}</div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </v-simple-table>
                <v-pagination
                    v-if="pageCount > 1"
                    v-model="page"
                    :length="pageCount"
                    class="mt-4 mb-2 d-flex justify-center"
                    color="primary"
                />

                <v-dialog v-model="dialog" max-width="90vw" max-height="90vh" persistent>
                    <v-card class="pa-0" style="background:transparent;box-shadow:none;">
                                <v-btn icon color="white" class="dialog-close-btn" @click="dialog = false" style="position:fixed;top:24px;right:36px;z-index:1001;background:#22223b;">
                                    <v-icon>mdi-close</v-icon>
                                </v-btn>
                                <div v-if="selectedMedia" class="dialog-media-wrapper">
                                    <video v-if="selectedMedia.type === 'video'" :src="selectedMedia.url" controls autoplay style="max-width:90vw;max-height:80vh;display:block;margin:auto;border-radius:12px;" />
                                    <img v-else :src="selectedMedia.url" :alt="selectedMedia.name" style="max-width:90vw;max-height:80vh;display:block;margin:auto;border-radius:12px;" />
                                                <div class="dialog-nav-btns" style="display:flex;justify-content:center;gap:24px;margin-top:18px;align-items:center;">
                                                    <v-btn icon color="white" v-if="selectedIndex > 0" @click="prevMedia" style="background:#22223b;">
                                                        <v-icon>mdi-chevron-left</v-icon>
                                                    </v-btn>
                                                    <v-btn
                                                        v-if="$store.getters.isUserLogged && selectedMedia"
                                                        icon
                                                        color="white"
                                                        :href="selectedMedia.url"
                                                        :download="selectedMedia.name"
                                                        style="background:#38b2ac;"
                                                        target="_blank"
                                                    >
                                                        <v-icon>mdi-download</v-icon>
                                                    </v-btn>
                                                    <v-btn icon color="white" v-if="selectedIndex < mediaList.length - 1" @click="nextMedia" style="background:#22223b;">
                                                        <v-icon>mdi-chevron-right</v-icon>
                                                    </v-btn>
                                                </div>
                                </div>
                    </v-card>
                </v-dialog>
        </v-container>
</template>

<script>
import axios from 'axios';
export default {
                data() {
                    return {
                        mediaList: [],
                        dialog: false,
                        selectedMedia: null,
                        selectedIndex: -1,
                        page: 1,
                        perPage: 16,
                        isMobile: false
                    };
                },
            mounted() {
                axios.get('/media-list').then(res => {
                    this.mediaList = res.data;
                });
                this.checkMobile();
            },
            computed: {
                pageCount() {
                    return Math.ceil(this.mediaList.length / this.perPage);
                },
                pagedRows() {
                    const start = (this.page - 1) * this.perPage;
                    const pageItems = this.mediaList.slice(start, start + this.perPage);
                    const rows = [];
                    const itemsPerRow = this.isMobile ? 2 : 4;
                    for (let i = 0; i < pageItems.length; i += itemsPerRow) {
                        rows.push(pageItems.slice(i, i + itemsPerRow));
                    }
                    return rows;
                },
            },
            watch: {
                page() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },
            },
            methods: {
                formatDate(ts) {
                    const d = new Date(ts * 1000);
                    return d.toLocaleDateString() + ' ' + d.toLocaleTimeString();
                },
                openDialog(media) {
                    if (!media.url) return;
                    this.selectedIndex = this.mediaList.findIndex(m => m.name === media.name);
                    this.selectedMedia = media;
                    this.dialog = true;
                },
                prevMedia() {
                    if (this.selectedIndex > 0) {
                        this.selectedIndex--;
                        this.selectedMedia = this.mediaList[this.selectedIndex];
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },
                nextMedia() {
                    if (this.selectedIndex < this.mediaList.length - 1) {
                        this.selectedIndex++;
                        this.selectedMedia = this.mediaList[this.selectedIndex];
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },
                checkMobile() {
                    this.isMobile = window.innerWidth <= 600;
                    if (this.isMobile) {
                        this.perPage = 8;
                    } else {
                        this.perPage = 16;
                    }
                },
                async handleFileUpload(e) {
                    const files = Array.from(e.target.files);
                    if (!files.length) return;
                    const formData = new FormData();
                    files.forEach(f => formData.append('files[]', f));
                    try {
                        await axios.post('/upload-media', formData, {
                            headers: { 'Content-Type': 'multipart/form-data' }
                        });
                        // Recarga la galería tras subir
                        const res = await axios.get('/media-list');
                        this.mediaList = res.data;
                    } catch (err) {
                        alert('Error al subir archivo(s)');
                    }
                },
            }
};
</script>

<style scoped>

.media-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(56,178,172,0.08);
    overflow: hidden;
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: box-shadow 0.2s;
}
.media-card:hover {
    box-shadow: 0 6px 24px rgba(56,178,172,0.18);
}
.media-img, .media-video {
    width: 100%;
    height: 260px;
    object-fit: cover;
    display: block;
}
.media-date {
    font-size: 13px;
    color: #718096;
    background: rgba(255,255,255,0.85);
    padding: 4px 10px;
    border-radius: 0 0 0 10px;
    position: absolute;
    right: 0;
    bottom: 0;
}
.confirm-btn-bg {
    background-image: url('/images/btn-bg-8B5CF6.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    color: #fff !important;
    border: none;
    box-shadow: 0 2px 8px rgba(56,178,172,0.10);
}
</style>