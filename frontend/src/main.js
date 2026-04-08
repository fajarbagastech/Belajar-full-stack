import { createApp } from "vue";
import "./style.css";
import App from "./App.vue";
import router from "./router"; // <--- Baris ini untuk memanggil file router/index.js
import VueApexCharts from "vue3-apexcharts"; // Tambahkan ini

const app = createApp(App);
app.use(VueApexCharts); // Tambahkan ini

app.use(router); // <--- Memberitahu Vue untuk menggunakan sistem navigasi kita
app.mount("#app");
