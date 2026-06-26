<template>
  <div class="page">
    <!-- Filters -->
    <div class="filters">
      <div class="search-box">
        <i class="fas fa-search search-icon"></i>
        <input
          v-model="search"
          type="text"
          placeholder="Search order number..."
          @input="debouncedSearch"
        />
      </div>
      <select
        v-model="statusFilter"
        @change="fetchOrders(1)"
        class="filter-select"
      >
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="processing">Processing</option>
        <option value="shipped">Shipped</option>
        <option value="delivered">Delivered</option>
        <option value="cancelled">Cancelled</option>
      </select>
      <select
        v-model="paymentFilter"
        @change="fetchOrders(1)"
        class="filter-select"
      >
        <option value="">All Payments</option>
        <option value="paid">Paid</option>
        <option value="pending">Pending</option>
        <option value="failed">Failed</option>
        <option value="refunded">Refunded</option>
      </select>
      <div class="date-group">
        <input
          v-model="dateFrom"
          type="date"
          @change="fetchOrders(1)"
          class="filter-date"
        />
        <span class="date-sep">→</span>
        <input
          v-model="dateTo"
          type="date"
          @change="fetchOrders(1)"
          class="filter-date"
        />
      </div>
    </div>

    <!-- Table -->
    <div class="table-card">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Order</th>
              <th>Customer</th>
              <th>Items</th>
              <th>Total</th>
              <th>Status</th>
              <th>Payment</th>
              <th>Date</th>
              <th class="th-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="8" class="td-center">
                <i class="fas fa-spinner fa-spin spinner"></i>
              </td>
            </tr>
            <tr v-else-if="!orders.length">
              <td colspan="8" class="td-center td-empty">
                <i class="fas fa-shopping-cart empty-icon"></i>
                <p>No orders found</p>
              </td>
            </tr>
            <tr v-for="order in orders" :key="order.id" v-else>
              <td>
                <span class="order-num">{{ order.order_number }}</span>
              </td>
              <td>
                <p class="customer-name">{{ order.user?.name || "Guest" }}</p>
                <p class="customer-email">{{ order.user?.email || "" }}</p>
              </td>
              <td>
                <span class="items-count">{{ order.items_count || "—" }}</span>
              </td>
              <td>
                <span class="order-total">{{
                  formatCurrency(order.total)
                }}</span>
              </td>
              <td>
                <span class="status-badge" :class="`status-${order.status}`">{{
                  order.status
                }}</span>
              </td>
              <td>
                <span
                  class="status-badge"
                  :class="`payment-${order.payment_status}`"
                  >{{ order.payment_status }}</span
                >
              </td>
              <td>
                <span class="order-date">{{
                  formatDate(order.created_at)
                }}</span>
              </td>
              <td class="td-right">
                <button class="action-btn action-view" title="View">
                  <i class="fas fa-eye"></i>
                </button>
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
import { ref, onMounted } from "vue";
import StatusBadge from "./common/StatusBadge.vue";
import Pagination from "./common/Pagination.vue";
import { apiGet } from "../api.js";

const orders = ref([]);
const pagination = ref({});
const loading = ref(false);
const search = ref("");
const statusFilter = ref("");
const paymentFilter = ref("");
const dateFrom = ref("");
const dateTo = ref("");

let searchTimeout;
function debouncedSearch() {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => fetchOrders(1), 300);
}

async function fetchOrders(page = 1) {
  loading.value = true;
  try {
    const params = new URLSearchParams({
      page,
      search: search.value,
      status: statusFilter.value,
      payment_status: paymentFilter.value,
      date_from: dateFrom.value,
      date_to: dateTo.value,
    });
    const data = await apiGet(`/admin/orders?${params}`);
    orders.value = data.data;
    pagination.value = {
      current_page: data.current_page,
      last_page: data.last_page,
      per_page: data.per_page,
      total: data.total,
      from: data.from,
      to: data.to,
    };
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
}

function formatCurrency(v) {
  return new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: "USD",
  }).format(v);
}
function formatDate(d) {
  return new Date(d).toLocaleDateString("en-US", {
    month: "short",
    day: "numeric",
    year: "numeric",
  });
}

onMounted(() => fetchOrders());
</script>

