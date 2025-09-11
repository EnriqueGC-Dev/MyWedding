<template>
	<v-dialog v-model="dialog" max-width="400">
		<v-card>
            <v-card-text>
                <v-row>
                    <v-col cols="6">
                        <v-btn flat block @click="this.viewPage='login'" :color = "viewPage=='login' ? 'purple-darken-4' : 'white-darken-4' ">INICIAR SESION</v-btn>
                    </v-col>
                    <v-col cols="6">
                        <v-btn flat block @click="this.viewPage='signup'" :color = "viewPage=='signup' ? 'purple-darken-4' : 'white-darken-4' ">REGISTRARSE</v-btn>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            v-if="viewPage=='signup'"
                            v-model="name"
                            label="Nombre"
                            type="name"
                            outlined
                            dense
                            class="w-100"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                        <v-text-field
                            v-model="email"
                            label="Email"
                            type="email"
                            outlined
                            dense
                            class="w-100"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                        <v-text-field
                            v-model="password"
                            label="Contraseña"
                            type="password"
                            outlined
                            dense
                            class="w-100"
                        ></v-text-field>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn v-if="viewPage=='login'" color="primary" text @click="login()">Iniciar Sesión</v-btn>
                <v-btn v-if="viewPage=='signup'" color="primary" text @click="signup()">Registrarse</v-btn>
                <v-btn color="primary" text @click="$emit('cancel')">Cerrar</v-btn>
            </v-card-actions>
		</v-card>
	</v-dialog>
</template>

<script>
import axios from 'axios';

export default {

    data(){
        return{
            viewPage: 'login',
            email: 'a@a.es',
            password: '123456',
            name: '',
        }
    },

    methods: {
        login() {
            let Self = this;
        
            let credenciales = {
                email: this.email,
                password: this.password,
            };

            axios
                .post("/login", credenciales)
                .then((response) => {
                    if(response.data.status == 'OK'){

                        this.usuario = response.data[0];

                        Self.$store.dispatch("setLogin", () => {
                                Self.$emit('cancel');
                        });
                    } else {
                        if(response.data.status == 'ERROR') {
                        alert('Usuario o contraseña incorrectos');
                        }
                    }
                })
                .catch((error) => {
                    alert('error al iniciar sesión. Si el problema persiste, contacta al administrador.');
                });
        },

        signup() {
        
            let credenciales = {
                name: this.name,
                email: this.email,
                password: this.password,
            };

            axios
                .post("/signup", credenciales)
                .then((response) => {
                    if(response.data.status == 'OK'){

                        this.usuario = response.data[0];

                        this.login();
                    } else {
                        if(response.data.status == 'ERROR') {
                        alert('Usuario o contraseña incorrectos');
                        }
                    }
                })
                .catch((error) => {
                    alert('error al iniciar sesión. Si el problema persiste, contacta al administrador.');
                });
        }
    }
}
</script>