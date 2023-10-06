import './assets/css/common.css';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store';
import Paginate from 'vuejs-paginate-next';

library.add(fas);

const app = createApp(App);

app.component('font-awesome-icon', FontAwesomeIcon);

app.use(store);

app.use(router)

app.use(Paginate);

app.mount('#app');
