<template>
    <v-container class="d-flex justify-center align-center" style="min-height: 80vh; min-width: 100%;">
        <v-card class="pa-6" max-width="500" elevation="10" style="width:100%;">
            <v-card-title class="text-h5 font-weight-bold mb-2 text-center">Confirmación de Asistencia</v-card-title>
            <v-form @submit.prevent="submitForm" ref="form">
                <v-text-field
                    v-model="nombre"
                    label="Nombre Invitado"
                    outlined
                    dense
                    required
                    class="mb-3"
                />
                <v-text-field
                    v-model="telefono"
                    label="Teléfono"
                    outlined
                    dense
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
                <v-btn color="deep-purple-accent-4" block large type="submit" class="mt-2 text-white">Confirmar</v-btn>
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
            invitados: [],
            restricciones: '',
        };
    },
    methods: {
        addInvitado() {
            if (this.invitados.length < 5) this.invitados.push('');
        },
        removeInvitado(idx) {
            this.invitados.splice(idx, 1);
        },
        submitForm() {
            // Aquí puedes manejar el envío del formulario
            // Por ejemplo, emitir un evento o hacer una petición AJAX
            alert('Formulario enviado');
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
</style>
