<template>
    <div class="page">

        <!-- Toolbar -->
        <div class="toolbar">
            <div class="toolbar-left">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input v-model="search" type="text" placeholder="Search users..." @input="debouncedSearch" />
                </div>
                <select v-model="roleFilter" @change="fetchUsers(1)" class="filter-select">
                    <option value="">All Roles</option>
                    <option value="admin">Admins</option>
                    <option value="user">Users</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="table-card">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Orders</th>
                            <th>Joined</th>
                            <th class="th-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="loading">
                            <td colspan="5" class="td-center">
                                <i class="fas fa-spinner fa-spin spinner"></i>
                            </td>
                        </tr>
                        <tr v-else-if="!users.length">
                            <td colspan="5" class="td-center td-empty">
                                <i class="fas fa-users empty-icon"></i>
                                <p>No users found</p>
                            </td>
                        </tr>
                        <tr v-for="user in users" :key="user.id" v-else>
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">{{ getInitials(user.name) }}</div>
                                    <div>
                                        <p class="user-name">{{ user.name }}</p>
                                        <p class="user-email">{{ user.email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="role-badge" :class="user.is_admin ? 'role-admin' : 'role-user'">
                                    <i :class="user.is_admin ? 'fas fa-shield-alt' : 'fas fa-user'"></i>
                                    {{ user.is_admin ? 'Admin' : 'User' }}
                                </span>
                            </td>
                            <td><span class="orders-count">{{ user.orders_count || 0 }}</span></td>
                            <td><span class="joined-date">{{ formatDate(user.created_at) }}</span></td>
                            <td class="td-right">
                                <div class="action-btns">
                                    <button @click="toggleAdmin(user)" class="action-btn action-shield" title="Toggle Admin">
                                        <i class="fas fa-shield-alt"></i>
                                    </button>
                                    <button @click="confirmDelete(user)" class="action-btn action-delete" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <Pagination :pagination="pagination" @change="fetchUsers" />

        <!-- Delete Modal -->
        <Modal :show="showDeleteModal" title="Delete User" size="sm" @close="showDeleteModal = false">
            <div class="confirm-modal">
                <div class="confirm-icon confirm-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <p class="confirm-text">
                    Are you sure you want to delete <strong>{{ deletingUser?.name }}</strong>?
                    This will also delete all their orders.
                </p>
                <div class="confirm-btns">
                    <button @click="showDeleteModal = false" class="btn-cancel">Cancel</button>
                    <button @click="deleteUser" :disabled="deleting" class="btn-danger">
                        <i v-if="deleting" class="fas fa-spinner fa-spin"></i> Delete
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
import { apiGet, apiPut, apiDelete } from '../api.js';

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
        const data = await apiGet(`/admin/users?${params}`);
        users.value = data.data;
        pagination.value = { current_page: data.current_page, last_page: data.last_page, per_page: data.per_page, total: data.total, from: data.from, to: data.to };
    } catch (e) { console.error(e); }
    finally { loading.value = false; }
}

async function toggleAdmin(user) {
    try {
        const res = await apiPut(`/admin/users/${user.id}`, { is_admin: !user.is_admin });
        if (res.ok) { user.is_admin = !user.is_admin; showToast('User role updated'); }
    } catch (e) { console.error(e); }
}

function confirmDelete(user) { deletingUser.value = user; showDeleteModal.value = true; }

async function deleteUser() {
    deleting.value = true;
    try {
        const res = await apiDelete(`/admin/users/${deletingUser.value.id}`);
        if (!res.ok) throw new Error();
        showDeleteModal.value = false; await fetchUsers(); showToast('User deleted');
    } catch (e) { console.error(e); }
    finally { deleting.value = false; }
}

function getInitials(name) { return name?.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2) || '?'; }
function formatDate(d) { return new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }); }
function showToast(msg) {
    const t = document.createElement('div');
    t.className = 'admin-toast';
    t.innerHTML = `<i class="fas fa-check-circle"></i> ${msg}`;
    document.body.appendChild(t);
    setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 3000);
}

onMounted(() => fetchUsers());
</script>

<style scoped>
.page { display: flex; flex-direction: column; gap: 1.5rem; }