<style scoped>
.page {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

/* Filters */
.filters {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.search-box {
  position: relative;
}
.search-icon {
  position: absolute;
  left: 0.85rem;
  top: 50%;
  transform: translateY(-50%);
  color: #4a5068;
  font-size: 0.8rem;
  pointer-events: none;
}
.search-box input {
  background: #1a1e2e;
  border: 1px solid #2a2f45;
  border-radius: 10px;
  padding: 0.6rem 1rem 0.6rem 2.4rem;
  color: #e2e8f0;
  font-size: 0.875rem;
  width: 240px;
  outline: none;
  transition: border-color 0.2s;
}
.search-box input:focus {
  border-color: #c8773a;
}
.search-box input::placeholder {
  color: #3a4060;
}

.filter-select {
  background: #1a1e2e;
  border: 1px solid #2a2f45;
  border-radius: 10px;
  padding: 0.6rem 1rem;
  color: #e2e8f0;
  font-size: 0.875rem;
  outline: none;
  cursor: pointer;
}
.filter-select:focus {
  border-color: #c8773a;
}

.date-group {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.filter-date {
  background: #1a1e2e;
  border: 1px solid #2a2f45;
  border-radius: 10px;
  padding: 0.6rem 0.85rem;
  color: #e2e8f0;
  font-size: 0.82rem;
  outline: none;
}
.filter-date:focus {
  border-color: #c8773a;
}
.date-sep {
  color: #4a5068;
  font-size: 0.8rem;
}

/* Table */
.table-card {
  background: #13151e;
  border: 1px solid #1f2330;
  border-radius: 16px;
  overflow: hidden;
}
.table-wrap {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}
thead tr {
  border-bottom: 1px solid #1f2330;
}
th {
  text-align: left;
  font-size: 0.72rem;
  font-weight: 600;
  color: #4a5068;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  padding: 0.9rem 1.25rem;
  white-space: nowrap;
}
.th-right {
  text-align: right;
}

tbody tr {
  border-bottom: 1px solid #1a1e2e;
  transition: background 0.15s;
}
tbody tr:last-child {
  border-bottom: none;
}
tbody tr:hover {
  background: #1a1e2e;
}

td {
  padding: 1rem 1.25rem;
  font-size: 0.875rem;
  color: #cbd5e1;
  vertical-align: middle;
}
.td-right {
  text-align: right;
}
.td-center {
  text-align: center;
  padding: 3rem 1rem;
}
.td-empty {
  color: #3a4060;
}
.empty-icon {
  font-size: 2rem;
  display: block;
  margin-bottom: 0.75rem;
}
.spinner {
  font-size: 1.5rem;
  color: #c8773a;
}

/* Cells */
.order-num {
  font-weight: 600;
  color: #f5a85a;
  font-size: 0.82rem;
}
.customer-name {
  font-weight: 500;
  color: #e2e8f0;
  margin-bottom: 0.15rem;
}
.customer-email {
  font-size: 0.75rem;
  color: #4a5068;
}
.items-count {
  color: #94a3b8;
}
.order-total {
  font-weight: 600;
  color: #e2e8f0;
}
.order-date {
  font-size: 0.82rem;
  color: #64748b;
  white-space: nowrap;
}

/* Status badges */
.status-badge {
  display: inline-block;
  padding: 0.25rem 0.7rem;
  border-radius: 50px;
  font-size: 0.72rem;
  font-weight: 600;
  text-transform: capitalize;
}
.status-pending {
  background: rgba(245, 158, 11, 0.12);
  color: #fbbf24;
}
.status-processing {
  background: rgba(99, 102, 241, 0.12);
  color: #818cf8;
}
.status-shipped {
  background: rgba(167, 139, 250, 0.12);
  color: #a78bfa;
}
.status-delivered {
  background: rgba(16, 185, 129, 0.12);
  color: #34d399;
}
.status-cancelled {
  background: rgba(239, 68, 68, 0.12);
  color: #f87171;
}
.payment-paid {
  background: rgba(16, 185, 129, 0.12);
  color: #34d399;
}
.payment-pending {
  background: rgba(245, 158, 11, 0.12);
  color: #fbbf24;
}
.payment-failed {
  background: rgba(239, 68, 68, 0.12);
  color: #f87171;
}
.payment-refunded {
  background: rgba(100, 116, 139, 0.12);
  color: #94a3b8;
}

/* Action */
.action-btn {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  border: 1px solid #2a2f45;
  background: #1e2235;
  color: #6b7280;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 0.78rem;
  transition: all 0.18s;
}
.action-view:hover {
  border-color: #f59e0b;
  color: #fbbf24;
  background: rgba(245, 158, 11, 0.1);
}
</style>
