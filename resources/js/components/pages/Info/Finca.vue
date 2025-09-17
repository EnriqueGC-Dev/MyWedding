<template>
	<v-container>
		<h2 class="text-center mb-4">La Finca</h2>
        <v-div v-if="isMobile">
            <div class="mt-4">
                📌 C. de José María Usandizaga, 46, <p>28439 Alpedrete, Madrid</p>
            </div>
            <div class="mt-4">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3027.8829081644726!2d-4.027901424161336!3d40.632464171405715!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd41754389b1dcbd%3A0xe4e757c3ffda9420!2sLa%20Casona%20Imperial!5e0!3m2!1ses!2ses!4v1758101355769!5m2!1ses!2ses" width="100%" scale style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </v-div>
        <v-div v-else>
            <v-row>
                <v-col cols="6" class="text-center">
                    <div class="mt-4">
                        📌 C. de José María Usandizaga, 46, <p>28439 Alpedrete, Madrid</p>
                    </div>
                    <div class="mt-4">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3027.8829081644726!2d-4.027901424161336!3d40.632464171405715!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd41754389b1dcbd%3A0xe4e757c3ffda9420!2sLa%20Casona%20Imperial!5e0!3m2!1ses!2ses!4v1758101355769!5m2!1ses!2ses" width="100%" scale style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </v-col>
                <v-col cols="6">
                    <v-img src="/images/finca/casona.jpg"  class="rounded-lg mb-6" alt="Mapa de la finca" style="width: 100%;" />
                </v-col>    
            </v-row>
        </v-div>
        
		
        <h3 class="text-center mt-10 mb-4">Galería de fotos</h3>
		<v-carousel
			hide-delimiter-background
			:height=" [isMobile ? '200' : '400']"
			cycle
			show-arrows-on-hover
			class="finca-carousel"
			style="max-width:500px;margin:0 auto;"
		>
			<v-carousel-item v-for="(img, i) in images" :key="i">
				<v-img :src="img.src" :alt="img.alt" class="rounded-lg" style="height:100%; object-fit:cover;" />
			</v-carousel-item>
		</v-carousel>
	</v-container>
</template>

<script>
export default {
	name: 'FincaInfo',
	data() {
		return {
            isMobile: false,
			images: [
				{ src: '/images/finca/piscina.jpg', alt: 'Piscina' },
				{ src: '/images/finca/padel.jpg', alt: 'Pádel' },
				{ src: '/images/finca/futbolin.jpg', alt: 'Futbolín' },
				{ src: '/images/finca/billar.jpg', alt: 'Billar' },
			]
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
};
</script>

<style scoped>
.rounded-lg {
	border-radius: 18px;
	box-shadow: 0 2px 12px rgba(56,178,172,0.10);
}
</style>
