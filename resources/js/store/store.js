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
            user_active: null,
            user_role: null,
            user_firstlogin: null,
            user_photo: null,
            user_phone: null,
            user_birthday: null,
            user_companyadmin: null,
        }
    },
    mutations: {
        LOGIN: async function (state, callback) {
            let response = await axios.get("/api/user/data");   

            if (response.data.status == 'OK') {
                console.log(response.data);
                state.authentication = true;
                state.user_id = response.data.user.id;
                state.user_name = response.data.user.name;
                state.user_mail = response.data.user.email;
                state.user_active = response.data.user.user_active;
                state.user_role = response.data.user.user_role_id;
                state.user_firstlogin = response.data.user.user_first_log;
                state.user_photo = response.data.user.user_photo;
                state.user_phone = response.data.user.user_phone;
                state.user_birthday = response.data.user.user_birthday;
                state.user_companyadmin = response.data.user.user_company_admin;
                if (typeof callback == 'function') {
                    callback(true);
                }
            }
            else {
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

        SET_USER_FIRSTLOGIN(state, value) {
            state.user_firstlogin = value;
        }

    },
    actions: {
        setLogin: function (context, callback) {
            context.commit('LOGIN', callback);
        },
        setLogout: function (context) {
            axios.post("/api/user/logout")
                .then(response => {
                    context.commit('LOGOUT');
                });
        },
        user_firstlogin({ commit }, value) {
            commit('SET_USER_FIRSTLOGIN', value);
        }

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