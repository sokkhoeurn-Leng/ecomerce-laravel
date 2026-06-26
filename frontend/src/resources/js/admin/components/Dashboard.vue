<template>
    <div class="dashboard">

        <!-- Page Header -->
        <div class="dash-header">
            <div>
                <h1 class="dash-title">Good {{ greeting }}, {{ adminName }} 👋</h1>
                <p class="dash-sub">Here's what's happening in your store today.</p>
            </div>
            <button @click="loadStats" class="refresh-btn">
                <i class="fas fa-sync-alt" :class="{ 'fa-spin': loading }"></i> Refresh
            </button>
        </div>

        <!-- ── Revenue ─────────────────────────────── -->
        <div class="section-label">Revenue</div>
        <div class="stats-grid cols-3">
            <div class="stat-card" style="--accent:#10b981">
                <div class="stat-top">
                    <div class="stat-icon-wrap"><i class="fas fa-dollar-sign"></i></div>
                    <span class="stat-trend trend-up"><i class="fas fa-chart-line"></i> All time</span>
                </div>
                <p class="stat-value">{{ fmt(stats.total_revenue) }}</p>
                <p class="stat-label">Total Revenue</p>
                <p class="stat-sub">Sum of all completed orders</p>
            </div>
            <div class="stat-card" style="--accent:#34d399">
                <div class="stat-top">
                    <div class="stat-icon-wrap"><i class="fas fa-sun"></i></div>
                    <span class="stat-trend trend-up"><i class="fas fa-calendar-day"></i> Today</span>
                </div>
                <p class="stat-value">{{ fmt(stats.today_revenue) }}</p>
                <p class="stat-label">Today Revenue</p>
            </div>
            <div class="stat-card" style="--accent:#6ee7b7">
                <div class="stat-top">
                    <div class="stat-icon-wrap"><i class="fas fa-calendar-alt"></i></div>
                    <span class="stat-trend trend-up"><i class="fas fa-calendar"></i> This month</span>
                </div>
                <p class="stat-value">{{ fmt(stats.monthly_revenue) }}</p>
                <p class="stat-label">Monthly Revenue</p>
            </div>
        </div>

        <!-- ── Orders ──────────────────────────────── -->
        <div class="section-label">Orders</div>
        <div class="stats-grid cols-5">
            <div class="stat-card" style="--accent:#6366f1">
                <div class="stat-top">
                    <div class="stat-icon-wrap"><i class="fas fa-shopping-bag"></i></div>
                </div>
                <p class="stat-value">{{ stats.total_orders ?? 0 }}</p>
                <p class="stat-label">Total Orders</p>
            </div>
            <div class="stat-card" style="--accent:#f59e0b">
                <div class="stat-top">
                    <div class="stat-icon-wrap"><i class="fas fa-clock"></i></div>
                </div>
                <p class="stat-value">{{ stats.pending_orders ?? 0 }}</p>
                <p class="stat-label">Pending</p>
            </div>
            <div class="stat-card" style="--accent:#818cf8">
                <div class="stat-top">
                    <div class="stat-icon-wrap"><i class="fas fa-cog"></i></div>
                </div>
                <p class="stat-value">{{ stats.processing_orders ?? 0 }}</p>
                <p class="stat-label">Processing</p>
            </div>
            <div class="stat-card" style="--accent:#10b981">
                <div class="stat-top">
                    <div class="stat-icon-wrap"><i class="fas fa-check-circle"></i></div>
                </div>
                <p class="stat-value">{{ stats.completed_orders ?? 0 }}</p>
                <p class="stat-label">Completed</p>
            </div>
            <div class="stat-card" style="--accent:#ef4444">
                <div class="stat-top">
                    <div class="stat-icon-wrap"><i class="fas fa-times-circle"></i></div>
                </div>
                <p class="stat-value">{{ stats.cancelled_orders ?? 0 }}</p>
                <p class="stat-label">Cancelled</p>
            </div>
        </div>

        <!-- ── Products ────────────────────────────── -->
        <div class="section-label">Products</div>
        <div class="stats-grid cols-4">
            <div class="stat-card" style="--accent:#f59e0b">
                <div class="stat-top">
                    <div class="stat-icon-wrap"><i class="fas fa-box-open"></i></div>
                </div>
                <p class="stat-value">{{ stats.total_products ?? 0 }}</p>
                <p class="stat-label">Total Products</p>
            </div>
            <div class="stat-card" style="--accent:#10b981">
                <div class="stat-top">
                    <div class="stat-icon-wrap"><i class="fas fa-check"></i></div>
                </div>
                <p class="stat-value">{{ stats.active_products ?? 0 }}</p>
                <p class="stat-label">Active Products</p>
            </div>
            <div class="stat-card" style="--accent:#ef4444">
                <div class="stat-top">
                    <div class="stat-icon-wrap"><i class="fas fa-ban"></i></div>
                </div>
                <p class="stat-value">{{ stats.out_of_stock ?? 0 }}</p>
                <p class="stat-label">Out of Stock</p>
            </div>
            <div class="stat-card" style="--accent:#f97316">
                <div class="stat-top">
                    <div class="stat-icon-wrap"><i class="fas fa-exclamation-triangle"></i></div>
                </div>
                <p class="stat-value">{{ stats.low_stock_products ?? 0 }}</p>
                <p class="stat-label">Low Stock</p>
            </div>
        </div>

        <!-- ── Customers ───────────────────────────── -->
        <div class="section-label">Customers</div>
        <div class="stats-grid cols-3">
            <div class="stat-card" style="--accent:#ec4899">
                <div class="stat-top">
                    <div class="stat-icon-wrap"><i class="fas fa-users"></i></div>
                </div>
                <p class="stat-value">{{ stats.total_customers ?? 0 }}</p>
                <p class="stat-label">Total Customers</p>
            </div>
            <div class="stat-card" style="--accent:#a78bfa">
                <div class="stat-top">
                    <div class="stat-icon-wrap"><i class="fas fa-user-check"></i></div>
                </div>
                <p class="stat-value">{{ stats.total_customers ?? 0 }}</p>
                <p class="stat-label">Registered Customers</p>
            </div>
            <div class="stat-card" style="--accent:#f472b6">
                <div class="stat-top">
                    <div class="stat-icon-wrap"><i class="fas fa-user-plus"></i></div>
                </div>
                <p class="stat-value">{{ stats.new_customers_month ?? 0 }}</p>
                <p class="stat-label">New This Month</p>
            </div>
        </div>

        <!-- ── Middle Row: Charts ──────────────────── -->
        <div class="mid-row">
            <div class="panel panel-lg">
                <div class="panel-head">
                    <div>
                        <h3 class="panel-title">Revenue Overview</h3>
                        <p class="panel-sub">Monthly revenue breakdown</p>
                    </div>
                    <div class="panel-badge"><i class="fas fa-chart-bar"></i> Last 12 months</div>
                </div>
                <div class="revenue-chart">
                    <div v-if="!stats.revenue_by_month?.length" class="empty-state">
                        <i class="fas fa-chart-line"></i><p>No revenue data yet</p>
                    </div>
                    <div v-else class="chart-rows">
                        <div v-for="item in stats.revenue_by_month" :key="item.month" class="chart-row">
                            <span class="chart-label">{{ formatMonth(item.month) }}</span>
                            <div class="chart-track">
                                <div class="chart-fill" :style="{ width: getRevenueWidth(item.revenue) + '%' }">
                                    <span v-if="getRevenueWidth(item.revenue) > 18" class="chart-value-inside">
                                        {{ fmt(item.revenue) }}
                                    </span>
                                </div>
                            </div>
                            <span v-if="getRevenueWidth(item.revenue) <= 18" class="chart-value-outside">
                                {{ fmt(item.revenue) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <div>
                        <h3 class="panel-title">Order Status</h3>
                        <p class="panel-sub">Current distribution</p>
                    </div>
                </div>
                <div class="status-list">
                    <div v-if="!Object.keys(stats.orders_by_status || {}).length" class="empty-state">
                        <i class="fas fa-shopping-bag"></i><p>No orders yet</p>
                    </div>
                    <div v-else>
                        <div v-for="(count, status) in stats.orders_by_status" :key="status" class="status-row">
                            <div class="status-left">
                                <span class="status-dot" :class="`dot-${status}`"></span>
                                <span class="status-name">{{ status }}</span>
                            </div>
                            <div class="status-right">
                                <div class="status-track">
                                    <div class="status-fill" :class="`fill-${status}`"
                                        :style="{ width: getStatusWidth(count) + '%' }"></div>
                                </div>
                                <span class="status-count">{{ count }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Bottom Row ──────────────────────────── -->
        <div class="bottom-row">
            <div class="panel">
                <div class="panel-head">
                    <div>
                        <h3 class="panel-title">Recent Orders</h3>
                        <p class="panel-sub">Latest {{ stats.recent_orders?.length || 0 }} orders</p>
                    </div>
                    <router-link to="/admin/orders" class="view-all">View All <i class="fas fa-arrow-right"></i></router-link>
                </div>
                <div class="orders-list">
                    <div v-if="!stats.recent_orders?.length" class="empty-state">
                        <i class="fas fa-receipt"></i><p>No orders yet</p>
                    </div>
                    <div v-for="order in stats.recent_orders" :key="order.id" class="order-row">
                        <div class="order-avatar">{{ (order.user?.name || 'G').charAt(0).toUpperCase() }}</div>
                        <div class="order-info">
                            <p class="order-num">{{ order.order_number }}</p>
                            <p class="order-user">{{ order.user?.name || 'Guest' }}</p>
                        </div>
                        <div class="order-meta">
                            <p class="order-amount">{{ fmt(order.total) }}</p>
                            <span class="order-badge" :class="`badge-${order.status}`">{{ order.status }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-head">
                    <div>
                        <h3 class="panel-title">Top Products</h3>
                        <p class="panel-sub">Best selling items</p>
                    </div>
                </div>
                <div class="products-list">
                    <div v-if="!stats.top_products?.length" class="empty-state">
                        <i class="fas fa-box-open"></i><p>No sales data yet</p>
                    </div>
                    <div v-for="(product, index) in stats.top_products" :key="product.product_name" class="product-row">
                        <div class="product-rank" :class="`rank-${index + 1}`">{{ index + 1 }}</div>
                        <div class="product-info">
                            <p class="product-name">{{ product.product_name }}</p>
                            <div class="product-bar-wrap">
                                <div class="product-bar">
                                    <div class="product-bar-fill" :style="{ width: getProductWidth(product.total_sold) + '%' }"></div>
                                </div>
                            </div>
                        </div>
                        <div class="product-sold">
                            <span class="sold-num">{{ product.total_sold }}</span>
                            <span class="sold-label">sold</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { apiGet } from '../api.js';

const stats = ref({});
const loading = ref(false);

const adminName = computed(() => (JSON.parse(localStorage.getItem('user'))?.name || 'Admin').split(' ')[0]);

const greeting = computed(() => {
    const h = new Date().getHours();
    if (h < 12) return 'morning';
    if (h < 17) return 'afternoon';
    return 'evening';
});

const maxRevenue = computed(() => {
    if (!stats.value.revenue_by_month?.length) return 0;
    return Math.max(...stats.value.revenue_by_month.map(i => parseFloat(i.revenue)));
});

const maxStatusCount = computed(() => {
    const vals = Object.values(stats.value.orders_by_status || {});
    return vals.length ? Math.max(...vals) : 1;
});

const maxSold = computed(() => {
    if (!stats.value.top_products?.length) return 1;
    return Math.max(...stats.value.top_products.map(p => p.total_sold));
});

function fmt(amount) {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount || 0);
}

function formatMonth(monthStr) {
    const [year, month] = monthStr.split('-');
    return new Date(year, month - 1).toLocaleDateString('en-US', { month: 'short', year: '2-digit' });
}

function getRevenueWidth(revenue) {
    if (!maxRevenue.value) return 0;
    return Math.max(4, (parseFloat(revenue) / maxRevenue.value) * 100);
}

function getStatusWidth(count) {
    return Math.max(8, (count / maxStatusCount.value) * 100);
}

function getProductWidth(sold) {
    return Math.max(8, (sold / maxSold.value) * 100);
}

async function loadStats() {
    loading.value = true;
    try { stats.value = await apiGet('/admin/dashboard'); }
    catch (e) { console.error(e); }
    finally { loading.value = false; }
}

onMounted(loadStats);
</script>

<style scoped>
.dashboard { display: flex; flex-direction: column; gap: 1.25rem; }

.dash-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}

.dash-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.6rem;
    font-weight: 700;
    color: #f1f5f9;
    margin-bottom: 0.25rem;
}

.dash-sub { font-size: 0.875rem; color: #64748b; }

.refresh-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.55rem 1.1rem;
    background: #1e2235;
    border: 1px solid #2a2f45;
    border-radius: 10px;
    color: #94a3b8;
    font-size: 0.82rem;
    cursor: pointer;
    transition: all 0.2s;
}
.refresh-btn:hover { background: #252b40; color: #e2e8f0; }

/* Section labels */
.section-label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #475569;
    margin-bottom: -0.5rem;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    gap: 1rem;
}
.cols-3 { grid-template-columns: repeat(3, 1fr); }
.cols-4 { grid-template-columns: repeat(4, 1fr); }
.cols-5 { grid-template-columns: repeat(5, 1fr); }

.stat-card {
    background: #13151e;
    border: 1px solid #1f2330;
    border-radius: 14px;
    padding: 1.2rem;
    position: relative;
    overflow: hidden;
    transition: transform 0.2s, border-color 0.2s;
}
.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--accent);
    border-radius: 14px 14px 0 0;
}
.stat-card:hover { transform: translateY(-2px); border-color: #2a2f45; }

.stat-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.85rem; }

