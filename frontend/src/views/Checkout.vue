<template>
  <div class="page">
    <div class="page-inner">
      <h1 class="page-title">Checkout</h1>

      <div v-if="cart.loading" class="loading-state"><i class="fas fa-spinner fa-spin"></i></div>

      <div v-else-if="!cart.items.length" class="empty">
        <i class="fas fa-shopping-cart"></i>
        <p>Your cart is empty.</p>
        <router-link to="/shop" class="shop-btn">Browse Products</router-link>
      </div>

      <div v-else class="checkout-layout">
        <!-- Left: Form -->
        <div class="form-col">
          <!-- Shipping Address -->
          <div class="section-card">
            <h2 class="section-title"><i class="fas fa-map-marker-alt"></i> Shipping Address</h2>
            <div class="fields-grid">
              <div class="field">
                <label>Full Name *</label>
                <input v-model="form.shipping_address.name" type="text" placeholder="Jane Doe" required />
              </div>
              <div class="field">
                <label>Phone *</label>
                <input v-model="form.shipping_address.phone" type="tel" placeholder="+855 xx xxx xxx" required />
              </div>
              <div class="field full">
                <label>Address *</label>
                <input v-model="form.shipping_address.address" type="text" placeholder="Street address" required />
              </div>
              <div class="field">
                <label>City *</label>
                <input v-model="form.shipping_address.city" type="text" placeholder="Phnom Penh" required />
              </div>
              <div class="field">
                <label>Postal Code</label>
                <input v-model="form.shipping_address.postal_code" type="text" placeholder="12000" />
              </div>
              <div class="field full">
                <label>Notes (optional)</label>
                <input v-model="form.notes" type="text" placeholder="Leave at door, etc." />
              </div>
            </div>
          </div>

          <!-- Payment Method -->
          <div class="section-card">
            <h2 class="section-title"><i class="fas fa-credit-card"></i> Payment Method</h2>
            <div class="payment-grid">
              <label v-for="m in paymentMethods" :key="m.value" class="payment-option"
                :class="{ active: form.payment_method === m.value }">
                <input type="radio" v-model="form.payment_method" :value="m.value" />
                <span class="pay-icon">{{ m.icon }}</span>
                <span class="pay-label">{{ m.label }}</span>
              </label>
            </div>
          </div>
        </div>

        <!-- Right: Summary -->
        <div class="summary-col">
          <div class="summary-card">
            <h3>Order Review</h3>
            <div class="order-items">
              <div v-for="item in cart.items" :key="item.id" class="order-item">
                <div class="oi-img">
                  <img v-if="item.product?.image" :src="`http://localhost:8000/storage/${item.product.image}`" />
                  <span v-else>🎂</span>
                </div>
                <div class="oi-info">
                  <p class="oi-name">{{ item.product?.name }}</p>
                  <p class="oi-qty">Qty: {{ item.quantity }}</p>
                </div>
                <p class="oi-price">${{ (item.product?.price * item.quantity).toFixed(2) }}</p>
              </div>
            </div>

            <div class="summary-rows">
              <div class="summary-row"><span>Subtotal</span><span>${{ cart.total }}</span></div>
              <div class="summary-row"><span>Shipping</span><span>$5.00</span></div>
              <div class="summary-row"><span>Tax (10%)</span><span>${{ (cart.total * 0.1).toFixed(2) }}</span></div>
              <div class="summary-row total">
                <span>Total</span>
                <span>${{ (Number(cart.total) + 5 + cart.total * 0.1).toFixed(2) }}</span>
              </div>
            </div>

            <div v-if="error" class="error-msg">
              <i class="fas fa-exclamation-circle"></i> {{ error }}
            </div>

            <button @click="placeOrder" :disabled="placing" class="place-btn">
              <i :class="placing ? 'fas fa-spinner fa-spin' : 'fas fa-check'"></i>
              {{ placing ? 'Placing Order...' : 'Place Order' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'
import { cart } from '../stores/cart'
import { auth } from '../stores/auth'

const router  = useRouter()
const placing = ref(false)
const error   = ref(null)

const form = ref({
  shipping_address: { name: '', phone: '', address: '', city: '', postal_code: '' },
  payment_method: 'cod',
  notes: '',
})

const paymentMethods = [
  { value: 'aba',   icon: '🏦', label: 'ABA Pay' },
  { value: 'acleda', icon: '🏛️', label: 'ACLEDA Pay' },
  { value: 'card',  icon: '💳', label: 'Visa / MasterCard' },
  { value: 'cod',   icon: '💵', label: 'Cash on Delivery' },
]

async function placeOrder() {
  const { name, phone, address, city } = form.value.shipping_address
  if (!name || !phone || !address || !city) { error.value = 'Please fill all required fields.'; return }
  placing.value = true
  error.value = null
  try {
    const { data } = await api.post('/checkout', form.value)
    cart.items = []; cart.total = 0; cart.count = 0
    router.push(`/orders/${data.order.id}?success=1`)
  } catch (e) {
    error.value = e.response?.data?.message || 'Checkout failed. Please try again.'
  } finally { placing.value = false }
}

onMounted(() => {
  if (!auth.isLoggedIn) { router.push('/login'); return }
  cart.fetch()
  const user = auth.user
  if (user?.name) form.value.shipping_address.name = user.name
})
</script>

<style scoped>
.page { background: #fdf8f3; min-height: 100vh; padding: 2.5rem 0; }
.page-inner { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; }
.page-title { font-family: 'Playfair Display', serif; font-size: 2rem; color: #2d1b0e; margin-bottom: 2rem; }

.loading-state { display: flex; justify-content: center; align-items: center; padding: 5rem; }
.loading-state i { font-size: 2rem; color: #c8773a; }
.empty { text-align: center; padding: 5rem 1rem; color: #9a7560; }
.empty i { font-size: 3rem; display: block; margin-bottom: 1rem; color: #e8d5c0; }
.empty p { margin-bottom: 1.5rem; }
.shop-btn { display: inline-block; padding: 0.7rem 2rem; background: linear-gradient(135deg, #c8773a, #e8945a); color: white; text-decoration: none; border-radius: 50px; font-weight: 600; }

.checkout-layout { display: grid; grid-template-columns: 1fr 360px; gap: 2rem; align-items: start; }

.section-card { background: white; border: 1px solid #f0e6d8; border-radius: 16px; padding: 1.5rem; margin-bottom: 1.25rem; }
.section-title { font-size: 1rem; font-weight: 600; color: #2d1b0e; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.6rem; }
.section-title i { color: #c8773a; }

.fields-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.field { display: flex; flex-direction: column; gap: 0.35rem; }
.field.full { grid-column: 1 / -1; }
.field label { font-size: 0.8rem; font-weight: 500; color: #4a3220; }
.field input { padding: 0.7rem 0.9rem; border: 1.5px solid #e8d5c0; border-radius: 10px; font-size: 0.875rem; color: #2d1b0e; outline: none; font-family: 'Inter', sans-serif; background: white; transition: border-color 0.2s; }
.field input:focus { border-color: #c8773a; box-shadow: 0 0 0 3px rgba(200,119,58,0.1); }
.field input::placeholder { color: #c4a07a; }

/* Payment */
.payment-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
.payment-option { display: flex; align-items: center; gap: 0.75rem; padding: 0.85rem 1rem; border: 2px solid #f0e6d8; border-radius: 12px; cursor: pointer; transition: all 0.2s; }
.payment-option input { display: none; }
.payment-option.active { border-color: #c8773a; background: #fdf5ef; }
.pay-icon { font-size: 1.4rem; }
.pay-label { font-size: 0.85rem; font-weight: 500; color: #4a3220; }

/* Summary */
.summary-card { background: white; border: 1px solid #f0e6d8; border-radius: 16px; padding: 1.5rem; position: sticky; top: 90px; }
.summary-card h3 { font-family: 'Playfair Display', serif; font-size: 1.2rem; color: #2d1b0e; margin-bottom: 1.25rem; }

.order-items { display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1.25rem; border-bottom: 1px solid #f0e6d8; padding-bottom: 1.25rem; }
.order-item { display: flex; align-items: center; gap: 0.75rem; }
.oi-img { width: 48px; height: 48px; border-radius: 8px; overflow: hidden; background: linear-gradient(135deg, #fdf0e6, #f5d5b0); display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.2rem; }
.oi-img img { width: 100%; height: 100%; object-fit: cover; }
.oi-info { flex: 1; min-width: 0; }
.oi-name { font-size: 0.82rem; font-weight: 500; color: #2d1b0e; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.oi-qty  { font-size: 0.72rem; color: #9a7560; }
.oi-price { font-size: 0.85rem; font-weight: 700; color: #c8773a; white-space: nowrap; }

.summary-rows { display: flex; flex-direction: column; gap: 0.65rem; margin-bottom: 1.25rem; }
.summary-row { display: flex; justify-content: space-between; font-size: 0.875rem; color: #6b4c30; }
.summary-row.total { font-size: 1.05rem; font-weight: 700; color: #2d1b0e; padding-top: 0.75rem; border-top: 1px solid #f0e6d8; }

.error-msg { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; border-radius: 10px; padding: 0.75rem 1rem; font-size: 0.85rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem; }

.place-btn { width: 100%; padding: 0.9rem; background: linear-gradient(135deg, #c8773a, #e8945a); color: white; border: none; border-radius: 50px; font-size: 0.95rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s; }
.place-btn:hover:not(:disabled) { filter: brightness(1.08); transform: translateY(-1px); }
.place-btn:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

@media (max-width: 768px) {
  .checkout-layout { grid-template-columns: 1fr; }
  .fields-grid { grid-template-columns: 1fr; }
  .payment-grid { grid-template-columns: 1fr; }
}
</style>
