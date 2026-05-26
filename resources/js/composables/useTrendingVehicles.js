import { onMounted, onUnmounted, ref } from 'vue';
import axios from 'axios';

const TRENDING_URL = '/api/vehicles/trending';
const REFRESH_INTERVAL_MS = 30_000;

export function useTrendingVehicles() {
    const vehicles = ref([]);
    const loading = ref(false);
    const error = ref(null);

    let refreshTimer = null;

    async function load({ showLoading = false } = {}) {
        if (showLoading) {
            loading.value = true;
        }
        error.value = null;

        try {
            const { data } = await axios.get(TRENDING_URL);
            vehicles.value = Array.isArray(data) ? data : [];
        } catch (err) {
            const message = err?.response?.data?.message
                ?? err?.message
                ?? 'Failed to load trending vehicles.';
            error.value = message;
        } finally {
            if (showLoading) {
                loading.value = false;
            }
        }
    }

    onMounted(() => {
        load({ showLoading: true });
        refreshTimer = setInterval(() => load(), REFRESH_INTERVAL_MS);
    });

    onUnmounted(() => {
        if (refreshTimer !== null) {
            clearInterval(refreshTimer);
            refreshTimer = null;
        }
    });

    return { vehicles, loading, error, load };
}
