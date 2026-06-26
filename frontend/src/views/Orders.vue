<template>
  <div class="page">
    <div class="page-inner">
      <h1 class="page-title">📋 My Orders</h1>

      <div v-if="loading" class="loading-state"><i class="fas fa-spinner fa-spin"></i></div>

      <div v-else-if="!orders.length" class="empty">
        <i class="fas fa-receipt"></i>
        <p>You haven't placed any orders yet.</p>
        <router-link to="/shop" class="shop-btn">Start Shopping</router-link>
      </div>

      <div v-else class="orders-list">
        <div v-for="order in orders" :key="order.id" class="order-card" @click="go(order.id)">
          <div class="order-left">
            <div class="order-icon"><i class="fas fa-box"></i></div>
            <div class="order-info">
              <p class="order-num">{{ order.order_number }}</p>
              <p class="order-date">{{ formatDate(order.created_at) }}</p>
              <p class="order-items-count">{{ order.items?.length || 0 }} item{{ order.items?.length !== 1 ? 's' : '' }}</p>
            </div>
          </div>
          <div class="order-right">
            <p class="order-total">${{ Number(order.total).toFixed(2) }}</p>
            <span class="status-badge" :class="`status-${order.status}`">{{ order.status }}</span>
            <i class="fas fa-chevron-right chevron"></i>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="lastPage > 1" class="pagination">
          <button @click="fetchOrders(currentPage - 1)" :disabled="currentPage <= 1">
            <i class="fas fa-chevron-left"></i>
          </button>
          <button v-for="p in lastPage" :key="p" @click="fetchOrders(p)" :class="{ active: p === currentPage }">{{ p }}</button>
          <button @click="fetchOrders(currentPage + 1)" :disabled="currentPage >= lastPage">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'

const router      = useRouter()
const orders      = ref([])
const loading     = ref(true)
const currentPage = ref(1)
const lastPage    = ref(1)

const go = (id) => router.push(`/orders/${id}`)

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}

async function fetchOrders(page = 1) {
  loading.value = true
  try {
    const { data } = await api.get('/orders', { params: { page } })
    orders.value  = data.data
    currentPage.value = data.current_page
    lastPage.value    = data.last_page
  } finally { loading.value = false }
}

onMounted(() => fetchOrders())
</script>

<style scoped>
.page { background: #fdf8f3; min-height: 100vh; padding: 2.5rem 0; }
.page-inner { max-width: 800px; margin: 0 auto; padding: 0 1.5rem; }
.page-title { font-family: 'Playfair Display', serif; font-size: 2rem; color: #2d1b0e; margin-bottom: 2rem; }

.loading-state { display: flex; justify-content: center; align-items: center; padding: 5rem; }
.loading-state i { font-size: 2rem; color: #c8773a; }

.empty { text-align: center; padding: 5rem 1rem; color: #9a7560; }
.empty i { font-size: 3rem; display: block; margin-bottom: 1rem; color: #e8d5c0; }
.empty p { font-size: 1rem; margin-bottom: 1.5rem; }
.shop-btn { display: inline-block; padding: 0.7rem 2rem; background: linear-gradient(135deg, #c8773a, #e8945a); color: white; text-decoration: none; border-radius: 50px; font-weight: 600; }

.orders-list { display: flex; flex-direction: column; gap: 0.75rem; }

.order-card { background: white; border: 1px solid #f0e6d8; border-radius: 14px; padding: 1.25rem; display: flex; align-items: center; justify-content: space-between; cursor: pointer; transition: all 0.2s; gap: 1rem; }
.order-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(200,119,58,0.1); border-color: #e8c9a0; }

.order-left  { display: flex; align-items: center; gap: 1rem; }
.order-icon  { width: 44px; height: 44px; border-radius: 12px; background: #fdf0e6; display: flex; align-items: center; justify-content: center; color: #c8773a; font-size: 1.1rem; flex-shrink: 0; }
.order-num   { font-weight: 600; font-size: 0.9rem; color: #2d1b0e; margin-bottom: 0.2rem; }
.order-date  { font-size: 0.78rem; color: #9a7560; margin-bottom: 0.15rem; }
.order-items-count { font-size: 0.75rem; color: #9a7560; }

.order-right  { display: flex; align-items: center; gap: 1rem; flex-shrink: 0; }
.order-total  { font-size: 1.05rem; font-weight: 700; color: #c8773a; }

.status-badge { font-size: 0.72rem; font-weight: 600; padding: 0.25rem 0.7rem; border-radius: 50px; text-transform: capitalize; }
.status-pending    { background: rgba(245,158,11,0.12); color: #d97706; }
.status-processing { background: rgba(99,102,241,0.12); color: #6366f1; }
.status-shipped    { background: rgba(139,92,246,0.12); color: #7c3aed; }
.status-delivered  { background: rgba(16,185,129,0.12); color: #059669; }
.status-cancelled  { background: rgba(239,68,68,0.12); color: #dc2626; }

.chevron { color: #c8a07a; font-size: 0.8rem; }

.pagination { display: flex; justify-content: center; gap: 0.4rem; margin-top: 1.5rem; }
.pagination button { width: 36px; height: 36px; border-radius: 8px; border: 1.5px solid #e8d5c0; background: white; color: #6b4c30; cursor: pointer; font-size: 0.85rem; transition: all 0.15s; }
.pagination button:hover { border-color: #c8773a; color: #c8773a; }
.pagination button.active { background: #c8773a; border-color: #c8773a; color: white; }
.pagination button:disabled { opacity: 0.4; cursor: not-allowed; }
</style>
