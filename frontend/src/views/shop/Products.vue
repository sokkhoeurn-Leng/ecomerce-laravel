<template>
  <div class="page">
    <div class="page-inner">
      <div class="page-head">
        <div>
          <h1 class="page-title">Our Cakes 🎂</h1>
          <p class="page-sub">{{ pagination.total || 0 }} delicious products</p>
        </div>
      </div>

      <div class="layout">
        <!-- Sidebar Filters -->
        <aside class="sidebar">
          <div class="filter-section">
            <h3>Search</h3>
            <div class="search-wrap">
              <i class="fas fa-search"></i>
              <input v-model="filters.search" @input="debouncedFetch" placeholder="Search cakes..." />
            </div>
          </div>

          <div class="filter-section">
            <h3>Categories</h3>
            <label class="cat-option" :class="{ active: !filters.category_id }" @click="selectCategory(null)">
              <span>All Categories</span>
              <span class="cat-count">{{ pagination.total || 0 }}</span>
            </label>
            <label v-for="cat in categories" :key="cat.id" class="cat-option"
              :class="{ active: filters.category_id == cat.id }" @click="selectCategory(cat.id)">
              <span>{{ cat.name }}</span>
              <span class="cat-count">{{ cat.products_count }}</span>
            </label>
          </div>

          <div class="filter-section">
            <h3>Price Range</h3>
            <div class="price-range">
              <div class="price-inputs">
                <div class="price-input-wrap">
                  <span>$</span>
                  <input type="number" v-model.number="filters.min_price" @change="fetchProducts(1)" placeholder="Min" min="0" />
                </div>
                <span class="price-sep">—</span>
                <div class="price-input-wrap">
                  <span>$</span>
                  <input type="number" v-model.number="filters.max_price" @change="fetchProducts(1)" placeholder="Max" min="0" />
                </div>
              </div>
            </div>
          </div>

          <div class="filter-section">
            <h3>Rating</h3>
            <div class="rating-filters">
              <label v-for="r in [5,4,3,2,1]" :key="r" class="rating-option"
                :class="{ active: filters.min_rating == r }" @click="toggleRating(r)">
                <span class="stars">{{ '★'.repeat(r) }}{{ '☆'.repeat(5-r) }}</span>
                <span>& up</span>
              </label>
            </div>
          </div>

          <div class="filter-section">
            <h3>Availability</h3>
            <label class="check-option">
              <input type="checkbox" v-model="filters.in_stock" @change="fetchProducts(1)" />
              <span>In Stock Only</span>
            </label>
          </div>

          <div class="filter-section">
            <h3>Sort By</h3>
            <select v-model="filters.sort" @change="fetchProducts(1)" class="filter-select">
              <option value="newest">Newest First</option>
              <option value="oldest">Oldest First</option>
              <option value="price_asc">Price: Low to High</option>
              <option value="price_desc">Price: High to Low</option>
              <option value="popular">Most Popular</option>
            </select>
          </div>

          <button @click="resetFilters" class="reset-btn">
            <i class="fas fa-undo"></i> Reset Filters
          </button>
        </aside>

        <!-- Products Grid -->
        <div class="products-area">
          <div v-if="loading" class="loading-grid">
            <div v-for="n in 8" :key="n" class="skeleton-card"></div>
          </div>
          <div v-else-if="products.length" class="products-grid">
            <ProductCard v-for="p in products" :key="p.id" :product="p"
              @add-to-cart="addToCart" @toggle-wishlist="toggleWishlist" :wishlisted="wishlistIds.includes(p.id)" />
          </div>
          <div v-else class="empty">
            <i class="fas fa-search"></i>
            <p>No products found</p>
            <button @click="resetFilters">Clear filters</button>
          </div>

          <div v-if="pagination.last_page > 1" class="pagination">
            <button @click="fetchProducts(pagination.current_page - 1)" :disabled="pagination.current_page <= 1">
              <i class="fas fa-chevron-left"></i>
            </button>
            <button v-for="p in pagination.last_page" :key="p" @click="fetchProducts(p)"
              :class="{ active: p === pagination.current_page }">{{ p }}</button>
            <button @click="fetchProducts(pagination.current_page + 1)" :disabled="pagination.current_page >= pagination.last_page">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast -->
    <div v-if="toast" class="toast-fixed" :class="toast.type">
      <i :class="toast.type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'"></i>
      {{ toast.message }}
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../services/api'
import { cart } from '../../stores/cart'
import { auth } from '../../stores/auth'
import ProductCard from '../../components/ProductCard.vue'

