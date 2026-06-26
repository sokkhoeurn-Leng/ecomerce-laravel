<template>
  <div class="page">
    <div class="page-inner">
      <nav class="breadcrumb">
        <router-link to="/">Home</router-link><span>/</span>
        <router-link to="/orders">My Orders</router-link><span>/</span>
        <span class="current">{{ order?.order_number }}</span>
      </nav>

      <div v-if="loading" class="loading-state"><i class="fas fa-spinner fa-spin"></i></div>

      <div v-else-if="!order" class="empty">
        <i class="fas fa-receipt"></i>
        <p>Order not found.</p>
        <router-link to="/orders">Back to Orders</router-link>
      </div>

      <div v-else>
        <!-- Success Banner -->
        <div v-if="isSuccess" class="success-banner">
          <i class="fas fa-check-circle"></i>
          <div>
            <p class="sb-title">Order Placed Successfully! 🎂</p>
            <p class="sb-sub">We'll notify you when your order is confirmed and shipped.</p>
          </div>
        </div>

        <div class="order-header">
          <div>
            <h1 class="order-num">{{ order.order_number }}</h1>
            <p class="order-date">Placed on {{ formatDate(order.created_at) }}</p>
          </div>
          <div class="header-right">
            <span class="status-badge" :class="`status-${order.status}`">{{ order.status }}</span>
            <button v-if="order.status === 'pending'" @click="cancelOrder" class="cancel-btn" :disabled="cancelling">
              <i :class="cancelling ? 'fas fa-spinner fa-spin' : 'fas fa-times'"></i>
              {{ cancelling ? 'Cancelling...' : 'Cancel Order' }}
            </button>
          </div>
        </div>

        <!-- Status Timeline -->
        <div class="timeline">
          <div v-for="step in statusSteps" :key="step.key"
            class="step" :class="{ done: stepDone(step.key), current: order.status === step.key }">
            <div class="step-dot"><i :class="step.icon"></i></div>
            <div class="step-label">{{ step.label }}</div>
          </div>
        </div>

        <div class="detail-layout">
          <!-- Items -->
          <div class="items-card">
            <h3>Items Ordered</h3>
            <div class="items-list">
              <div v-for="item in order.items" :key="item.id" class="item-row">
                <div class="item-info">
                  <p class="item-name">{{ item.product_name }}</p>
                  <p class="item-meta">Qty: {{ item.quantity }} × ${{ Number(item.price).toFixed(2) }}</p>
                </div>
                <p class="item-sub">${{ Number(item.subtotal).toFixed(2) }}</p>
              </div>
            </div>
            <div class="price-breakdown">
              <div class="pb-row"><span>Subtotal</span><span>${{ Number(order.subtotal).toFixed(2) }}</span></div>
              <div class="pb-row"><span>Shipping</span><span>${{ Number(order.shipping).toFixed(2) }}</span></div>
              <div class="pb-row"><span>Tax</span><span>${{ Number(order.tax).toFixed(2) }}</span></div>
              <div class="pb-row total"><span>Total</span><span>${{ Number(order.total).toFixed(2) }}</span></div>
            </div>
          </div>

          <!-- Info Cards -->
          <div class="info-col">
            <div class="info-card">
              <h4><i class="fas fa-map-marker-alt"></i> Shipping Address</h4>
              <p>{{ order.shipping_address?.name }}</p>
              <p>{{ order.shipping_address?.phone }}</p>
              <p>{{ order.shipping_address?.address }}</p>
              <p>{{ order.shipping_address?.city }}</p>
            </div>
            <div class="info-card">
              <h4><i class="fas fa-credit-card"></i> Payment</h4>
              <p class="payment-method">{{ paymentLabel(order.payment_method) }}</p>
              <span class="pay-status" :class="`pay-${order.payment_status}`">{{ order.payment_status }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="toast" class="toast-fixed" :class="toast.type">
      <i :class="toast.type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'"></i>
      {{ toast.message }}
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../services/api'

const route     = useRoute()
const order     = ref(null)
const loading   = ref(true)
const cancelling = ref(false)
const toast     = ref(null)

const isSuccess = computed(() => route.query.success === '1')

const statusSteps = [
  { key: 'pending',    label: 'Pending',    icon: 'fas fa-clock' },
  { key: 'processing', label: 'Processing', icon: 'fas fa-cog' },
  { key: 'shipped',    label: 'Shipped',    icon: 'fas fa-truck' },
  { key: 'delivered',  label: 'Delivered',  icon: 'fas fa-check-circle' },
]

const stepOrder = ['pending', 'processing', 'shipped', 'delivered']

function stepDone(key) {
  if (order.value?.status === 'cancelled') return false
  return stepOrder.indexOf(order.value?.status) >= stepOrder.indexOf(key)
}

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
}

