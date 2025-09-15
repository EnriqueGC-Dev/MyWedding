<template>
    <v-container class="d-flex justify-center align-center" style="min-height: 80vh; min-width: 100%;">
        <v-card class="pa-6" max-width="700" elevation="10" style="width:100%;">
            <v-card-title class="text-h5 font-weight-bold mb-2 text-center">Confirmación de Asistencia</v-card-title>
            <div class="text-h7 font-weight-bold mb-2 text-center">Podéis utilizar este formulario para confirmar vuestra asistencia a la boda.</div>
            <div class="text-h7 font-weight-bold mb-2 text-center">Si teneis acompañantes o familia, indicad los nombres de todos los que asistirán.</div>
            <v-form @submit.prevent="submitForm" ref="form" v-model="formValid">
                <v-text-field
                    v-model="nombre"
                    label="Nombre Invitado"
                    outlined
                    dense
                    :rules="[v => !!v || 'El nombre es obligatorio']"
                    required
                    class="mb-3"
                />
                <v-text-field
                    v-model="bebidaCena"
                    label="Bebida Cena"
                    outlined
                    dense
                    class="mb-3"
                />
                <v-text-field
                    v-model="bebidaFiesta"
                    label="Bebida Fiesta"
                    outlined
                    dense
                    class="mb-3"
                />
                <v-text-field
                    v-model="telefono"
                    label="Teléfono"
                    outlined
                    dense
                    :rules="[v => !!v || 'El teléfono es obligatorio', v => /^\d{9,15}$/.test(v) || 'Introduce un teléfono válido']"
                    required
                    class="mb-3"
                    type="tel"
                />
                <div class="mb-3">
                    <div class="d-flex align-center justify-space-between mb-1">
                        <span class="font-weight-medium">Invitados adicionales</span>
                        <v-btn icon small color="primary" @click="addInvitado" :disabled="invitados.length >= 5">
                            <v-icon>mdi-plus</v-icon>
                        </v-btn>
                    </div>
                    <div v-for="(invitado, idx) in invitados" :key="idx" class="d-flex align-center">
                        <v-text-field
                            v-model="invitados[idx]"
                            :label="`Invitado ${idx + 1}`"
                            outlined
                            dense
                            class="flex-grow-1 mt-4"
                            required
                        />
                        <v-text-field
                            v-model="bebidasCenaAcompanantes[idx]"
                            :label="`Bebida Cena`"
                            outlined
                            dense
                            class="ml-2 mt-4"
                        />
                        <v-text-field
                            v-model="bebidasFiestaAcompanantes[idx]"
                            :label="`Bebida Fiesta`"
                            outlined
                            dense
                            class="ml-2 mt-4"
                        />
                        <v-switch
                            v-model="niños[idx]"
                            :label="`Niño/a`"
                            outlined
                            dense
                            class="ml-4 mt-4"
                        />
                        <v-btn icon small color="red" @click="removeInvitado(idx)" class="ml-2">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </div>
                </div>
                <v-textarea
                    v-model="restricciones"
                    label="Restricciones alimenticias"
                    outlined
                    dense
                    rows="2"
                    class="mb-4"
                />
                <v-row>
                    <v-spacer></v-spacer>
                <v-btn
                    large
                    type="submit"
                    class="mt-2 text-white confirm-btn-bg"
                    :disabled="!isFormReallyValid"
                >
                    Confirmar
                </v-btn>
                </v-row>
            </v-form>
        </v-card>
    </v-container>

</template>
<script>
export default {
    data() {
        return {
            nombre: '',
            telefono: '',
            bebidaCena: '',
            bebidaFiesta: '',
            invitados: [],
            niños: [],
            bebidasCenaAcompanantes: [],
            bebidasFiestaAcompanantes: [],
            restricciones: '',
            formValid: false,
        };
    },
    computed: {
        isFormReallyValid() {
            if (!this.formValid) return false;
            if (!this.nombre || !this.telefono) return false;
            if (this.invitados.some(i => !i)) return false;
            return true;
        }
    },
    methods: {
        addInvitado() {
            if (this.invitados.length < 5) {
                this.invitados.push('');
                this.niños.push(false);
                this.bebidasCenaAcompanantes.push('');
                this.bebidasFiestaAcompanantes.push('');
            }
        },
        removeInvitado(idx) {
            this.invitados.splice(idx, 1);
            this.niños.splice(idx, 1);
            this.bebidasCenaAcompanantes.splice(idx, 1);
            this.bebidasFiestaAcompanantes.splice(idx, 1);
        },
        submitForm() {
            // Obtén el token CSRF desde la meta etiqueta
            const token = document.head.querySelector('meta[name="csrf-token"]').content;
            const data = {
                nombre: this.nombre,
                telefono: this.telefono,
                bebida_cena: this.bebidaCena,
                bebida_fiesta: this.bebidaFiesta,
                invitados: this.invitados,
                ninos: this.niños,
                bebidas_cena_acompanantes: this.bebidasCenaAcompanantes,
                bebidas_fiesta_acompanantes: this.bebidasFiestaAcompanantes,
                restricciones: this.restricciones
            };
            fetch('/invitados', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify(data)
            })
            .then(async response => {
                if (!response.ok) {
                    const error = await response.json();
                    alert('Error al enviar: ' + (error.message || response.status));
                    return;
                }
                alert('¡Confirmación enviada!');
                // Opcional: limpiar el formulario
                this.nombre = '';
                this.telefono = '';
                this.invitados = [];
                this.niños = [];
                this.bebidaCena = '';
                this.bebidaFiesta = '';
                this.bebidasCenaAcompanantes = [];
                this.bebidasFiestaAcompanantes = [];
                this.restricciones = '';
            })
            .catch(() => {
                alert('Error de red o servidor.');
            });
        },
    },
};
</script>

<style scoped>
.v-card {
    border-radius: 18px;
    background: rgba(255,255,255,0.95);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
}

.confirm-btn-bg {
    background-image: url('/images/btn-bg.png');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    color: #fff !important;
    border: none;
    box-shadow: 0 2px 8px rgba(56,178,172,0.10);
}
</style>

