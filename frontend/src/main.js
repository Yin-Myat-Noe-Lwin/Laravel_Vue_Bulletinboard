import './assets/main.css'
import './assets/css/common.css';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { fas } from '@fortawesome/free-solid-svg-icons';
import mitt from 'mitt';

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store';

library.add(fas);

const app = createApp(App);
app.component('font-awesome-icon', FontAwesomeIcon);

app.use(store);
app.use(router)

app.mount('#app')

const eventBus = mitt();

app.provide('eventBus',eventBus);

