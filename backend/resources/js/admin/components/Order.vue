<template>
    <div class="space-y-6">
        <!-- Filters -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 flex-wrap">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                <input v-model="search" type="text" placeholder="Search by order number..."
                    class="bg-gray-900 border border-gray-800 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 w-64"
                    @input="debouncedSearch">
            </div>
            <select v-model="statusFilter" @change="fetchOrders(1)"
                class="bg-gray-900 border border-gray-800 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <select v-model="paymentFilter" @change="fetchOrders(1)"
                class="bg-gray-900 border border-gray-800 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50">
                <option value="">All Payments</option>
                <option value="paid">Paid</option>
                <option value="pending">Pending</option>
                <option value="failed">Failed</option>
                <option value="refunded">Refunded</option>
            </select>
            <input v-model="dateFrom" type="date" @change="fetchOrders(1)"
                class="bg-gray-900 border border-gray-800 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50">
            <input v-model="dateTo" type="date" @change="fetchOrders(1)"
                class="bg-gray-900 border border-gray-800 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50">
        </div>

        <!-- Table -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-800">
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Order</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Customer</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Items</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Total</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Status</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Payment</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Date</th>
                            <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <a :href="`/admin/orders/${order.id}`" class="text-sm font-medium text-amber-500 hover:text-amber-400">
                                    {{ order.order_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm">{{ order.user?.name || 'Guest' }}</p>
                                <p class="text-xs text-gray-500">{{ order.user?.email || '' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-400">{{ order.items_count || '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium">{{ formatCurrency(order.total) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <StatusBadge :status="order.status" type="order" />
                            </td>
                            <td class="px-6 py-4">
                                <StatusBadge :status="order.payment_status" type="payment" />
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-400">{{ formatDate(order.created_at) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a :href="`/admin/orders/${order.id}`" class="p-2 text-gray-500 hover:text-amber-500 transition-colors" title="View">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                            </td>
                        </tr>
                        <tr v-if="!orders.length && !loading">
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-shopping-cart text-3xl mb-3 block"></i>
                                No orders found
                            </td>
                        </tr>
                        <tr v-if="loading">
                            <td colspan="8" class="px-6 py-12 text-center">
                                <i class="fas fa-spinner fa-spin text-amber-500 text-2xl"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <Pagination :pagination="pagination" @change="fetchOrders" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import StatusBadge from './common/StatusBadge.vue';
import Pagination from './common/Pagination.vue';

const orders = ref([]);
const pagination = ref({});
const loading = ref(false);
const search = ref('');
const statusFilter = ref('');
const paymentFilter = ref('');
const dateFrom = ref('');
const dateTo = ref('');

let searchTimeout;
function debouncedSearch() { clearTimeout(searchTimeout); searchTimeout = setTimeout(() => fetchOrders(1), 300); }

async function fetchOrders(page = 1) {
    loading.value = true;
    try {
        const params = new URLSearchParams({
            page, search: search.value, status: statusFilter.value,
            payment_status: paymentFilter.value, date_from: dateFrom.value, date_to: dateTo.value
        });
        const response = await fetch(`/admin/api/orders?${params}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        orders.value = data.data;
        pagination.value = { current_page: data.current_page, last_page: data.last_page, per_page: data.per_page, total: data.total, from: data.from, to: data.to };
    } catch (e) { console.error(e); }
    finally { loading.value = false; }
}

function formatCurrency(amount) { return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount); }
function formatDate(date) { return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }); }

onMounted(() => fetchOrders());
</script>