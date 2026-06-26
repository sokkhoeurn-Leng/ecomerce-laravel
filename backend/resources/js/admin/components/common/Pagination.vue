<template>
    <div v-if="pagination.last_page > 1" class="flex items-center justify-between mt-6">
        <p class="text-sm text-gray-500">
            Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} results
        </p>
        <div class="flex items-center gap-1">
            <button 
                @click="$emit('change', pagination.current_page - 1)"
                :disabled="pagination.current_page <= 1"
                class="px-3 py-2 rounded-lg text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                :class="pagination.current_page <= 1 ? 'text-gray-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'"
            >
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <template v-for="(page, index) in visiblePages" :key="`${page}-${index}`">
                <button 
                    v-if="page !== '...'"
                    @click="$emit('change', page)"
                    class="px-3 py-2 rounded-lg text-sm transition-colors"
                    :class="page === pagination.current_page ? 'bg-amber-500 text-gray-900 font-medium' : 'text-gray-400 hover:bg-gray-800 hover:text-white'"
                >
                    {{ page }}
                </button>
                <span v-else class="px-2 text-gray-600">...</span>
            </template>
            
            <button 
                @click="$emit('change', pagination.current_page + 1)"
                :disabled="pagination.current_page >= pagination.last_page"
                class="px-3 py-2 rounded-lg text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                :class="pagination.current_page >= pagination.last_page ? 'text-gray-600' : 'text-gray-400 hover:bg-gray-800 hover:text-white'"
            >
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    pagination: {
        type: Object,
        required: true
    }
});

defineEmits(['change']);

const visiblePages = computed(() => {
    const { current_page, last_page } = props.pagination;
    const pages = [];
    
    if (last_page <= 7) {
        for (let i = 1; i <= last_page; i++) pages.push(i);
    } else {
        pages.push(1);
        if (current_page > 3) pages.push('...');
        
        const start = Math.max(2, current_page - 1);
        const end = Math.min(last_page - 1, current_page + 1);
        
        for (let i = start; i <= end; i++) pages.push(i);
        
        if (current_page < last_page - 2) pages.push('...');
        pages.push(last_page);
    }
    
    return pages;
});
</script>