.stat-icon-wrap {
    width: 36px; height: 36px;
    border-radius: 9px;
    background: color-mix(in srgb, var(--accent) 12%, transparent);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem;
    color: var(--accent);
}

.stat-trend {
    font-size: 0.68rem;
    font-weight: 500;
    padding: 0.2rem 0.55rem;
    border-radius: 50px;
    display: flex; align-items: center; gap: 0.3rem;
}
.trend-up { background: rgba(16,185,129,0.1); color: #10b981; }

.stat-value { font-size: 1.5rem; font-weight: 700; color: #f1f5f9; line-height: 1.2; margin-bottom: 0.2rem; }
.stat-label { font-size: 0.78rem; color: #64748b; font-weight: 500; }
.stat-sub   { font-size: 0.72rem; color: #475569; margin-top: 0.15rem; }

/* Panel */
.panel {
    background: #13151e;
    border: 1px solid #1f2330;
    border-radius: 16px;
    padding: 1.5rem;
}
.panel-lg { flex: 2; }

.panel-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    gap: 1rem;
    flex-wrap: wrap;
}
.panel-title { font-size: 1rem; font-weight: 600; color: #e2e8f0; margin-bottom: 0.2rem; }
.panel-sub { font-size: 0.78rem; color: #475569; }

.panel-badge {
    background: #1e2235;
    border: 1px solid #2a2f45;
    padding: 0.35rem 0.8rem;
    border-radius: 8px;
    font-size: 0.75rem;
    color: #64748b;
    display: flex; align-items: center; gap: 0.4rem;
    white-space: nowrap;
}

.view-all {
    font-size: 0.78rem;
    color: #f59e0b;
    text-decoration: none;
    display: flex; align-items: center; gap: 0.35rem;
    transition: color 0.2s;
    white-space: nowrap;
}
.view-all:hover { color: #fbbf24; }

/* Empty State */
.empty-state { text-align: center; padding: 2.5rem 1rem; color: #334155; }
.empty-state i { font-size: 2rem; display: block; margin-bottom: 0.75rem; }
.empty-state p { font-size: 0.85rem; }

/* Revenue Chart */
.chart-rows { display: flex; flex-direction: column; gap: 0.65rem; }
.chart-row { display: flex; align-items: center; gap: 0.75rem; }
.chart-label { font-size: 0.75rem; color: #475569; width: 56px; flex-shrink: 0; }
.chart-track { flex: 1; background: #1a1e2e; border-radius: 6px; height: 28px; overflow: hidden; }
.chart-fill {
    height: 100%;
    background: linear-gradient(90deg, #c8773a, #f59e0b);
    border-radius: 6px;
    display: flex; align-items: center; justify-content: flex-end;
    padding-right: 0.5rem;
    transition: width 0.8s ease;
    min-width: 4px;
}
.chart-value-inside { font-size: 0.7rem; font-weight: 600; color: #1a1a1a; }
.chart-value-outside { font-size: 0.72rem; color: #64748b; width: 70px; text-align: right; flex-shrink: 0; }

.mid-row, .bottom-row { display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem; }

/* Status List */
.status-list { display: flex; flex-direction: column; gap: 0.9rem; }
.status-row { display: flex; align-items: center; gap: 0.75rem; }
.status-left { display: flex; align-items: center; gap: 0.5rem; min-width: 100px; }
.status-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.dot-pending { background: #f59e0b; }
.dot-processing { background: #6366f1; }
.dot-shipped { background: #a78bfa; }
.dot-delivered { background: #10b981; }
.dot-cancelled { background: #ef4444; }
.status-name { font-size: 0.8rem; color: #94a3b8; text-transform: capitalize; }
.status-right { display: flex; align-items: center; gap: 0.6rem; flex: 1; }
.status-track { flex: 1; background: #1a1e2e; border-radius: 4px; height: 6px; overflow: hidden; }
.status-fill { height: 100%; border-radius: 4px; transition: width 0.8s ease; }
.fill-pending { background: #f59e0b; }
.fill-processing { background: #6366f1; }
.fill-shipped { background: #a78bfa; }
.fill-delivered { background: #10b981; }
.fill-cancelled { background: #ef4444; }
.status-count { font-size: 0.8rem; font-weight: 600; color: #cbd5e1; min-width: 20px; text-align: right; }

/* Orders List */
.orders-list { display: flex; flex-direction: column; gap: 0.1rem; }
.order-row {
    display: flex; align-items: center; gap: 0.85rem;
    padding: 0.7rem 0.5rem;
    border-radius: 10px;
    transition: background 0.15s;
}
.order-row:hover { background: #1a1e2e; }
.order-avatar {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, #c8773a, #f59e0b);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.8rem; font-weight: 700; color: white;
    flex-shrink: 0;
}
.order-info { flex: 1; min-width: 0; }
.order-num { font-size: 0.82rem; font-weight: 600; color: #cbd5e1; }
.order-user { font-size: 0.73rem; color: #475569; }
.order-meta { text-align: right; }
.order-amount { font-size: 0.85rem; font-weight: 600; color: #e2e8f0; margin-bottom: 0.25rem; }
.order-badge {
    display: inline-block;
    font-size: 0.67rem; font-weight: 600;
    padding: 0.15rem 0.5rem;
    border-radius: 50px;
    text-transform: capitalize;
}
.badge-pending    { background: rgba(245,158,11,0.15); color: #f59e0b; }
.badge-processing { background: rgba(99,102,241,0.15); color: #818cf8; }
.badge-shipped    { background: rgba(167,139,250,0.15); color: #a78bfa; }
.badge-delivered  { background: rgba(16,185,129,0.15); color: #34d399; }
.badge-cancelled  { background: rgba(239,68,68,0.15); color: #f87171; }

/* Products List */
.products-list { display: flex; flex-direction: column; gap: 0.85rem; }
.product-row { display: flex; align-items: center; gap: 0.85rem; }
.product-rank {
    width: 28px; height: 28px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.75rem; font-weight: 700;
    flex-shrink: 0;
}
.rank-1 { background: rgba(245,158,11,0.2); color: #f59e0b; }
.rank-2 { background: rgba(148,163,184,0.15); color: #94a3b8; }
.rank-3 { background: rgba(180,120,75,0.15); color: #b47c4b; }
.rank-4, .rank-5 { background: #1a1e2e; color: #475569; }
.product-info { flex: 1; min-width: 0; }
.product-name { font-size: 0.82rem; color: #cbd5e1; font-weight: 500; margin-bottom: 0.4rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.product-bar { background: #1a1e2e; border-radius: 4px; height: 5px; overflow: hidden; }
.product-bar-fill { height: 100%; background: linear-gradient(90deg, #c8773a, #f59e0b); border-radius: 4px; transition: width 0.8s ease; }
.product-sold { text-align: right; flex-shrink: 0; }
.sold-num { display: block; font-size: 0.9rem; font-weight: 700; color: #f1f5f9; }
.sold-label { font-size: 0.68rem; color: #475569; }

@media (max-width: 1100px) {
    .cols-5 { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 900px) {
    .mid-row, .bottom-row { grid-template-columns: 1fr; }
    .cols-3, .cols-4 { grid-template-columns: repeat(2, 1fr); }
    .cols-5 { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 520px) {
    .cols-3, .cols-4, .cols-5 { grid-template-columns: 1fr; }
}
</style>