.toolbar { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
.toolbar-left { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; }

.search-box { position: relative; }
.search-icon { position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: #4a5068; font-size: 0.8rem; pointer-events: none; }
.search-box input { background: #1a1e2e; border: 1px solid #2a2f45; border-radius: 10px; padding: 0.6rem 1rem 0.6rem 2.4rem; color: #e2e8f0; font-size: 0.875rem; width: 240px; outline: none; transition: border-color 0.2s; }
.search-box input:focus { border-color: #c8773a; }
.search-box input::placeholder { color: #3a4060; }

.filter-select { background: #1a1e2e; border: 1px solid #2a2f45; border-radius: 10px; padding: 0.6rem 1rem; color: #e2e8f0; font-size: 0.875rem; outline: none; cursor: pointer; }
.filter-select:focus { border-color: #c8773a; }

.table-card { background: #13151e; border: 1px solid #1f2330; border-radius: 16px; overflow: hidden; }
.table-wrap { overflow-x: auto; }

table { width: 100%; border-collapse: collapse; }
thead tr { border-bottom: 1px solid #1f2330; }
th { text-align: left; font-size: 0.72rem; font-weight: 600; color: #4a5068; text-transform: uppercase; letter-spacing: 0.08em; padding: 0.9rem 1.25rem; white-space: nowrap; }
.th-right { text-align: right; }
tbody tr { border-bottom: 1px solid #1a1e2e; transition: background 0.15s; }
tbody tr:last-child { border-bottom: none; }
tbody tr:hover { background: #1a1e2e; }
td { padding: 1rem 1.25rem; font-size: 0.875rem; color: #cbd5e1; vertical-align: middle; }
.td-right { text-align: right; }
.td-center { text-align: center; padding: 3rem 1rem; }
.td-empty { color: #3a4060; }
.empty-icon { font-size: 2rem; display: block; margin-bottom: 0.75rem; }
.spinner { font-size: 1.5rem; color: #c8773a; }

.user-info { display: flex; align-items: center; gap: 0.85rem; }
.user-avatar { width: 40px; height: 40px; background: linear-gradient(135deg, rgba(200,119,58,0.2), rgba(232,148,90,0.1)); border: 1px solid rgba(200,119,58,0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; color: #f5a85a; flex-shrink: 0; }
.user-name { font-weight: 600; color: #e2e8f0; margin-bottom: 0.15rem; }
.user-email { font-size: 0.75rem; color: #4a5068; }

.role-badge { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.3rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; }
.role-admin { background: rgba(245,158,11,0.12); color: #fbbf24; }
.role-user  { background: rgba(100,116,139,0.12); color: #94a3b8; }

.orders-count { color: #94a3b8; }
.joined-date  { font-size: 0.82rem; color: #64748b; white-space: nowrap; }

.action-btns { display: flex; align-items: center; justify-content: flex-end; gap: 0.4rem; }
.action-btn { width: 32px; height: 32px; border-radius: 8px; border: 1px solid #2a2f45; background: #1e2235; color: #6b7280; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.78rem; transition: all 0.18s; }
.action-shield:hover { border-color: #6366f1; color: #818cf8; background: rgba(99,102,241,0.1); }
.action-delete:hover { border-color: #ef4444; color: #f87171; background: rgba(239,68,68,0.1); }

.confirm-modal { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 1rem; }
.confirm-icon { width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
.confirm-danger { background: rgba(239,68,68,0.12); color: #f87171; }
.confirm-text { font-size: 0.875rem; color: #94a3b8; line-height: 1.6; }
.confirm-text strong { color: #e2e8f0; }
.confirm-btns { display: flex; gap: 0.75rem; }
.btn-cancel { padding: 0.6rem 1.2rem; background: transparent; border: 1px solid #2a2f45; border-radius: 10px; color: #6b7280; font-size: 0.875rem; cursor: pointer; transition: all 0.18s; }
.btn-cancel:hover { border-color: #4a5068; color: #cbd5e1; }
.btn-danger { padding: 0.6rem 1.4rem; background: #ef4444; border: none; border-radius: 10px; color: white; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.18s; display: flex; align-items: center; gap: 0.5rem; }
.btn-danger:hover:not(:disabled) { background: #dc2626; }
.btn-danger:disabled { opacity: 0.5; cursor: not-allowed; }
</style>
