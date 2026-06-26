<template>
    <div class="space-y-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div v-for="stat in statCards" :key="stat.label" 
                 class="bg-gray-900 border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition-colors">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-gray-500">{{ stat.label }}</span>
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center" :class="stat.iconBg">
                        <i :class="[stat.icon, stat.iconColor]"></i>
                    </div>
                </div>
                <p class="text-2xl font-bold">{{ stat.value }}</p>
                <p v-if="stat.subtext" class="text-xs text-gray-500 mt-1">{{ stat.subtext }}</p>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Revenue Chart -->
            <div class="lg:col-span-2 bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold mb-6">Revenue Overview</h3>
                <div class="space-y-3">
                    <div v-for="item in stats.revenue_by_month" :key="item.month" 
                         class="flex items-center gap-4">
                        <span class="text-sm text-gray-500 w-20">{{ formatMonth(item.month) }}</span>
                        <div class="flex-1 bg-gray-800 rounded-full h-8 overflow-hidden">
                            <div 
                                class="h-full bg-gradient-to-r from-amber-500 to-orange-500 rounded-full flex items-center justify-end pr-3 transition-all duration-500"
                                :style="{ width: getRevenueWidth(item.revenue) + '%' }"
                            >
                                <span v-if="getRevenueWidth(item.revenue) > 15" class="text-xs font-medium text-gray-900">
                                    {{ formatCurrency(item.revenue) }}
                                </span>
                            </div>
                        </div>
                        <span v-if="getRevenueWidth(item.revenue) <= 15" class="text-sm text-gray-400 w-24 text-right">
                            {{ formatCurrency(item.revenue) }}
                        </span>
                    </div>
                    <div v-if="!stats.revenue_by_month?.length" class="text-center text-gray-500 py-8">
                        No revenue data yet
                    </div>
                </div>
            </div>

            <!-- Orders by Status -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold mb-6">Orders by Status</h3>
                <div class="space-y-4">
                    <div v-for="(count, status) in stats.orders_by_status" :key="status" 
                         class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <StatusBadge :status="status" type="order" />
                        </div>
                        <span class="text-sm font-medium">{{ count }}</span>
                    </div>
                    <div v-if="!Object.keys(stats.orders_by_status || {}).length" class="text-center text-gray-500 py-8">
                        No orders yet
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Orders -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl">
                <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Recent Orders</h3>
                    <a href="/admin/orders" class="text-sm text-amber-500 hover:text-amber-400">
                        View All
                    </a>
                </div>
                <div class="divide-y divide-gray-800">
                    <div v-for="order in stats.recent_orders" :key="order.id" class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <a :href="`/admin/orders/${order.id}`" class="text-sm font-medium hover:text-amber-500">
                                    {{ order.order_number }}
                                </a>
                                <p class="text-xs text-gray-500 mt-0.5">{{ order.user?.name || 'Guest' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium">{{ formatCurrency(order.total) }}</p>
                                <StatusBadge :status="order.status" type="order" class="mt-1" />
                            </div>
                        </div>
                    </div>
                    <div v-if="!stats.recent_orders?.length" class="px-6 py-8 text-center text-gray-500">
                        No orders yet
                    </div>
                </div>
            </div>

            <!-- Top Products -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl">
                <div class="px-6 py-4 border-b border-gray-800">
                    <h3 class="text-lg font-semibold">Top Selling Products</h3>
                </div>
                <div class="divide-y divide-gray-800">
                    <div v-for="(product, index) in stats.top_products" :key="product.product_name" class="px-6 py-4 flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-sm font-medium text-gray-400">
                            {{ index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">{{ product.product_name }}</p>
                            <p class="text-xs text-gray-500">{{ product.total_sold }} sold</p>
                        </div>
                    </div>
                    <div v-if="!stats.top_products?.length" class="px-6 py-8 text-center text-gray-500">
                        No sales data yet
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import StatusBadge from './common/StatusBadge.vue';

const stats = computed(() => {
    const data = document.getElementById('admin-dashboard')?.dataset?.stats;
    return data ? JSON.parse(data) : {};
});

const maxRevenue = computed(() => {
    if (!stats.value.revenue_by_month?.length) return 0;
    return Math.max(...stats.value.revenue_by_month.map(item => parseFloat(item.revenue)));
});

const statCards = computed(() => [
    {
        label: 'Total Revenue',
        value: formatCurrency(stats.value.total_revenue),
        icon: 'fas fa-dollar-sign',
        iconBg: 'bg-green-500/10',
        iconColor: 'text-green-500',
    },
    {
        label: 'Total Orders',
        value: stats.value.total_orders || 0,
        subtext: `${stats.value.pending_orders || 0} pending`,
        icon: 'fas fa-shopping-bag',
        iconBg: 'bg-blue-500/10',
        iconColor: 'text-blue-500',
    },
    {
        label: 'Total Products',
        value: stats.value.total_products || 0,
        subtext: `${stats.value.low_stock_products || 0} low stock`,
        icon: 'fas fa-box',
        iconBg: 'bg-purple-500/10',
        iconColor: 'text-purple-500',
    },
    {
        label: 'Total Users',
        value: stats.value.total_users || 0,
        icon: 'fas fa-users',
        iconBg: 'bg-amber-500/10',
        iconColor: 'text-amber-500',
    },
]);

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(amount || 0);
}

function formatMonth(monthStr) {
    const [year, month] = monthStr.split('-');
    const date = new Date(year, month - 1);
    return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' });
}

function getRevenueWidth(revenue) {
    if (!maxRevenue.value) return 0;
    return Math.max(5, (parseFloat(revenue) / maxRevenue.value) * 100);
}
</script>
