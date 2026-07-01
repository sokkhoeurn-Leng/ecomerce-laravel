<template>
  <div class="dashboard">

    <!-- Header -->
    <div class="dash-header">
      <div>
        <h1 class="dash-title">Good {{ greeting }}, {{ adminName }} 👋</h1>
        <p class="dash-sub">Here's what's happening in your store today.</p>
      </div>
      <button @click="loadStats" class="refresh-btn" :disabled="loading">
        <i class="fas fa-sync-alt" :class="{ 'fa-spin': loading }"></i>
        Refresh
      </button>
    </div>

    <!-- KPI Row -->
    <div class="kpi-grid">
      <div class="kpi-card" style="--c:#10b981">
        <div class="kpi-left">
          <div class="kpi-icon"><i class="fas fa-dollar-sign"></i></div>
          <div class="kpi-texts">
            <p class="kpi-value">{{ fmt(stats.today_revenue) }}</p>
            <p class="kpi-label">Today's Revenue</p>
          </div>
        </div>
        <div class="kpi-extra">
          <span class="kpi-sub">Month: {{ fmt(stats.monthly_revenue) }}</span>
          <span class="kpi-sub">All time: {{ fmt(stats.total_revenue) }}</span>
        </div>
      </div>

      <div class="kpi-card" style="--c:#6366f1">
        <div class="kpi-left">
          <div class="kpi-icon"><i class="fas fa-shopping-bag"></i></div>
          <div class="kpi-texts">
            <p class="kpi-value">{{ stats.total_orders ?? 0 }}</p>
            <p class="kpi-label">Total Orders</p>
          </div>
        </div>
        <div class="kpi-badges">
          <span class="kpi-badge pending">{{ stats.pending_orders ?? 0 }} pending</span>
          <span class="kpi-badge processing">{{ stats.processing_orders ?? 0 }} processing</span>
        </div>
      </div>

      <div class="kpi-card" style="--c:#f59e0b">
        <div class="kpi-left">
          <div class="kpi-icon"><i class="fas fa-box-open"></i></div>
          <div class="kpi-texts">
            <p class="kpi-value">{{ stats.active_products ?? 0 }}</p>
            <p class="kpi-label">Active Products</p>
          </div>
        </div>
        <div class="kpi-badges">
          <span v-if="stats.out_of_stock > 0" class="kpi-badge danger">{{ stats.out_of_stock }} out of stock</span>
          <span v-if="stats.low_stock_products > 0" class="kpi-badge warn">{{ stats.low_stock_products }} low stock</span>
          <span v-if="!stats.out_of_stock && !stats.low_stock_products" class="kpi-badge ok">All stocked</span>
        </div>
      </div>

      <div class="kpi-card" style="--c:#ec4899">
        <div class="kpi-left">
          <div class="kpi-icon"><i class="fas fa-users"></i></div>
          <div class="kpi-texts">
            <p class="kpi-value">{{ stats.total_customers ?? 0 }}</p>
            <p class="kpi-label">Total Customers</p>
          </div>
        </div>
        <div class="kpi-extra">
          <span class="kpi-sub">{{ stats.new_customers_month ?? 0 }} new this month</span>
        </div>
      </div>
    </div>

    <!-- Revenue Chart (Full Width) -->
    <div class="panel">
      <div class="panel-head">
        <div>
          <h3 class="panel-title">Revenue Overview</h3>
          <p class="panel-sub">Monthly revenue for the last 12 months</p>
        </div>
        <div class="panel-badge"><i class="fas fa-chart-bar"></i> 12 Months</div>
      </div>
      <div class="revenue-chart">
        <div v-if="!stats.revenue_by_month?.length" class="empty-state">
          <i class="fas fa-chart-line"></i>
          <p>No revenue data yet</p>
        </div>
        <div v-else class="chart-bars">
          <div v-for="item in stats.revenue_by_month" :key="item.month" class="chart-bar-wrap">
            <div class="chart-bar-track">
              <div class="chart-bar-fill" :style="{ height: getRevenueHeight(item.revenue) + '%' }">
                <span class="chart-tooltip">{{ fmt(item.revenue) }}</span>
              </div>
            </div>
            <span class="chart-bar-label">{{ formatMonth(item.month) }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Middle Row -->
    <div class="mid-row">
      <!-- Order Status -->
      <div class="panel">
        <div class="panel-head">
          <div>
            <h3 class="panel-title">Order Status</h3>
            <p class="panel-sub">Current distribution</p>
          </div>
        </div>
        <div class="status-list">
          <div v-if="!Object.keys(stats.orders_by_status || {}).length" class="empty-state small">
            <i class="fas fa-shopping-bag"></i><p>No orders yet</p>
          </div>
          <div v-for="(count, status) in stats.orders_by_status" :key="status" class="status-row">
            <div class="status-left">
              <span class="status-dot" :class="`dot-${status}`"></span>
              <span class="status-name">{{ status }}</span>
            </div>
            <div class="status-right">
              <div class="status-track">
                <div class="status-fill" :class="`fill-${status}`" :style="{ width: getStatusWidth(count) + '%' }"></div>
              </div>
              <span class="status-count">{{ count }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Breakdown Summary -->
      <div class="panel">
        <div class="panel-head">
          <div>
            <h3 class="panel-title">Order Breakdown</h3>
            <p class="panel-sub">Completed vs cancelled</p>
          </div>
        </div>
        <div class="breakdown-grid">
          <div class="breakdown-card completed">
            <i class="fas fa-check-circle"></i>
            <div class="breakdown-num">{{ stats.completed_orders ?? 0 }}</div>
            <div class="breakdown-label">Completed</div>
            <div class="breakdown-bar">
              <div class="breakdown-fill" :style="{ width: getCompletionRate() + '%' }"></div>
            </div>
            <div class="breakdown-pct">{{ getCompletionRate() }}%</div>
          </div>
          <div class="breakdown-card cancelled">
            <i class="fas fa-times-circle"></i>
            <div class="breakdown-num">{{ stats.cancelled_orders ?? 0 }}</div>
            <div class="breakdown-label">Cancelled</div>
            <div class="breakdown-bar">
              <div class="breakdown-fill red" :style="{ width: getCancellationRate() + '%' }"></div>
            </div>
            <div class="breakdown-pct">{{ getCancellationRate() }}%</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Row -->
    <div class="bottom-row">
      <!-- Recent Orders -->
      <div class="panel">
        <div class="panel-head">
          <div>
            <h3 class="panel-title">Recent Orders</h3>
            <p class="panel-sub">Latest {{ stats.recent_orders?.length || 0 }} orders</p>
          </div>
          <router-link to="/admin/orders" class="view-all">View All <i class="fas fa-arrow-right"></i></router-link>
        </div>
        <div class="orders-list">
          <div v-if="!stats.recent_orders?.length" class="empty-state small">
            <i class="fas fa-receipt"></i><p>No orders yet</p>
          </div>
          <div v-for="order in stats.recent_orders" :key="order.id" class="order-row">
            <div class="order-avatar">{{ (order.user?.name || 'G').charAt(0).toUpperCase() }}</div>
            <div class="order-info">
              <p class="order-id">{{ order.order_number }}</p>
              <p class="order-user">{{ order.user?.name || 'Guest' }}</p>
            </div>
            <div class="order-right">
              <p class="order-amount">{{ fmt(order.total) }}</p>
              <span class="order-badge" :class="`badge-${order.status}`">{{ order.status }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Top Products -->
      <div class="panel">
        <div class="panel-head">
          <div>
            <h3 class="panel-title">Top Products</h3>
            <p class="panel-sub">Best selling items</p>
          </div>
        </div>
        <div class="products-list">
          <div v-if="!stats.top_products?.length" class="empty-state small">
            <i class="fas fa-box-open"></i><p>No sales data yet</p>
          </div>
          <div v-for="(product, index) in stats.top_products" :key="product.product_name" class="product-row">
            <div class="product-rank" :class="{ gold: index === 0, silver: index === 1, bronze: index === 2 }">
              {{ index + 1 }}
            </div>
            <div class="product-info">
              <p class="product-name">{{ product.product_name }}</p>
              <div class="product-bar">
                <div class="product-bar-fill" :style="{ width: getProductWidth(product.total_sold) + '%' }"></div>
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
  return new Date(year, month - 1).toLocaleDateString('en-US', { month: 'short' });
}

function getRevenueHeight(revenue) {
  if (!maxRevenue.value) return 0;
  return Math.max(4, (parseFloat(revenue) / maxRevenue.value) * 100);
}

function getStatusWidth(count) {
  return Math.max(6, (count / maxStatusCount.value) * 100);
}

function getProductWidth(sold) {
  return Math.max(6, (sold / maxSold.value) * 100);
}

function getCompletionRate() {
  const total = (stats.value.completed_orders || 0) + (stats.value.cancelled_orders || 0);
  return total ? Math.round(((stats.value.completed_orders || 0) / total) * 100) : 0;
}

function getCancellationRate() {
  const total = (stats.value.completed_orders || 0) + (stats.value.cancelled_orders || 0);
  return total ? Math.round(((stats.value.cancelled_orders || 0) / total) * 100) : 0;
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
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

.dashboard {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
  font-family: 'Inter', sans-serif;
  color: #e2e8f0;
}

/* ── Header ─────────────────────────────── */
.dash-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 1rem;
  flex-wrap: wrap;
}

.dash-title {
  font-size: 1.5rem;
  font-weight: 700;
  color: #f1f5f9;
  margin-bottom: 0.2rem;
  letter-spacing: -0.02em;
}

.dash-sub {
  font-size: 0.85rem;
  color: #64748b;
}

.refresh-btn {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.55rem 1.15rem;
  background: #1e2235;
  border: 1px solid #2a2f45;
  border-radius: 10px;
  color: #94a3b8;
  font-size: 0.82rem;
  font-family: inherit;
  cursor: pointer;
  transition: all 0.2s;
}
.refresh-btn:hover:not(:disabled) {
  background: #252b40;
  color: #e2e8f0;
  border-color: #3a4060;
}
.refresh-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* ── KPI Grid ──────────────────────────── */
.kpi-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1rem;
}

.kpi-card {
  background: #13151e;
  border: 1px solid #1f2330;
  border-radius: 16px;
  padding: 1.35rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  position: relative;
  overflow: hidden;
  transition: border-color 0.25s, transform 0.25s;
}
.kpi-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: var(--c);
  opacity: 0.7;
}
.kpi-card:hover {
  border-color: #2a2f45;
  transform: translateY(-2px);
}