function paymentLabel(m) {
  return { aba: 'ABA Pay', acleda: 'ACLEDA Pay', card: 'Visa / MasterCard', cod: 'Cash on Delivery' }[m] || m
}

function showToast(msg, type = 'success') {
  toast.value = { message: msg, type }
  setTimeout(() => toast.value = null, 3000)
}

async function cancelOrder() {
  cancelling.value = true
  try {
    await api.put(`/orders/${order.value.id}/cancel`)
    order.value.status = 'cancelled'
    showToast('Order cancelled.')
  } catch {
    showToast('Cannot cancel this order.', 'error')
  } finally { cancelling.value = false }
}

onMounted(async () => {
  try {
    const { data } = await api.get(`/orders/${route.params.id}`)
    order.value = data
  } catch { order.value = null }
  finally { loading.value = false }
})
</script>

<style scoped>
.page { background: #fdf8f3; min-height: 100vh; padding: 2.5rem 0; }
.page-inner { max-width: 1000px; margin: 0 auto; padding: 0 1.5rem; }

.loading-state { display: flex; justify-content: center; align-items: center; padding: 5rem; }
.loading-state i { font-size: 2rem; color: #c8773a; }

.empty { text-align: center; padding: 5rem 1rem; color: #9a7560; }
.empty a { color: #c8773a; font-weight: 600; text-decoration: none; }

.breadcrumb { display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; color: #9a7560; margin-bottom: 2rem; }
.breadcrumb a { color: #c8773a; text-decoration: none; }
.breadcrumb .current { color: #2d1b0e; font-weight: 500; }

/* Success Banner */
.success-banner { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 14px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 1rem; }
.success-banner i { color: #16a34a; font-size: 1.5rem; flex-shrink: 0; margin-top: 0.1rem; }
.sb-title { font-weight: 600; color: #15803d; margin-bottom: 0.2rem; }
.sb-sub   { font-size: 0.85rem; color: #4ade80; }

/* Order Header */
.order-header { display: flex; align-items: flex-start; justify-content: space-between; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem; }
.order-num  { font-family: 'Playfair Display', serif; font-size: 1.6rem; color: #2d1b0e; margin-bottom: 0.25rem; }
.order-date { font-size: 0.85rem; color: #9a7560; }
.header-right { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; }

.status-badge { font-size: 0.8rem; font-weight: 600; padding: 0.3rem 0.85rem; border-radius: 50px; text-transform: capitalize; }
.status-pending    { background: rgba(245,158,11,0.12); color: #d97706; }
.status-processing { background: rgba(99,102,241,0.12); color: #6366f1; }
.status-shipped    { background: rgba(139,92,246,0.12); color: #7c3aed; }
.status-delivered  { background: rgba(16,185,129,0.12); color: #059669; }
.status-cancelled  { background: rgba(239,68,68,0.12); color: #dc2626; }

.cancel-btn { display: flex; align-items: center; gap: 0.5rem; padding: 0.55rem 1.1rem; border: 1.5px solid #fecaca; border-radius: 50px; background: white; color: #ef4444; font-size: 0.82rem; font-weight: 600; cursor: pointer; transition: all 0.2s; }
.cancel-btn:hover:not(:disabled) { background: #fef2f2; }
.cancel-btn:disabled { opacity: 0.6; cursor: not-allowed; }

/* Timeline */
.timeline { display: flex; align-items: flex-start; margin-bottom: 2.5rem; position: relative; }
.timeline::before { content: ''; position: absolute; top: 20px; left: 20px; right: 20px; height: 2px; background: #f0e6d8; z-index: 0; }
.step { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 0.5rem; position: relative; z-index: 1; }
.step-dot { width: 40px; height: 40px; border-radius: 50%; background: #f0e6d8; border: 2px solid #e8d5c0; display: flex; align-items: center; justify-content: center; color: #c8a07a; font-size: 0.9rem; transition: all 0.3s; }
.step.done .step-dot, .step.current .step-dot { background: #c8773a; border-color: #c8773a; color: white; }
.step-label { font-size: 0.72rem; font-weight: 500; color: #9a7560; text-transform: capitalize; }
.step.done .step-label, .step.current .step-label { color: #c8773a; font-weight: 600; }

/* Detail Layout */
.detail-layout { display: grid; grid-template-columns: 1fr 280px; gap: 1.5rem; align-items: start; }

.items-card { background: white; border: 1px solid #f0e6d8; border-radius: 16px; padding: 1.5rem; }
.items-card h3 { font-family: 'Playfair Display', serif; font-size: 1.1rem; color: #2d1b0e; margin-bottom: 1.25rem; }
.items-list { display: flex; flex-direction: column; gap: 0.85rem; margin-bottom: 1.25rem; border-bottom: 1px solid #f0e6d8; padding-bottom: 1.25rem; }
.item-row   { display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
.item-name  { font-size: 0.875rem; font-weight: 500; color: #2d1b0e; margin-bottom: 0.2rem; }
.item-meta  { font-size: 0.78rem; color: #9a7560; }
.item-sub   { font-size: 0.9rem; font-weight: 600; color: #c8773a; white-space: nowrap; }
.price-breakdown { display: flex; flex-direction: column; gap: 0.5rem; }
.pb-row { display: flex; justify-content: space-between; font-size: 0.875rem; color: #6b4c30; }
.pb-row.total { font-size: 1rem; font-weight: 700; color: #2d1b0e; padding-top: 0.5rem; border-top: 1px solid #f0e6d8; }

.info-col { display: flex; flex-direction: column; gap: 1rem; }
.info-card { background: white; border: 1px solid #f0e6d8; border-radius: 14px; padding: 1.25rem; }
.info-card h4 { font-size: 0.82rem; font-weight: 600; color: #9a7560; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.85rem; display: flex; align-items: center; gap: 0.5rem; }
.info-card h4 i { color: #c8773a; }
.info-card p { font-size: 0.875rem; color: #4a3220; line-height: 1.7; }
.payment-method { font-weight: 500; color: #2d1b0e; margin-bottom: 0.5rem; }
.pay-status { font-size: 0.72rem; font-weight: 600; padding: 0.2rem 0.65rem; border-radius: 50px; text-transform: capitalize; }
.pay-paid    { background: rgba(16,185,129,0.12); color: #059669; }
.pay-pending { background: rgba(245,158,11,0.12); color: #d97706; }
.pay-failed  { background: rgba(239,68,68,0.12); color: #dc2626; }

.toast-fixed { position: fixed; bottom: 2rem; right: 2rem; z-index: 9999; display: flex; align-items: center; gap: 0.5rem; padding: 0.85rem 1.25rem; border-radius: 12px; font-size: 0.875rem; font-weight: 500; box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
.toast-fixed.success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
.toast-fixed.error   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

@media (max-width: 768px) {
  .detail-layout { grid-template-columns: 1fr; }
  .timeline::before { display: none; }
}
</style>
