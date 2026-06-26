<template>
  <div class="page">
    <div class="page-inner">
      <h1 class="page-title">🛒 Shopping Cart</h1>

      <div v-if="cart.loading" class="loading-state"><i class="fas fa-spinner fa-spin"></i></div>

      <div v-else-if="!cart.items.length" class="empty">
        <i class="fas fa-shopping-cart"></i>
        <p>Your cart is empty.</p>
        <router-link to="/shop" class="shop-btn">Browse Products</router-link>
      </div>

      <div v-else class="layout">
        <!-- Items -->
        <div class="items-col">
          <div v-for="item in cart.items" :key="item.id" class="cart-item">
            <div class="item-img">
              <img v-if="item.product?.image" :src="`http://localhost:8000/storage/${item.product.image}`" :alt="item.product?.name" />
              <div v-else class="img-placeholder">🎂</div>
            </div>
            <div class="item-info">
              <p class="item-cat">{{ item.product?.category?.name }}</p>
              <h3 class="item-name">{{ item.product?.name }}</h3>
              <p class="item-price">${{ Number(item.product?.price).toFixed(2) }}</p>
            </div>
            <div class="item-qty">
              <div class="qty-control">
                <button @click="updateQty(item, item.quantity - 1)" :disabled="item.quantity <= 1">−</button>
                <span>{{ item.quantity }}</span>
                <button @click="updateQty(item, item.quantity + 1)" :disabled="item.quantity >= item.product?.stock_quantity">+</button>
              </div>
            </div>
            <div class="item-subtotal">
              ${{ (item.product?.price * item.quantity).toFixed(2) }}
            </div>
            <button class="item-remove" @click="removeItem(item.id)">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>

        <!-- Summary -->
        <div class="summary-col">
          <div class="summary-card">
            <h3>Order Summary</h3>
            <div class="summary-rows">
              <div class="summary-row">
                <span>Subtotal ({{ cart.count }} items)</span>
                <span>${{ cart.total }}</span>
              </div>
              <div class="summary-row">
                <span>Shipping</span>
                <span>$5.00</span>
              </div>
              <div class="summary-row">
                <span>Tax (10%)</span>
                <span>${{ (cart.total * 0.1).toFixed(2) }}</span>
              </div>
              <div class="summary-row total">
                <span>Total</span>
                <span>${{ (Number(cart.total) + 5 + cart.total * 0.1).toFixed(2) }}</span>
              </div>
            </div>
            <router-link to="/checkout" class="checkout-btn">
              Proceed to Checkout <i class="fas fa-arrow-right"></i>
            </router-link>
            <router-link to="/shop" class="continue-btn">
              <i class="fas fa-arrow-left"></i> Continue Shopping
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { cart } from '../stores/cart'

async function updateQty(item, qty) {
  if (qty < 1) return
  await cart.update(item.id, qty)
}

async function removeItem(id) {
  await cart.remove(id)
}

onMounted(() => cart.fetch())
</script>

<style scoped>
.page { background: #fdf8f3; min-height: 100vh; padding: 2.5rem 0; }
.page-inner { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; }
.page-title { font-family: 'Playfair Display', serif; font-size: 2rem; color: #2d1b0e; margin-bottom: 2rem; }

.loading-state { display: flex; justify-content: center; align-items: center; padding: 5rem; }
.loading-state i { font-size: 2rem; color: #c8773a; }

.empty { text-align: center; padding: 5rem 1rem; color: #9a7560; }
.empty i { font-size: 3rem; display: block; margin-bottom: 1rem; color: #e8d5c0; }
.empty p { font-size: 1rem; margin-bottom: 1.5rem; }
.shop-btn { display: inline-block; padding: 0.7rem 2rem; background: linear-gradient(135deg, #c8773a, #e8945a); color: white; text-decoration: none; border-radius: 50px; font-weight: 600; }

.layout { display: grid; grid-template-columns: 1fr 340px; gap: 2rem; align-items: start; }

/* Items */
.items-col { display: flex; flex-direction: column; gap: 0.75rem; }
.cart-item { display: flex; align-items: center; gap: 1rem; background: white; border: 1px solid #f0e6d8; border-radius: 14px; padding: 1rem; }
.item-img { width: 80px; height: 80px; border-radius: 10px; overflow: hidden; background: linear-gradient(135deg, #fdf0e6, #f5d5b0); flex-shrink: 0; }
.item-img img { width: 100%; height: 100%; object-fit: cover; }
.img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 2rem; }
.item-info { flex: 1; min-width: 0; }
.item-cat  { font-size: 0.68rem; color: #c8773a; font-weight: 600; text-transform: uppercase; margin-bottom: 0.2rem; }
.item-name { font-family: 'Playfair Display', serif; font-size: 0.95rem; color: #2d1b0e; margin-bottom: 0.25rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.item-price { font-size: 0.85rem; color: #9a7560; }

.qty-control { display: flex; align-items: center; border: 1.5px solid #e8d5c0; border-radius: 8px; overflow: hidden; }
.qty-control button { width: 32px; height: 32px; background: #fdf0e6; border: none; font-size: 1rem; color: #8b5e3c; cursor: pointer; }
.qty-control button:disabled { opacity: 0.4; cursor: not-allowed; }
.qty-control span { width: 36px; text-align: center; font-weight: 600; font-size: 0.9rem; color: #2d1b0e; }

.item-subtotal { font-size: 1rem; font-weight: 700; color: #c8773a; min-width: 70px; text-align: right; }
.item-remove { width: 32px; height: 32px; border-radius: 8px; border: 1.5px solid #fecaca; background: white; color: #ef4444; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; transition: all 0.2s; flex-shrink: 0; }
.item-remove:hover { background: #fef2f2; }

/* Summary */
.summary-card { background: white; border: 1px solid #f0e6d8; border-radius: 16px; padding: 1.5rem; position: sticky; top: 90px; }
.summary-card h3 { font-family: 'Playfair Display', serif; font-size: 1.2rem; color: #2d1b0e; margin-bottom: 1.25rem; }
.summary-rows { display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 1.5rem; }
.summary-row { display: flex; justify-content: space-between; font-size: 0.875rem; color: #6b4c30; }
.summary-row.total { font-size: 1.05rem; font-weight: 700; color: #2d1b0e; padding-top: 0.75rem; border-top: 1px solid #f0e6d8; }

.checkout-btn { display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; padding: 0.85rem; background: linear-gradient(135deg, #c8773a, #e8945a); color: white; text-decoration: none; border-radius: 50px; font-weight: 600; font-size: 0.9rem; text-align: center; transition: all 0.2s; margin-bottom: 0.75rem; }
.checkout-btn:hover { filter: brightness(1.08); transform: translateY(-1px); }
.continue-btn { display: flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; padding: 0.75rem; border: 1.5px solid #e8d5c0; border-radius: 50px; color: #8b5e3c; font-size: 0.875rem; text-decoration: none; text-align: center; transition: all 0.2s; }
.continue-btn:hover { border-color: #c8773a; color: #c8773a; }

@media (max-width: 768px) { .layout { grid-template-columns: 1fr; } .item-qty { display: none; } }
</style>