const route  = useRoute()
const router = useRouter()

const products    = ref([])
const categories  = ref([])
const wishlistIds = ref([])
const loading     = ref(false)
const pagination  = ref({})
const toast       = ref(null)
const filters     = ref({ search: '', category_id: '', sort: 'newest', min_price: '', max_price: '', min_rating: '', in_stock: false })

let searchTimer

function showToast(msg, type = 'success') {
  toast.value = { message: msg, type }
  setTimeout(() => toast.value = null, 3000)
}

function debouncedFetch() {
  clearTimeout(searchTimer)
  searchTimer = setTimeout(() => fetchProducts(1), 350)
}

function toggleRating(r) {
  filters.value.min_rating = filters.value.min_rating === r ? '' : r
  fetchProducts(1)
}

async function fetchProducts(page = 1) {
  loading.value = true
  try {
    const params = { ...filters.value, page }
    if (!params.min_price) delete params.min_price
    if (!params.max_price) delete params.max_price
    if (!params.min_rating) delete params.min_rating
    if (!params.in_stock) delete params.in_stock
    const { data } = await api.get('/shop/products', { params })
    products.value   = data.data
    pagination.value = { current_page: data.current_page, last_page: data.last_page, total: data.total }
  } finally { loading.value = false }
}

async function fetchCategories() {
  const { data } = await api.get('/shop/categories')
  categories.value = data
}

async function fetchWishlistIds() {
  if (!auth.isLoggedIn) return
  const { data } = await api.get('/wishlist/ids')
  wishlistIds.value = data
}

function selectCategory(id) {
  filters.value.category_id = id
  fetchProducts(1)
}

function resetFilters() {
  filters.value = { search: '', category_id: '', sort: 'newest', min_price: '', max_price: '', min_rating: '', in_stock: false }
  fetchProducts(1)
}

async function addToCart(productId) {
  if (!auth.isLoggedIn) { router.push('/login'); return }
  try { await cart.add(productId); showToast('Added to cart! 🛒') }
  catch { showToast('Failed to add', 'error') }
}

async function toggleWishlist(productId) {
  if (!auth.isLoggedIn) { router.push('/login'); return }
  await api.post('/wishlist/toggle', { product_id: productId })
  if (wishlistIds.value.includes(productId)) {
    wishlistIds.value = wishlistIds.value.filter(id => id !== productId)
  } else {
    wishlistIds.value.push(productId)
  }
}

onMounted(() => {
  // pick up query params from home page navigation
  if (route.query.search)      filters.value.search = route.query.search
  if (route.query.category_id) filters.value.category_id = route.query.category_id
  fetchProducts()
  fetchCategories()
  fetchWishlistIds()
})
</script>

