<template>
    <v-container class="d-flex justify-center align-center" style="min-height: 80vh; min-width: 100%;">
        <v-card class="pa-6" max-width="900" elevation="10" style="width:100%;">
            <div v-if="isMobile">
                <v-select
                    v-model="view"
                    :items="['General', 'Finca', 'Autobús']"
                    label="Selecciona una sección"
                    outlined
                    dense
                    class="mb-4"
                    style="max-width: 300px;"
                ></v-select>
            </div>
            <div v-else>
                <v-row>
                    <v-col cols="4">
                        <v-btn
                        block
                        text
                        :class="['mt-2', view=='General' ? 'active-btn-bg' : 'unactive-btn-bg']"
                        @click="view = 'General'"
                        >
                        General
                        </v-btn>
                    </v-col>
                    <v-col cols="4">
                        <v-btn
                        block
                        text
                        :class="['mt-2', view=='Finca' ? 'active-btn-bg' : 'unactive-btn-bg']"
                        @click="view = 'Finca'"
                        >
                        Finca
                        </v-btn>
                    </v-col>
                    <v-col cols="4">
                        <v-btn
                        block
                        text
                        :class="['mt-2', view=='Autobús' ? 'active-btn-bg' : 'unactive-btn-bg']"
                        @click="view = 'Autobús'"
                        >
                        Autobús
                        </v-btn>
                    </v-col>
                </v-row>
            </div>
            <div class="mt-6">
                <InfoGeneral v-if="view==='General'" />
                <InfoFinca v-if="view==='Finca'" /> 
                <InfoAutobus v-if="view==='Autobús'" />
            </div>
        </v-card>
    </v-container>
</template>

<script>
import InfoGeneral from './Info/General.vue';
import InfoFinca from './Info/Finca.vue';
import InfoAutobus from './Info/Autobus.vue';

export default {
    components: { InfoGeneral, InfoFinca, InfoAutobus },

    data() {
        return {
            isMobile: false,
            view: 'General'
        }
    },
    mounted() {
        this.checkMobile();
        window.addEventListener('resize', this.checkMobile);
    },
    beforeDestroy() {
        window.removeEventListener('resize', this.checkMobile);
    },
    methods: {
        checkMobile() {
            this.isMobile = window.innerWidth <= 600;
        },
    }
}

</script>

<style scoped>
    .active-btn-bg {
        background-image: url('/images/btn-bg-8B5CF6.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        color: #fff !important;
        border: none;
        box-shadow: 0 2px 8px rgba(56,178,172,0.10);
    }
    .unactive-btn-bg {
        background-image: url('/images/btn-bg.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        color: #fff !important;
        border: none;
        box-shadow: 0 2px 8px rgba(56,178,172,0.10);
    }
</style>