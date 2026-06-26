<template>
    <div v-if="pagination.last_page > 1" class="pagination-wrap">
        <p class="pagination-info">
            Showing <strong>{{ pagination.from }}</strong> – <strong>{{ pagination.to }}</strong> of <strong>{{ pagination.total }}</strong>
        </p>
        <div class="pagination-btns">
            <button @click="$emit('change', pagination.current_page - 1)"
                :disabled="pagination.current_page <= 1" class="pg-btn">
                <i class="fas fa-chevron-left"></i>
            </button>

            <template v-for="(page, index) in visiblePages">
                <button v-if="page !== '...'" :key="'p-' + page" @click="$emit('change', page)"
                    class="pg-btn" :class="{ 'pg-active': page === pagination.current_page }">
                    {{ page }}
                </button>
                <span v-else :key="'e-' + index" class="pg-ellipsis">…</span>
            </template>

            <button @click="$emit('change', pagination.current_page + 1)"
                :disabled="pagination.current_page >= pagination.last_page" class="pg-btn">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({ pagination: { type: Object, required: true } });
defineEmits(['change']);

const visiblePages = computed(() => {
    const { current_page, last_page } = props.pagination;
    const pages = [];
    if (last_page <= 7) {
        for (let i = 1; i <= last_page; i++) pages.push(i);
    } else {
        pages.push(1);
        if (current_page > 3) pages.push('...');
        for (let i = Math.max(2, current_page - 1); i <= Math.min(last_page - 1, current_page + 1); i++) pages.push(i);
        if (current_page < last_page - 2) pages.push('...');
        pages.push(last_page);
    }
    return pages;
});
</script>

<style scoped>
.pagination-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1.5rem;
    flex-wrap: wrap;
    gap: 0.75rem;
}

.pagination-info {
    font-size: 0.82rem;
    color: var(--text-muted, #4a5068);
}

.pagination-info strong { color: var(--text-secondary, #94a3b8); }

.pagination-btns { display: flex; align-items: center; gap: 0.3rem; }

.pg-btn {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    border: 1px solid var(--border, #2a2f45);
    background: var(--bg-card, #13151e);
    color: var(--text-secondary, #94a3b8);
    font-size: 0.82rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.18s;
}

.pg-btn:hover:not(:disabled) {
    border-color: var(--accent, #c8773a);
    color: var(--accent, #c8773a);
    background: var(--accent-hover, rgba(200,119,58,0.08));
}

.pg-btn:disabled { opacity: 0.35; cursor: not-allowed; }

.pg-active {
    background: var(--accent, #c8773a) !important;
    border-color: var(--accent, #c8773a) !important;
    color: white !important;
    font-weight: 600;
}

.pg-ellipsis { font-size: 0.85rem; color: var(--text-muted, #4a5068); padding: 0 0.2rem; }
</style>
