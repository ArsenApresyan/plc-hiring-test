<script setup>
import { useTrendingVehicles } from '../composables/useTrendingVehicles';

const { vehicles, loading, error } = useTrendingVehicles();
</script>

<template>
    <section class="trending-vehicles">
        <h2>Trending vehicles</h2>
        <p class="trending-vehicles__hint">Top 10 in the last 24 hours · refreshes every 30s</p>

        <p v-if="loading" class="trending-vehicles__status" role="status">Loading…</p>

        <p v-else-if="error" class="trending-vehicles__status trending-vehicles__status--error" role="alert">
            {{ error }}
        </p>

        <ul v-else-if="vehicles.length" class="trending-vehicles__list">
            <li v-for="vehicle in vehicles" :key="vehicle.id" class="trending-vehicles__item">
                <span class="trending-vehicles__title">
                    {{ vehicle.year }} {{ vehicle.make }} {{ vehicle.model }}
                </span>
                <span class="trending-vehicles__views">{{ vehicle.view_count }} views</span>
            </li>
        </ul>

        <p v-else class="trending-vehicles__status">No trending vehicles yet.</p>
    </section>
</template>

<style scoped>
.trending-vehicles {
    margin-top: 1.5rem;
}

.trending-vehicles__hint {
    margin: 0.25rem 0 1rem;
    color: #64748b;
    font-size: 0.875rem;
}

.trending-vehicles__status {
    margin: 0;
    color: #475569;
}

.trending-vehicles__status--error {
    color: #b91c1c;
}

.trending-vehicles__list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.trending-vehicles__item {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    gap: 1rem;
    padding: 0.75rem 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    background: #f8fafc;
}

.trending-vehicles__title {
    font-weight: 600;
}

.trending-vehicles__views {
    color: #64748b;
    font-size: 0.875rem;
    white-space: nowrap;
}
</style>
