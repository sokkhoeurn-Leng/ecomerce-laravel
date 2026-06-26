<template>
  <div class="home">
    <!-- Hero -->
    <section class="hero">
      <div class="hero-content">
        <p class="hero-badge">🍰 Fresh Baked Daily</p>
        <h1 class="hero-title">Life is Sweeter<br>with <span class="hero-accent">Every Bite</span></h1>
        <p class="hero-sub">Handcrafted cakes and pastries made with the finest ingredients.<br>Order online and enjoy same-day delivery.</p>
        <div class="hero-actions">
          <router-link to="/shop" class="cta-primary">Order Now 🎂</router-link>
          <router-link to="/about" class="cta-secondary">Our Story</router-link>
        </div>
        <div class="hero-stats">
          <div class="stat"><span class="stat-num">500+</span><span class="stat-label">Happy Customers</span></div>
          <div class="stat-divider"></div>
          <div class="stat"><span class="stat-num">50+</span><span class="stat-label">Cake Flavours</span></div>
          <div class="stat-divider"></div>
          <div class="stat"><span class="stat-num">4.9★</span><span class="stat-label">Average Rating</span></div>
        </div>
      </div>
      <div class="hero-visual">
        <div class="cake-display">
          <div class="cake-circle cake-c1"></div>
          <div class="cake-circle cake-c2"></div>
          <div class="cake-emoji">🎂</div>
          <div class="floating-badge fb1">✨ Best Seller</div>
          <div class="floating-badge fb2">🎁 Gift Ready</div>
        </div>
      </div>
    </section>

    <!-- Search Bar -->
    <section class="search-section">
      <div class="section-inner">
        <div class="search-bar">
          <i class="fas fa-search"></i>
          <input v-model="searchQuery" @keyup.enter="goSearch" placeholder="Search cakes, flavours, categories..." />
          <button @click="goSearch" class="search-btn">Search</button>
        </div>
      </div>
    </section>

    <!-- Categories -->
    <section class="section">
      <div class="section-inner">
        <p class="section-label">Our Specialties</p>
        <h2 class="section-title">Shop by Category</h2>
        <div v-if="loadingCats" class="cats-skeleton">
          <div v-for="n in 4" :key="n" class="cat-skel"></div>
        </div>
        <div v-else class="categories-grid">
          <div v-for="cat in categories" :key="cat.id" class="cat-card" @click="goCategory(cat.id)">
            <span class="cat-emoji">🎂</span>
            <h3>{{ cat.name }}</h3>
            <p class="cat-count">{{ cat.products_count }} products</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Products -->
    <section class="section section-pink">
      <div class="section-inner">
        <p class="section-label">⭐ Hand-picked</p>
        <h2 class="section-title">Featured Products</h2>
        <div v-if="loadingFeatured" class="loading-grid">
          <div v-for="n in 4" :key="n" class="skeleton-card"></div>
        </div>
        <div v-else-if="featuredProducts.length" class="products-grid">
          <ProductCard v-for="p in featuredProducts" :key="p.id" :product="p"
            :wishlisted="wishlistIds.includes(p.id)"
            @add-to-cart="addToCart" @toggle-wishlist="toggleWishlist" />
        </div>
        <p v-else class="empty-text">No featured products yet.</p>
        <div class="view-all-wrap">
          <router-link to="/shop" class="view-all-btn">View All Products <i class="fas fa-arrow-right"></i></router-link>
        </div>
      </div>
    </section>

    <!-- New Arrivals -->
    <section class="section">
      <div class="section-inner">
        <p class="section-label">🆕 Just Added</p>
        <h2 class="section-title">New Arrivals</h2>
        <div v-if="loadingNew" class="loading-grid">
          <div v-for="n in 4" :key="n" class="skeleton-card"></div>
        </div>
        <div v-else-if="newArrivals.length" class="products-grid">
          <ProductCard v-for="p in newArrivals" :key="p.id" :product="p"
            :wishlisted="wishlistIds.includes(p.id)"
            @add-to-cart="addToCart" @toggle-wishlist="toggleWishlist" />
        </div>
        <p v-else class="empty-text">No new arrivals yet.</p>
      </div>
    </section>

    <!-- Best Selling -->
    <section class="section section-pink">
      <div class="section-inner">
        <p class="section-label">🔥 Popular</p>
        <h2 class="section-title">Best Selling</h2>
        <div v-if="loadingBest" class="loading-grid">
          <div v-for="n in 4" :key="n" class="skeleton-card"></div>
        </div>
        <div v-else-if="bestSelling.length" class="products-grid">
          <ProductCard v-for="p in bestSelling" :key="p.id" :product="p"
            :wishlisted="wishlistIds.includes(p.id)"
            @add-to-cart="addToCart" @toggle-wishlist="toggleWishlist" />
        </div>
        <p v-else class="empty-text">No best sellers yet.</p>
      </div>
    </section>

    <!-- Why Us -->
    <section class="section">
      <div class="section-inner">
        <p class="section-label">Why Choose Us</p>
        <h2 class="section-title">Baked with Love</h2>
        <div class="features-grid">
          <div v-for="f in features" :key="f.title" class="feature-card">
            <span class="feature-icon">{{ f.icon }}</span>
            <h4>{{ f.title }}</h4>
            <p>{{ f.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Banner -->
    <section class="cta-banner">
      <div class="section-inner cta-inner">
        <div>
          <h2>Ready to Order?</h2>
          <p>Create an account and get 10% off your first order!</p>
        </div>
        <div class="cta-btns">
          <router-link v-if="!isLoggedIn" to="/register" class="cta-primary">Get Started</router-link>
          <router-link v-else to="/shop" class="cta-primary">Browse Cakes</router-link>
        </div>
      </div>
    </section>

    <!-- Toast -->
    <div v-if="toast" class="global-toast" :class="toast.type">
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
import { auth } from '../stores/auth'
import ProductCard from '../components/ProductCard.vue'

const router = useRouter()
const isLoggedIn = ref(auth.isLoggedIn)

const categories     = ref([])
const featuredProducts = ref([])
const newArrivals    = ref([])
const bestSelling    = ref([])
const wishlistIds    = ref([])
const searchQuery    = ref('')

const loadingCats     = ref(true)
const loadingFeatured = ref(true)
const loadingNew      = ref(true)
const loadingBest     = ref(true)
const toast           = ref(null)

function showToast(message, type = 'success') {
  toast.value = { message, type }
  setTimeout(() => toast.value = null, 3000)
}

function goSearch() {
  if (searchQuery.value.trim()) router.push({ path: '/shop', query: { search: searchQuery.value.trim() } })
}

function goCategory(id) {
  router.push({ path: '/shop', query: { category_id: id } })
}

async function addToCart(productId) {
  if (!auth.isLoggedIn) { router.push('/login'); return }
  try { await cart.add(productId); showToast('Added to cart! 🛒') }
  catch { showToast('Failed to add to cart', 'error') }
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

async function fetchWishlistIds() {
  if (!auth.isLoggedIn) return
  const { data } = await api.get('/wishlist/ids')
  wishlistIds.value = data
}

onMounted(async () => {
  isLoggedIn.value = auth.isLoggedIn
  fetchWishlistIds()

  api.get('/shop/categories').then(({ data }) => { categories.value = data }).finally(() => loadingCats.value = false)
  api.get('/shop/products', { params: { featured: 1, per_page: 4 } }).then(({ data }) => { featuredProducts.value = data.data }).finally(() => loadingFeatured.value = false)
  api.get('/shop/products', { params: { sort: 'newest', per_page: 4 } }).then(({ data }) => { newArrivals.value = data.data }).finally(() => loadingNew.value = false)
  api.get('/shop/products', { params: { sort: 'popular', per_page: 4 } }).then(({ data }) => { bestSelling.value = data.data }).finally(() => loadingBest.value = false)
})

const features = [
  { icon: '🌿', title: 'Fresh Ingredients', desc: 'We source locally every morning for the freshest bakes.' },
  { icon: '👩‍🍳', title: 'Expert Bakers', desc: 'Our team has 10+ years of pastry experience.' },
  { icon: '🚚', title: 'Same-Day Delivery', desc: 'Order by noon, receive by evening.' },
  { icon: '🎁', title: 'Custom Orders', desc: "Tell us your vision, we'll bring it to life." },
]
</script>

<style scoped>
.home { overflow-x: hidden; }

/* Hero */
.hero {
  min-height: calc(100vh - 70px);
  display: flex; align-items: center;
  max-width: 1200px; margin: 0 auto;
  padding: 4rem 2rem; gap: 4rem;
}
.hero-content { flex: 1; }
.hero-badge { display: inline-block; background: #fdf0e6; color: #c8773a; border: 1px solid #f0d5ba; padding: 0.4rem 1rem; border-radius: 50px; font-size: 0.85rem; font-weight: 500; margin-bottom: 1.5rem; }
.hero-title { font-family: 'Playfair Display', serif; font-size: clamp(2.4rem, 5vw, 3.6rem); font-weight: 700; line-height: 1.15; color: #2d1b0e; margin-bottom: 1.25rem; }
.hero-accent { color: #c8773a; }
.hero-sub { color: #7a5c42; font-size: 1.05rem; line-height: 1.7; margin-bottom: 2rem; }
.hero-actions { display: flex; gap: 1rem; margin-bottom: 2.5rem; flex-wrap: wrap; }
.cta-primary { text-decoration: none; padding: 0.8rem 2rem; background: linear-gradient(135deg, #c8773a, #e8945a); color: white; border-radius: 50px; font-weight: 600; font-size: 0.95rem; transition: all 0.25s; box-shadow: 0 4px 20px rgba(200,119,58,0.35); display: inline-block; }
.cta-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 25px rgba(200,119,58,0.45); }
.cta-secondary { text-decoration: none; padding: 0.8rem 2rem; border: 1.5px solid #d4a87a; color: #8b5e3c; border-radius: 50px; font-weight: 500; font-size: 0.95rem; transition: all 0.25s; display: inline-block; }
.cta-secondary:hover { background: #fdf0e6; border-color: #c8773a; }
.hero-stats { display: flex; align-items: center; gap: 1.5rem; }
.stat { display: flex; flex-direction: column; gap: 0.2rem; }
.stat-num { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; color: #c8773a; }
.stat-label { font-size: 0.75rem; color: #9a7560; }
.stat-divider { width: 1px; height: 40px; background: #e8d5c0; }
.hero-visual { flex: 1; display: flex; justify-content: center; align-items: center; }
.cake-display { position: relative; width: 350px; height: 350px; display: flex; align-items: center; justify-content: center; }
.cake-circle { position: absolute; border-radius: 50%; }
.cake-c1 { width: 320px; height: 320px; background: radial-gradient(circle at 35% 35%, #fdf0e6, #f5d5b0); }
.cake-c2 { width: 240px; height: 240px; background: radial-gradient(circle at 40% 40%, #fce8d0, #f0c898); }
.cake-emoji { font-size: 7rem; position: relative; z-index: 2; filter: drop-shadow(0 8px 24px rgba(200,119,58,0.3)); animation: float 3s ease-in-out infinite; }
@keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }
.floating-badge { position: absolute; background: white; border: 1px solid #f0d5ba; border-radius: 50px; padding: 0.4rem 0.9rem; font-size: 0.78rem; font-weight: 600; color: #5a3e28; box-shadow: 0 4px 16px rgba(0,0,0,0.08); z-index: 3; }
.fb1 { top: 30px; right: 10px; }
.fb2 { bottom: 50px; left: 0; }

/* Search */
.search-section { background: white; border-top: 1px solid #f0e6d8; border-bottom: 1px solid #f0e6d8; padding: 1.5rem 0; }
.search-bar { display: flex; align-items: center; gap: 0; background: white; border: 2px solid #e8d5c0; border-radius: 50px; padding: 0 0.5rem 0 1.25rem; max-width: 600px; margin: 0 auto; transition: border-color 0.2s; }
.search-bar:focus-within { border-color: #c8773a; }
.search-bar i { color: #c8a07a; font-size: 0.9rem; flex-shrink: 0; }
.search-bar input { flex: 1; border: none; outline: none; padding: 0.75rem 0.75rem; font-size: 0.95rem; color: #2d1b0e; background: transparent; font-family: 'Inter', sans-serif; }
.search-bar input::placeholder { color: #c4a07a; }
.search-btn { background: linear-gradient(135deg, #c8773a, #e8945a); color: white; border: none; padding: 0.6rem 1.5rem; border-radius: 50px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
.search-btn:hover { filter: brightness(1.08); }

/* Sections */
.section { padding: 5rem 0; }
.section-pink { background: #fdf5ef; }
.section-inner { max-width: 1200px; margin: 0 auto; padding: 0 2rem; }
.section-label { text-align: center; font-size: 0.8rem; font-weight: 600; color: #c8773a; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 0.6rem; }
.section-title { font-family: 'Playfair Display', serif; text-align: center; font-size: clamp(1.8rem, 3vw, 2.4rem); font-weight: 700; color: #2d1b0e; margin-bottom: 3rem; }

/* Categories */
.categories-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; }
.cats-skeleton { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; }
.cat-skel { height: 130px; background: linear-gradient(90deg, #f0e6d8 25%, #fdf0e6 50%, #f0e6d8 75%); background-size: 200%; border-radius: 16px; animation: shimmer 1.4s infinite; }
.cat-card { background: white; border: 1px solid #f0e6d8; border-radius: 16px; padding: 2rem 1.5rem; text-align: center; transition: all 0.25s; cursor: pointer; }
.cat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(200,119,58,0.12); border-color: #c8773a; }
.cat-emoji { font-size: 2.5rem; display: block; margin-bottom: 1rem; }
.cat-card h3 { font-family: 'Playfair Display', serif; font-size: 1.05rem; color: #2d1b0e; margin-bottom: 0.3rem; }
.cat-count { font-size: 0.78rem; color: #9a7560; }

/* Products */
.products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.5rem; }
.loading-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.5rem; }
.skeleton-card { height: 340px; background: linear-gradient(90deg, #f0e6d8 25%, #fdf0e6 50%, #f0e6d8 75%); background-size: 200%; border-radius: 16px; animation: shimmer 1.4s infinite; }
@keyframes shimmer { 0% { background-position: 200% 0 } 100% { background-position: -200% 0 } }
.empty-text { text-align: center; color: #9a7560; font-size: 0.95rem; padding: 2rem; }

.view-all-wrap { text-align: center; margin-top: 2.5rem; }
.view-all-btn { display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; padding: 0.75rem 2rem; border: 2px solid #c8773a; border-radius: 50px; color: #c8773a; font-weight: 600; font-size: 0.9rem; transition: all 0.25s; }
.view-all-btn:hover { background: #c8773a; color: white; }

/* Features */
.features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; }
.feature-card { text-align: center; padding: 2rem 1.5rem; }
.feature-icon { font-size: 2.2rem; display: block; margin-bottom: 1rem; }
.feature-card h4 { font-weight: 600; color: #2d1b0e; margin-bottom: 0.5rem; }
.feature-card p { font-size: 0.85rem; color: #9a7560; line-height: 1.6; }

/* CTA Banner */
.cta-banner { background: linear-gradient(135deg, #2d1b0e 0%, #5a3520 100%); padding: 4rem 0; }
.cta-inner { display: flex; align-items: center; justify-content: space-between; gap: 2rem; flex-wrap: wrap; }
.cta-banner h2 { font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #f5d9b8; margin-bottom: 0.5rem; }
.cta-banner p { color: #a08060; font-size: 0.95rem; }

/* Global Toast */
.global-toast {
  position: fixed; bottom: 2rem; right: 2rem; z-index: 9999;
  display: flex; align-items: center; gap: 0.5rem;
  padding: 0.85rem 1.25rem; border-radius: 12px;
  font-size: 0.875rem; font-weight: 500;
  box-shadow: 0 8px 24px rgba(0,0,0,0.12);
  animation: slideUp 0.25s ease;
}
.global-toast.success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
.global-toast.error   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
@keyframes slideUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: none; } }

@media (max-width: 768px) {
  .hero { flex-direction: column; gap: 2rem; padding: 2rem 1.5rem; min-height: auto; }
  .hero-visual { display: none; }
}
</style>
