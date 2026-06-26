<template>
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="show" class="modal-backdrop" @click.self="close">
                <div class="modal-box" :class="sizeClass">
                    <div class="modal-header">
                        <h3 class="modal-title">{{ title }}</h3>
                        <button @click="close" class="modal-close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <slot></slot>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
const props = defineProps({
    show:  Boolean,
    title: String,
    size:  { type: String, default: 'md' },
});

const emit = defineEmits(['close']);

const sizeClass = {
    sm:  'modal-sm',
    md:  'modal-md',
    lg:  'modal-lg',
    xl:  'modal-xl',
}[props.size] || 'modal-md';

const close = () => emit('close');
</script>

<style scoped>
.modal-backdrop {
    position: fixed;
    inset: 0;
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(4px);
}

.modal-box {
    position: relative;
    background: var(--bg-card, #13151e);
    border: 1px solid var(--border, #1f2330);
    border-radius: 18px;
    box-shadow: 0 24px 60px rgba(0,0,0,0.4);
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    transition: background 0.3s, border-color 0.3s;
}

.modal-sm  { max-width: 420px; }
.modal-md  { max-width: 540px; }
.modal-lg  { max-width: 720px; }
.modal-xl  { max-width: 960px; }

.modal-header {
    position: sticky;
    top: 0;
    background: var(--bg-card, #13151e);
    border-bottom: 1px solid var(--border, #1f2330);
    padding: 1.1rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 10;
    transition: background 0.3s, border-color 0.3s;
}

.modal-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary, #e2e8f0);
}

.modal-close {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    border: 1px solid var(--border, #1f2330);
    background: var(--bg-hover, #1a1e2e);
    color: var(--text-muted, #4a5068);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    transition: all 0.18s;
}

.modal-close:hover {
    background: rgba(239,68,68,0.1);
    border-color: rgba(239,68,68,0.3);
    color: #f87171;
}

.modal-body { padding: 1.5rem; }

/* Transition */
.modal-enter-active, .modal-leave-active { transition: all 0.25s ease; }
.modal-enter-from, .modal-leave-to { opacity: 0; }
.modal-enter-from .modal-box, .modal-leave-to .modal-box { transform: scale(0.95) translateY(12px); }
</style>
