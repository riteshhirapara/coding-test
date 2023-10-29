import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia'

import VueDatePicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import CKEditor from "@ckeditor/ckeditor5-vue";

import Alpine from 'alpinejs';
import TaskCard from './components/TaskCard.vue';
import TaskColumn from './components/TaskColumn.vue';
import KanbanBoard from './components/KanbanBoard.vue';
import GenericModal from './components/modals/GenericModal.vue';
import TaskStatisticsBoard from "./components/TaskStatisticsBoard.vue";

const pinia = createPinia()
const app = createApp({});
app.use(pinia);
app.use(CKEditor);

app.component("VueDatePicker", VueDatePicker);
app.component('TaskCard', TaskCard);
app.component('TaskColumn', TaskColumn);
app.component('KanbanBoard', KanbanBoard);
app.component('GenericModal', GenericModal);
app.component("TaskStatisticsBoard", TaskStatisticsBoard);

app.mount("#app");


window.Alpine = Alpine;

Alpine.start();
