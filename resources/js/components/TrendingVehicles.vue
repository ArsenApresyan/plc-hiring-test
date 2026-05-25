<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

// TODO: implement
//   - fetch GET /api/vehicles/trending on mount
//   - auto-refresh every 30s
//   - render the list (make, model, year, view count)
//   - loading + error states
//   - clean up the interval on unmount

const vehicles = ref([]);
const loading = ref(false);
const error = ref(null);

async function load() {
    // TODO
}

onMounted(() => {
    load();
    // TODO: set up polling
});

onUnmounted(() => {
    // TODO: clear polling
});
</script>

<template>
    <section class="trending-vehicles">
        <h2>Trending vehicles</h2>
        <!-- TODO: loading state, error state, list of vehicles -->
        <p v-if="!vehicles.length && !loading && !error">No data yet.</p>
    </section>
</template>