.kpi-left {
  display: flex;
  align-items: center;
  gap: 0.85rem;
}

.kpi-icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  background: color-mix(in srgb, var(--c) 10%, transparent);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
  color: var(--c);
  flex-shrink: 0;
}

.kpi-texts {
  min-width: 0;
}

.kpi-value {
  font-size: 1.45rem;
  font-weight: 700;
  color: #f1f5f9;
  line-height: 1.2;
}

.kpi-label {
  font-size: 0.78rem;
  color: #64748b;
  font-weight: 500;
}

.kpi-extra {
  display: flex;
  flex-wrap: wrap;
  gap: 0.4rem;
}

.kpi-sub {
  font-size: 0.7rem;
  color: #475569;
  background: #1a1e2e;
  padding: 0.2rem 0.55rem;
  border-radius: 6px;
}

.kpi-badges {
  display: flex;
  flex-wrap: wrap;
  gap: 0.4rem;
}

.kpi-badge {
  font-size: 0.68rem;
  font-weight: 600;
  padding: 0.2rem 0.6rem;
  border-radius: 6px;
  text-transform: capitalize;
}
.kpi-badge.pending {
  background: rgba(245, 158, 11, 0.12);
  color: #f59e0b;
}
.kpi-badge.processing {
  background: rgba(99, 102, 241, 0.12);
  color: #818cf8;
}
.kpi-badge.danger {
  background: rgba(239, 68, 68, 0.12);
  color: #f87171;
}
.kpi-badge.warn {
  background: rgba(249, 115, 22, 0.12);
  color: #fb923c;
}
.kpi-badge.ok {
  background: rgba(16, 185, 129, 0.12);
  color: #34d399;
}

