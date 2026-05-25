import './bootstrap';

import { createApp } from 'vue';
import TrendingVehicles from './components/TrendingVehicles.vue';

const app = createApp({});
app.component('trending-vehicles', TrendingVehicles);
app.mount('#app');