<style scoped>
.page { background: #fdf8f3; min-height: 100vh; padding: 2rem 0; }
.page-inner { max-width: 1200px; margin: 0 auto; padding: 0 1.5rem; }
.page-head { margin-bottom: 2rem; }
.page-title { font-family: 'Playfair Display', serif; font-size: 2rem; color: #2d1b0e; }
.page-sub { color: #9a7560; font-size: 0.9rem; margin-top: 0.25rem; }

.layout { display: grid; grid-template-columns: 250px 1fr; gap: 2rem; }

/* Sidebar */
.sidebar { display: flex; flex-direction: column; gap: 1.5rem; }
.filter-section h3 { font-size: 0.78rem; font-weight: 600; color: #9a7560; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 0.75rem; }

.search-wrap { position: relative; }
.search-wrap i { position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #c8a07a; font-size: 0.8rem; }
.search-wrap input { width: 100%; padding: 0.6rem 0.75rem 0.6rem 2.25rem; border: 1.5px solid #e8d5c0; border-radius: 10px; background: white; font-size: 0.875rem; color: #2d1b0e; outline: none; }
.search-wrap input:focus { border-color: #c8773a; }

.cat-option { display: flex; align-items: center; justify-content: space-between; padding: 0.5rem 0.75rem; border-radius: 8px; cursor: pointer; font-size: 0.875rem; color: #6b4c30; margin-bottom: 0.2rem; transition: all 0.15s; }
.cat-option:hover { background: #fdf0e6; }
.cat-option.active { background: #fdf0e6; color: #c8773a; font-weight: 600; }
.cat-count { font-size: 0.72rem; background: #f0e6d8; color: #9a7560; padding: 0.1rem 0.5rem; border-radius: 50px; }

/* Price Range */
.price-inputs { display: flex; align-items: center; gap: 0.5rem; }
.price-input-wrap { display: flex; align-items: center; gap: 0; border: 1.5px solid #e8d5c0; border-radius: 8px; overflow: hidden; background: white; flex: 1; }
.price-input-wrap span { padding: 0 0.5rem; color: #9a7560; font-size: 0.85rem; background: #fdf8f3; border-right: 1px solid #e8d5c0; }
.price-input-wrap input { width: 100%; padding: 0.5rem 0.5rem; border: none; outline: none; font-size: 0.85rem; color: #2d1b0e; }
.price-sep { color: #9a7560; font-size: 0.85rem; flex-shrink: 0; }

/* Rating */
.rating-filters { display: flex; flex-direction: column; gap: 0.3rem; }
.rating-option { display: flex; align-items: center; gap: 0.4rem; padding: 0.4rem 0.6rem; border-radius: 8px; cursor: pointer; font-size: 0.8rem; transition: all 0.15s; }
.rating-option:hover { background: #fdf0e6; }
.rating-option.active { background: #fdf0e6; color: #c8773a; font-weight: 600; }
.stars { color: #f59e0b; letter-spacing: 1px; }

/* Availability */
.check-option { display: flex; align-items: center; gap: 0.6rem; font-size: 0.875rem; color: #6b4c30; cursor: pointer; }
.check-option input { accent-color: #c8773a; width: 16px; height: 16px; cursor: pointer; }

.filter-select { width: 100%; padding: 0.6rem 0.75rem; border: 1.5px solid #e8d5c0; border-radius: 10px; font-size: 0.875rem; color: #2d1b0e; background: white; outline: none; }
.reset-btn { padding: 0.55rem 1rem; border: 1.5px solid #e8d5c0; border-radius: 10px; background: white; color: #9a7560; font-size: 0.82rem; cursor: pointer; display: flex; align-items: center; gap: 0.4rem; transition: all 0.2s; }
.reset-btn:hover { border-color: #c8773a; color: #c8773a; }

/* Grid */
.products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.25rem; }
.loading-grid  { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.25rem; }
.skeleton-card { height: 320px; background: linear-gradient(90deg, #f0e6d8 25%, #fdf0e6 50%, #f0e6d8 75%); background-size: 200%; border-radius: 16px; animation: shimmer 1.4s infinite; }
@keyframes shimmer { 0% { background-position: 200% 0 } 100% { background-position: -200% 0 } }

.empty { text-align: center; padding: 4rem 1rem; color: #9a7560; }
.empty i { font-size: 2.5rem; margin-bottom: 1rem; display: block; }
.empty p { margin-bottom: 1rem; font-size: 1rem; }
.empty button { padding: 0.5rem 1.5rem; border: 1.5px solid #c8773a; border-radius: 50px; background: white; color: #c8773a; cursor: pointer; }

.pagination { display: flex; justify-content: center; gap: 0.4rem; margin-top: 2rem; }
.pagination button { width: 36px; height: 36px; border-radius: 8px; border: 1.5px solid #e8d5c0; background: white; color: #6b4c30; cursor: pointer; font-size: 0.85rem; transition: all 0.15s; }
.pagination button:hover { border-color: #c8773a; color: #c8773a; }
.pagination button.active { background: #c8773a; border-color: #c8773a; color: white; }
.pagination button:disabled { opacity: 0.4; cursor: not-allowed; }

.toast-fixed { position: fixed; bottom: 2rem; right: 2rem; z-index: 9999; display: flex; align-items: center; gap: 0.5rem; padding: 0.85rem 1.25rem; border-radius: 12px; font-size: 0.875rem; font-weight: 500; box-shadow: 0 8px 24px rgba(0,0,0,0.12); animation: slideUp 0.25s ease; }
.toast-fixed.success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
.toast-fixed.error   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
@keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }

@media (max-width: 768px) { .layout { grid-template-columns: 1fr; } .sidebar { display: none; } }
</style>
