import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
import {createApp} from 'vue';
import TaskList from './components/TaskList.vue';
import ApplyFile from './components/ApplyFile.vue';
import Invoice from "./components/Invoice.vue";
import SignContract from "./components/SignContract.vue";
import Selector from "./components/Selector.vue";
import 'izitoast/dist/js/iziToast.min.js';
// import vue from '@vitejs/plugin-vue';
// window.Vue = vue;

// Vue.component('task-list', require('./components/TaskList.vue'));


const app = createApp({

});

app.component('task-list', TaskList);
app.component('apply-file', ApplyFile);
app.component('invoice', Invoice);
app.component('sign-contract', SignContract);
app.component('selector', Selector);

app.mount('#app');
