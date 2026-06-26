<template>
    <span 
        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium capitalize"
        :class="colorClass"
    >
        <span class="w-1.5 h-1.5 rounded-full" :class="dotClass"></span>
        {{ status }}
    </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: String,
    type: {
        type: String,
        default: 'order' // order, payment
    }
});

const colorMap = {
    order: {
        pending: { bg: 'bg-yellow-500/10', text: 'text-yellow-500', dot: 'bg-yellow-500' },
        processing: { bg: 'bg-blue-500/10', text: 'text-blue-500', dot: 'bg-blue-500' },
        shipped: { bg: 'bg-purple-500/10', text: 'text-purple-500', dot: 'bg-purple-500' },
        delivered: { bg: 'bg-green-500/10', text: 'text-green-500', dot: 'bg-green-500' },
        cancelled: { bg: 'bg-red-500/10', text: 'text-red-500', dot: 'bg-red-500' },
    },
    payment: {
        paid: { bg: 'bg-green-500/10', text: 'text-green-500', dot: 'bg-green-500' },
        pending: { bg: 'bg-yellow-500/10', text: 'text-yellow-500', dot: 'bg-yellow-500' },
        failed: { bg: 'bg-red-500/10', text: 'text-red-500', dot: 'bg-red-500' },
        refunded: { bg: 'bg-gray-500/10', text: 'text-gray-500', dot: 'bg-gray-500' },
    }
};

const colors = computed(() => {
    return colorMap[props.type]?.[props.status] || { bg: 'bg-gray-500/10', text: 'text-gray-500', dot: 'bg-gray-500' };
});

const colorClass = computed(() => `${colors.value.bg} ${colors.value.text}`);
const dotClass = computed(() => colors.value.dot);
</script>