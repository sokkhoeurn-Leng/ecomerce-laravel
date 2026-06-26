<template>
    <div class="space-y-6">
        <!-- Filters -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                <input v-model="search" type="text" placeholder="Search users..."
                    class="bg-gray-900 border border-gray-800 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 w-64"
                    @input="debouncedSearch">
            </div>
            <select v-model="roleFilter" @change="fetchUsers(1)"
                class="bg-gray-900 border border-gray-800 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50">
                <option value="">All Roles</option>
                <option value="admin">Admins</option>
                <option value="user">Users</option>
            </select>
        </div>

        <!-- Table -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-800">
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">User</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Role</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Orders</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Joined</th>
                            <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <tr v-for="user in users" :key="user.id" class="hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-amber-500/10 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-sm font-bold text-amber-500">{{ getInitials(user.name) }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">{{ user.name }}</p>
                                        <p class="text-xs text-gray-500">{{ user.email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium"
                                    :class="user.is_admin ? 'bg-amber-500/10 text-amber-500' : 'bg-gray-500/10 text-gray-400'">
                                    <i :class="user.is_admin ? 'fas fa-shield-alt' : 'fas fa-user'" class="text-[10px]"></i>
                                    {{ user.is_admin ? 'Admin' : 'User' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-400">{{ user.orders_count || 0 }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-400">{{ formatDate(user.created_at) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a :href="`/admin/users/${user.id}`" class="p-2 text-gray-500 hover:text-amber-500 transition-colors" title="View">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <button @click="toggleAdmin(user)" class="p-2 text-gray-500 hover:text-blue-500 transition-colors" title="Toggle Admin">
                                        <i class="fas fa-shield-alt text-sm"></i>
                                    </button>
                                    <button @click="confirmDelete(user)" class="p-2 text-gray-500 hover:text-red-500 transition-colors" title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!users.length && !loading">
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-users text-3xl mb-3 block"></i>
                                No users found
                            </td>
                        </tr>
                        <tr v-if="loading">
                            <td colspan="5" class="px-6 py-12 text-center">
                                <i class="fas fa-spinner fa-spin text-amber-500 text-2xl"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <Pagination :pagination="pagination" @change="fetchUsers" />

        <!-- Delete Modal -->
        <Modal :show="showDeleteModal" title="Delete User" size="sm" @close="showDeleteModal = false">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                </div>
                <p class="text-sm text-gray-400 mb-6">
                    Are you sure you want to delete <strong class="text-white">{{ deletingUser?.name }}</strong>? This will also delete all their orders.
                </p>
                <div class="flex justify-center gap-3">
                    <button @click="showDeleteModal = false" class="px-4 py-2.5 text-sm text-gray-400 hover:text-white transition-colors">Cancel</button>
                    <button @click="deleteUser" :disabled="deleting"
                        class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg text-sm transition-colors disabled:opacity-50">
                        <i v-if="deleting" class="fas fa-spinner fa-spin mr-2"></i>Delete
                    </button>
                </div>
            </div>
        </Modal>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Modal from './common/Modal.vue';
import Pagination from './common/Pagination.vue';

const users = ref([]);
const pagination = ref({});
const loading = ref(false);
const deleting = ref(false);
const search = ref('');
const roleFilter = ref('');
const showDeleteModal = ref(false);
const deletingUser = ref(null);

let searchTimeout;
function debouncedSearch() { clearTimeout(searchTimeout); searchTimeout = setTimeout(() => fetchUsers(1), 300); }

async function fetchUsers(page = 1) {
    loading.value = true;
    try {
        const params = new URLSearchParams({ page, search: search.value, role: roleFilter.value });
        const response = await fetch(`/admin/api/users?${params}`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await response.json();
        users.value = data.data;
        pagination.value = { current_page: data.current_page, last_page: data.last_page, per_page: data.per_page, total: data.total, from: data.from, to: data.to };
    } catch (e) { console.error(e); }
    finally { loading.value = false; }
}

async function toggleAdmin(user) {
    try {
        const response = await fetch(`/admin/api/users/${user.id}`, {
            method: 'PUT', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ is_admin: !user.is_admin }),
        });
        if (response.ok) { user.is_admin = !user.is_admin; showToast('User role updated'); }
    } catch (e) { console.error(e); }
}

function confirmDelete(user) { deletingUser.value = user; showDeleteModal.value = true; }

async function deleteUser() {
    deleting.value = true;
    try {
        const response = await fetch(`/admin/api/users/${deletingUser.value.id}`, { method: 'DELETE', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
        if (!response.ok) throw new Error();
        showDeleteModal.value = false; await fetchUsers(); showToast('User deleted');
    } catch (e) { console.error(e); }
    finally { deleting.value = false; }
}

function getInitials(name) { return name?.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) || '?'; }
function formatDate(date) { return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }); }
function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[200] flex items-center gap-2';
    toast.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(() => toast.remove(), 300); }, 3000);
}

onMounted(() => fetchUsers());
</script>