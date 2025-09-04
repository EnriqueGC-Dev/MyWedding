import { createApp } from 'vue'
import { createStore } from 'vuex'
import axios from 'axios';
import App from '../components/App.vue';

let store = createStore({
    state () {
        return {
            authentication: false,
            user_id: null,
            user_name: null,
            user_mail: null,
        }
    },
    mutations: {
        LOGIN: async function (state, callback) {
            let response = await axios.get("/data");   

            if (response.data.status == 'OK') {
                console.log("User is logged in");
                console.log(response.data);
                state.authentication = true;
                state.user_id = response.data.id;
                state.user_name = response.data.name;
                state.user_mail = response.data.email;
                if (typeof callback == 'function') {
                    callback(true);
                }
            }
            else {
                state.authentication = false; 
                state.user_id = null;
                state.user_name = null;
                state.user_mail = null;
                
                if (typeof callback == 'function') {
                    callback(false);
                }
            }
        },

        LOGOUT: async function (state, callback) {

                state.authentication = false; 
                state.user_id = null;
                state.user_name = null;
                state.user_mail = null;
                state.user_active = null;
                state.user_role = null;
                state.user_firstlogin = null;
                state.user_photo = null;
                state.user_phone = null;
                state.user_birthday = null;
                state.user_companyadmin = null;
                
                if (typeof callback == 'function') {
                    callback(false);
                }

        },

    },
    actions: {
        setLogin: function (context, callback) {
            context.commit('LOGIN', callback);
        },
        setLogout: function (context) {
            axios.post("/logout")
                .then(response => {
                    context.commit('LOGOUT');
                });
        },
    },
    getters: {
        isUserLogged(state) {
            return state.authentication;
        },
    }
})

const app = createApp(App)
app.use(store)

export default store