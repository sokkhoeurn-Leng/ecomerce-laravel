<template>
    <div v-if="user" class="space-y-6">
        <a :href="route('admin.users.index')" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white transition-colors">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>

        <!-- User Header -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                <div class="w-20 h-20 bg-amber-500/10 rounded-full flex items-center justify-center">
                    <span class="text-2xl font-bold text-amber-500">{{ getInitials(user.name) }}</span>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold">{{ user.name }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ user.email }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium"
                            :class="user.is_admin ? 'bg-amber-500/10 text-amber-500' : 'bg-gray-500/10 text-gray-400'">
                            <i :class="user.is_admin ? 'fas fa-shield-alt' : 'fas fa-user'" class="text-[10px]"></i>
                            {{ user.is_admin ? 'Admin' : 'User' }}
                        </span>
                        <span class="text-xs text-gray-500">Joined {{ formatDate(user.created_at) }}</span>
                    </div>
                </div>
                <button @click="toggleAdmin" class="px-4 py-2 text-sm border border-gray-700 rounded-lg hover:bg-gray-800 transition-colors">
                    {{ user.is_admin ? 'Remove Admin' : 'Make Admin' }}
                </button>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                <p class="text-sm text-gray-500">Total Orders</p>
                <p class="text-2xl font-bold mt-1">{{ user.orders?.length || 0 }}</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                <p class="text-sm text-gray-500">Total Spent</p>
                <p class="text-2xl font-bold mt-1 text-amber-500">{{ formatCurrency(user.total_spent) }}</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                <p class="text-sm text-gray-500">Average Order</p>
                <p class="text-2xl font-bold mt-1">{{ formatCurrency(user.orders?.length ? user.total_spent / user.orders.length : 0) }}</p>
            </div>
        </div>

        <!-- Orders -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl">
            <div class="px-6 py-4 border-b border-gray-800">
                <h3 class="font-semibold">Recent Orders</h3>
            </div>
            <div class="divide-y divide-gray-800">
                <div v-for="order in user.orders" :key="order.id" class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <a :href="`/admin/orders/${order.id}`" class="text-sm font-medium text-amber-500 hover:text-amber-400">{{ order.order_number }}</a>
                        <p class="text-xs text-gray-500 mt-0.5">{{ formatDate(order.created_at) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium">{{ formatCurrency(order.total) }}</p>
                        <StatusBadge :status="order.status" type="order" class="mt-1" />
                    </div>
                </div>
                <div v-if="!user.orders?.length" class="px-6 py-8 text-center text-gray-500">No orders yet</div>
            </div>
        </div>
    </div>
    <div v-else class="flex items-center justify-center py-20">
        <i class="fas fa-spinner fa-spin text-amber-500 text-2xl"></i>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import StatusBadge from './common/StatusBadge.vue';

const userId = document.getElementById('admin-user-show')?.dataset?.userId;
const user = ref(null);

async function fetchUser() {
    try {
        const response = await fetch(`/admin/api/users/${userId}`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
        user.value = await response.json();
    } catch (e) { console.error(e); }
}

async function toggleAdmin() {
    try {
        const response = await fetch(`/admin/api/users/${userId}`, {
            method: 'PUT', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ is_admin: !user.value.is_admin }),
        });
        if (response.ok) { user.value.is_admin = !user.value.is_admin; showToast('User role updated'); }
    } catch (e) { console.error(e); }
}

function getInitials(name) { return name?.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) || '?'; }
function formatCurrency(amount) { return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount || 0); }
function formatDate(date) { return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }); }
function route(name) { return window.location.origin + '/admin/users'; }

function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[200] flex items-center gap-2';
    toast.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(() => toast.remove(), 300); }, 3000);
}

onMounted(() => fetchUser());
</script>