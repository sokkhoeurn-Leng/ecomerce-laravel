<template>
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="show" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="close"></div>
                
                <!-- Modal -->
                <div 
                    class="relative bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl w-full max-h-[90vh] overflow-y-auto"
                    :class="sizeClass"
                >
                    <!-- Header -->
                    <div class="sticky top-0 bg-gray-900 border-b border-gray-800 px-6 py-4 flex items-center justify-between z-10">
                        <h3 class="text-lg font-semibold">{{ title }}</h3>
                        <button 
                            @click="close" 
                            class="text-gray-500 hover:text-white transition-colors p-1"
                        >
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>
                    
                    <!-- Body -->
                    <div class="p-6">
                        <slot></slot>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
const props = defineProps({
    show: Boolean,
    title: String,
    size: {
        type: String,
        default: 'md'
    }
});

const emit = defineEmits(['close']);

const sizeClass = {
    sm: 'max-w-md',
    md: 'max-w-lg',
    lg: 'max-w-2xl',
    xl: 'max-w-4xl',
    '2xl': 'max-w-6xl',
}[props.size] || 'max-w-lg';

const close = () => emit('close');
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: all 0.3s ease;
}
.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
.modal-enter-from .relative,
.modal-leave-to .relative {
    transform: scale(0.95) translateY(10px);
}
</style>