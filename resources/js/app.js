import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
import {createApp} from 'vue';
import TaskList from './components/TaskList.vue';
// import vue from '@vitejs/plugin-vue';
// import Vue from '@vitejs/plugin-vue';
// window.Vue = vue;

// Vue.component('task-list', require('./components/TaskList.vue'));


const app = createApp({
  
});

app.component('task-list', TaskList);

app.mount('#app');