/* ── Panel ──────────────────────────────── */
.panel {
  background: #13151e;
  border: 1px solid #1f2330;
  border-radius: 16px;
  padding: 1.5rem;
}

.panel-head {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 1.5rem;
  gap: 1rem;
  flex-wrap: wrap;
}

.panel-title {
  font-size: 0.95rem;
  font-weight: 600;
  color: #e2e8f0;
  margin-bottom: 0.15rem;
}

.panel-sub {
  font-size: 0.75rem;
  color: #475569;
}

.panel-badge {
  background: #1a1e2e;
  border: 1px solid #252a3a;
  padding: 0.3rem 0.75rem;
  border-radius: 8px;
  font-size: 0.7rem;
  color: #64748b;
  display: flex;
  align-items: center;
  gap: 0.35rem;
  white-space: nowrap;
}

.view-all {
  font-size: 0.78rem;
  color: #c8773a;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 0.3rem;
  transition: color 0.2s;
  white-space: nowrap;
  font-weight: 500;
}
.view-all:hover { color: #e8a76c; }

/* ── Empty State ────────────────────────── */
.empty-state {
  text-align: center;
  padding: 2.5rem 1rem;
  color: #334155;
}
.empty-state.small { padding: 2rem 1rem; }
.empty-state i {
  font-size: 1.8rem;
  display: block;
  margin-bottom: 0.6rem;
  opacity: 0.5;
}
.empty-state p { font-size: 0.82rem; }

/* ── Revenue Chart (Vertical Bars) ─────── */
.revenue-chart {
  padding: 0 0.5rem;
}

.chart-bars {
  display: flex;
  align-items: flex-end;
  gap: 6px;
  height: 200px;
}

.chart-bar-wrap {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  height: 100%;
  gap: 8px;
}

.chart-bar-track {
  flex: 1;
  width: 100%;
  max-width: 48px;
  background: #1a1e2e;
  border-radius: 6px 6px 0 0;
  position: relative;
  display: flex;
  align-items: flex-start;
  justify-content: center;
  overflow: hidden;
}

.chart-bar-fill {
  width: 100%;
  background: linear-gradient(180deg, #c8773a, #a05e28);
  border-radius: 6px 6px 0 0;
  position: relative;
  transition: height 0.8s cubic-bezier(0.22, 1, 0.36, 1);
  min-height: 4px;
}

.chart-tooltip {
  position: absolute;
  top: -30px;
  left: 50%;
  transform: translateX(-50%);
  background: #252b40;
  border: 1px solid #3a4060;
  color: #e2e8f0;
  font-size: 0.68rem;
  font-weight: 600;
  padding: 0.2rem 0.5rem;
  border-radius: 6px;
  white-space: nowrap;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.2s;
}
.chart-bar-wrap:hover .chart-tooltip { opacity: 1; }

.chart-bar-label {
  font-size: 0.65rem;
  color: #475569;
  font-weight: 500;
  text-align: center;
  flex-shrink: 0;
}

/* ── Mid / Bottom Rows ─────────────────── */
.mid-row,
.bottom-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.25rem;
}

/* ── Status List ───────────────────────── */
.status-list {
  display: flex;
  flex-direction: column;
  gap: 0.85rem;
}

.status-row {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.status-left {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  min-width: 95px;
}

.status-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  flex-shrink: 0;
}
.dot-pending { background: #f59e0b; }
.dot-processing { background: #6366f1; }
.dot-shipped { background: #a78bfa; }
.dot-delivered { background: #10b981; }
.dot-cancelled { background: #ef4444; }

.status-name {
  font-size: 0.8rem;
  color: #94a3b8;
  text-transform: capitalize;
}

.status-right {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  flex: 1;
}

.status-track {
  flex: 1;
  background: #1a1e2e;
  border-radius: 4px;
  height: 6px;
  overflow: hidden;
}

.status-fill {
  height: 100%;
  border-radius: 4px;
  transition: width 0.8s cubic-bezier(0.22, 1, 0.36, 1);
}
.fill-pending { background: #f59e0b; }
.fill-processing { background: #6366f1; }
.fill-shipped { background: #a78bfa; }
.fill-delivered { background: #10b981; }
.fill-cancelled { background: #ef4444; }

.status-count {
  font-size: 0.82rem;
  font-weight: 600;
  color: #cbd5e1;
  min-width: 24px;
  text-align: right;
}

/* ── Breakdown Cards ───────────────────── */
.breakdown-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.breakdown-card {
  background: #1a1e2e;
  border-radius: 14px;
  padding: 1.25rem;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
}
.breakdown-card i {
  font-size: 1.3rem;
}
.breakdown-card.completed i { color: #10b981; }
.breakdown-card.cancelled i { color: #ef4444; }

.breakdown-num {
  font-size: 1.6rem;
  font-weight: 700;
  color: #f1f5f9;
  line-height: 1;
}

.breakdown-label {
  font-size: 0.75rem;
  color: #64748b;
  font-weight: 500;
}

.breakdown-bar {
  width: 100%;
  height: 6px;
  background: #13151e;
  border-radius: 3px;
  overflow: hidden;
}

.breakdown-fill {
  height: 100%;
  background: #10b981;
  border-radius: 3px;
  transition: width 0.8s cubic-bezier(0.22, 1, 0.36, 1);
}
.breakdown-fill.red { background: #ef4444; }

.breakdown-pct {
  font-size: 0.75rem;
  font-weight: 700;
  color: #94a3b8;
}

/* ── Orders List ────────────────────────── */
.orders-list {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.order-row {
  display: flex;
  align-items: center;
  gap: 0.85rem;
  padding: 0.7rem 0.6rem;
  border-radius: 10px;
  transition: background 0.15s;
}
.order-row:hover { background: #1a1e2e; }

.order-avatar {
  width: 36px;
  height: 36px;
  background: linear-gradient(135deg, #c8773a, #e8a76c);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.8rem;
  font-weight: 700;
  color: white;
  flex-shrink: 0;
}

.order-info { flex: 1; min-width: 0; }
.order-id { font-size: 0.82rem; font-weight: 600; color: #cbd5e1; }
.order-user { font-size: 0.72rem; color: #475569; }

.order-right { text-align: right; flex-shrink: 0; }
.order-amount { font-size: 0.85rem; font-weight: 600; color: #e2e8f0; margin-bottom: 0.2rem; }

.order-badge {
  display: inline-block;
  font-size: 0.65rem;
  font-weight: 600;
  padding: 0.18rem 0.5rem;
  border-radius: 50px;
  text-transform: capitalize;
}
.badge-pending { background: rgba(245,158,11,0.15); color: #f59e0b; }
.badge-processing { background: rgba(99,102,241,0.15); color: #818cf8; }
.badge-shipped { background: rgba(167,139,250,0.15); color: #a78bfa; }
.badge-delivered { background: rgba(16,185,129,0.15); color: #34d399; }
.badge-cancelled { background: rgba(239,68,68,0.15); color: #f87171; }

/* ── Products List ──────────────────────── */
.products-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.product-row {
  display: flex;
  align-items: center;
  gap: 0.85rem;
}

.product-rank {
  width: 28px;
  height: 28px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 700;
  flex-shrink: 0;
  background: #1a1e2e;
  color: #475569;
}
.product-rank.gold { background: rgba(245,158,11,0.15); color: #f59e0b; }
.product-rank.silver { background: rgba(148,163,184,0.12); color: #94a3b8; }
.product-rank.bronze { background: rgba(180,120,75,0.12); color: #b47c4b; }

.product-info { flex: 1; min-width: 0; }
.product-name {
  font-size: 0.82rem;
  color: #cbd5e1;
  font-weight: 500;
  margin-bottom: 0.45rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.product-bar {
  background: #1a1e2e;
  border-radius: 4px;
  height: 5px;
  overflow: hidden;
}
.product-bar-fill {
  height: 100%;
  background: linear-gradient(90deg, #c8773a, #f59e0b);
  border-radius: 4px;
  transition: width 0.8s cubic-bezier(0.22, 1, 0.36, 1);
}

.product-sold { text-align: right; flex-shrink: 0; }
.sold-num { display: block; font-size: 0.9rem; font-weight: 700; color: #f1f5f9; }
.sold-label { font-size: 0.65rem; color: #475569; }

/* ── Responsive ─────────────────────────── */
@media (max-width: 1100px) {
  .kpi-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
  .mid-row,
  .bottom-row { grid-template-columns: 1fr; }
  .chart-bars { height: 160px; }
  .chart-bar-track { max-width: 36px; }
  .chart-bar-label { font-size: 0.58rem; }
}

@media (max-width: 580px) {
  .kpi-grid { grid-template-columns: 1fr; }
  .dash-header { flex-direction: column; }
  .breakdown-grid { grid-template-columns: 1fr; }
  .chart-bars { height: 130px; gap: 4px; }
  .chart-tooltip { display: none; }
}
</style>