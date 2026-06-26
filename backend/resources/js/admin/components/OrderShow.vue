<template>
    <div v-if="order" class="space-y-6">
        <!-- Back Button -->
        <a :href="route('admin.orders.index')" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white transition-colors">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>

        <!-- Order Header -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold">{{ order.order_number }}</h2>
                    <p class="text-sm text-gray-500 mt-1">Placed on {{ formatDate(order.created_at) }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <StatusBadge :status="order.status" type="order" />
                    <StatusBadge :status="order.payment_status" type="payment" />
                </div>
            </div>

            <!-- Update Status Form -->
            <div class="mt-6 pt-6 border-t border-gray-800 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1.5">Order Status</label>
                    <select v-model="statusForm.status" @change="updateStatus"
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50">
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1.5">Payment Status</label>
                    <select v-model="statusForm.payment_status" @change="updateStatus"
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50">
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1.5">Notes</label>
                    <input v-model="statusForm.notes" @blur="updateStatus" type="text"
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50"
                        placeholder="Add notes...">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Items -->
            <div class="lg:col-span-2 bg-gray-900 border border-gray-800 rounded-xl">
                <div class="px-6 py-4 border-b border-gray-800">
                    <h3 class="font-semibold">Order Items ({{ order.items?.length || 0 }})</h3>
                </div>
                <div class="divide-y divide-gray-800">
                    <div v-for="item in order.items" :key="item.id" class="px-6 py-4 flex items-center gap-4">
                        <div class="flex-1">
                            <p class="text-sm font-medium">{{ item.product_name }}</p>
                            <p class="text-xs text-gray-500">Qty: {{ item.quantity }} × {{ formatCurrency(item.price) }}</p>
                        </div>
                        <p class="text-sm font-medium">{{ formatCurrency(item.total) }}</p>
                    </div>
                    <div v-if="!order.items?.length" class="px-6 py-8 text-center text-gray-500">No items</div>
                </div>
                <!-- Totals -->
                <div class="px-6 py-4 border-t border-gray-800 space-y-2">
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal</span><span>{{ formatCurrency(order.subtotal) }}</span></div>
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Tax</span><span>{{ formatCurrency(order.tax) }}</span></div>
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Shipping</span><span>{{ formatCurrency(order.shipping) }}</span></div>
                    <div class="flex justify-between text-base font-bold pt-2 border-t border-gray-800"><span>Total</span><span class="text-amber-500">{{ formatCurrency(order.total) }}</span></div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Customer -->
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                    <h3 class="font-semibold mb-4">Customer</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-amber-500/10 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-amber-500 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium">{{ order.user?.name || 'Guest' }}</p>
                                <p class="text-xs text-gray-500">{{ order.user?.email || '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                    <h3 class="font-semibold mb-4">Shipping Address</h3>
                    <div v-if="order.shipping_address" class="space-y-1 text-sm text-gray-400">
                        <p>{{ order.shipping_address.name }}</p>
                        <p>{{ order.shipping_address.address }}</p>
                        <p>{{ order.shipping_address.city }}, {{ order.shipping_address.state }} {{ order.shipping_address.zip }}</p>
                        <p>{{ order.shipping_address.country }}</p>
                        <p class="mt-2">{{ order.shipping_address.phone }}</p>
                    </div>
                    <p v-else class="text-sm text-gray-500">No shipping address</p>
                </div>

                <!-- Payment Info -->
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                    <h3 class="font-semibold mb-4">Payment Info</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Method</span><span>{{ order.payment_method || 'N/A' }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Status</span><StatusBadge :status="order.payment_status" type="payment" /></div>
                    </div>
                </div>
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

const orderId = document.getElementById('admin-order-show')?.dataset?.orderId;
const order = ref(null);
const statusForm = ref({ status: '', payment_status: '', notes: '' });

async function fetchOrder() {
    try {
        const response = await fetch(`/admin/api/orders/${orderId}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        order.value = await response.json();
        statusForm.value = { status: order.value.status, payment_status: order.value.payment_status, notes: order.value.notes || '' };
    } catch (e) { console.error(e); }
}

async function updateStatus() {
    try {
        const response = await fetch(`/admin/api/orders/${orderId}/status`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify(statusForm.value),
        });
        if (response.ok) { showToast('Order updated'); }
    } catch (e) { console.error(e); }
}

function formatCurrency(amount) { return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount); }
function formatDate(date) { return new Date(date).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' }); }
function route(name, params) { return window.location.origin + '/admin/orders/' + (params?.id || orderId); }

function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[200] flex items-center gap-2';
    toast.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(() => toast.remove(), 300); }, 3000);
}

onMounted(() => fetchOrder());
</script>