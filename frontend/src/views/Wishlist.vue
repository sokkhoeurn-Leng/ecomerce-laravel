<template>
  <div class="page">
    <div class="page-inner">
      <h1 class="page-title">❤️ My Wishlist</h1>
      <p class="page-sub">{{ items.length }} saved item{{ items.length !== 1 ? 's' : '' }}</p>

      <div v-if="loading" class="loading-grid">
        <div v-for="n in 4" :key="n" class="skeleton-card"></div>
      </div>

      <div v-else-if="!items.length" class="empty">
        <i class="far fa-heart"></i>
        <p>Your wishlist is empty.</p>
        <router-link to="/shop" class="shop-btn">Browse Products</router-link>
      </div>

      <div v-else class="grid">
        <div v-for="item in items" :key="item.id" class="wish-card">
          <div class="wish-img" @click="go(item.product.id)">
            <img v-if="item.product.image" :src="`http://localhost:8000/storage/${item.product.image}`" :alt="item.product.name" />
            <div v-else class="img-placeholder">🎂</div>
            <span v-if="item.product.stock_quantity < 1" class="badge-out">Out of Stock</span>
          </div>
          <div class="wish-body">
            <p class="wish-cat">{{ item.product.category?.name }}</p>
            <h3 class="wish-name" @click="go(item.product.id)">{{ item.product.name }}</h3>
            <p class="wish-price">${{ Number(item.product.price).toFixed(2) }}</p>
            <div class="wish-actions">
              <button class="btn-cart" :disabled="item.product.stock_quantity < 1" @click="moveToCart(item)">
                <i class="fas fa-cart-plus"></i> Move to Cart
              </button>
              <button class="btn-remove" @click="remove(item)">
                <i class="fas fa-trash"></i>
              </button>
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
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'
import { cart } from '../stores/cart'

const router = useRouter()
const items   = ref([])
const loading = ref(true)
const toast   = ref(null)

function showToast(msg, type = 'success') {
  toast.value = { message: msg, type }
  setTimeout(() => toast.value = null, 3000)
}

const go = (id) => router.push(`/products/${id}`)

async function fetchWishlist() {
  loading.value = true
  try { const { data } = await api.get('/wishlist'); items.value = data }
  finally { loading.value = false }
}

async function remove(item) {
  await api.post('/wishlist/toggle', { product_id: item.product.id })
  items.value = items.value.filter(i => i.id !== item.id)
  showToast('Removed from wishlist')
}

async function moveToCart(item) {
  await cart.add(item.product.id)
  await remove(item)
  showToast('Moved to cart! 🛒')
}

onMounted(fetchWishlist)
</script>

<style scoped>
.page { background: #fdf8f3; min-height: 100vh; padding: 2.5rem 0; }
.page-inner { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; }
.page-title { font-family: 'Playfair Display', serif; font-size: 2rem; color: #2d1b0e; margin-bottom: 0.25rem; }
.page-sub { color: #9a7560; font-size: 0.9rem; margin-bottom: 2rem; }

.loading-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.25rem; }
.skeleton-card { height: 340px; background: linear-gradient(90deg, #f0e6d8 25%, #fdf0e6 50%, #f0e6d8 75%); background-size: 200%; border-radius: 16px; animation: shimmer 1.4s infinite; }
@keyframes shimmer { 0% { background-position: 200% 0 } 100% { background-position: -200% 0 } }

.empty { text-align: center; padding: 5rem 1rem; color: #9a7560; }
.empty i { font-size: 3rem; display: block; margin-bottom: 1rem; color: #e8d5c0; }
.empty p { font-size: 1rem; margin-bottom: 1.5rem; }
.shop-btn { display: inline-block; padding: 0.7rem 2rem; background: linear-gradient(135deg, #c8773a, #e8945a); color: white; text-decoration: none; border-radius: 50px; font-weight: 600; font-size: 0.9rem; }

.grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.25rem; }

.wish-card { background: white; border: 1px solid #f0e6d8; border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; transition: all 0.25s; }
.wish-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(200,119,58,0.12); }

.wish-img { position: relative; height: 200px; overflow: hidden; cursor: pointer; background: linear-gradient(135deg, #fdf0e6, #f5d5b0); }
.wish-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.35s; }
.wish-card:hover .wish-img img { transform: scale(1.06); }
.img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 4rem; }
.badge-out { position: absolute; top: 0.65rem; left: 0.65rem; background: #6b7280; color: white; font-size: 0.65rem; font-weight: 600; padding: 0.2rem 0.55rem; border-radius: 50px; }

.wish-body { padding: 1rem 1.1rem 1.1rem; display: flex; flex-direction: column; flex: 1; }
.wish-cat  { font-size: 0.7rem; color: #c8773a; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.3rem; }
.wish-name { font-family: 'Playfair Display', serif; font-size: 1rem; color: #2d1b0e; cursor: pointer; line-height: 1.3; margin-bottom: 0.5rem; }
.wish-name:hover { color: #c8773a; }
.wish-price { font-size: 1.1rem; font-weight: 700; color: #c8773a; flex: 1; }

.wish-actions { display: flex; gap: 0.5rem; margin-top: 0.75rem; }
.btn-cart { flex: 1; display: flex; align-items: center; justify-content: center; gap: 0.35rem; padding: 0.55rem; background: linear-gradient(135deg, #c8773a, #e8945a); color: white; border: none; border-radius: 50px; font-size: 0.78rem; font-weight: 600; cursor: pointer; transition: all 0.2s; }
.btn-cart:disabled { background: #d4c4b0; cursor: not-allowed; }
.btn-remove { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border: 1.5px solid #fecaca; border-radius: 50%; background: white; color: #ef4444; cursor: pointer; font-size: 0.8rem; transition: all 0.2s; flex-shrink: 0; }
.btn-remove:hover { background: #fef2f2; }

.toast-fixed { position: fixed; bottom: 2rem; right: 2rem; z-index: 9999; display: flex; align-items: center; gap: 0.5rem; padding: 0.85rem 1.25rem; border-radius: 12px; font-size: 0.875rem; font-weight: 500; box-shadow: 0 8px 24px rgba(0,0,0,0.12); animation: slideUp 0.25s ease; }
.toast-fixed.success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
.toast-fixed.error   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
@keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }
</style>
