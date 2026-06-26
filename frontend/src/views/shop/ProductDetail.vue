<template>
  <div class="page">
    <div v-if="loading" class="loading-state"><i class="fas fa-spinner fa-spin"></i></div>

    <div v-else-if="!product" class="empty-state">
      <i class="fas fa-box-open"></i>
      <p>Product not found.</p>
      <router-link to="/shop" class="back-link">Back to Shop</router-link>
    </div>

    <div v-else class="inner">
      <nav class="breadcrumb">
        <router-link to="/">Home</router-link><span>/</span>
        <router-link to="/shop">Shop</router-link><span>/</span>
        <span class="current">{{ product.name }}</span>
      </nav>

      <div class="product-layout">
        <!-- Images -->
        <div class="images-col">
          <div class="main-img">
            <img v-if="activeImage" :src="activeImage" :alt="product.name" />
            <div v-else class="img-placeholder">🎂</div>
            <span v-if="product.featured" class="badge-featured">⭐ Featured</span>
            <span v-if="product.compare_price" class="badge-sale">-{{ discount }}% OFF</span>
          </div>
          <div v-if="allImages.length > 1" class="thumb-row">
            <div v-for="(img, i) in allImages" :key="i"
              class="thumb" :class="{ active: activeImage === img }" @click="activeImage = img">
              <img :src="img" :alt="`Image ${i+1}`" />
            </div>
          </div>
        </div>

        <!-- Info -->
        <div class="info-col">
          <p class="product-cat">{{ product.category?.name }}</p>
          <h1 class="product-name">{{ product.name }}</h1>
          <p v-if="product.short_description" class="product-short">{{ product.short_description }}</p>

          <!-- Rating summary -->
          <div v-if="product.review_count" class="rating-summary">
            <div class="rating-stars">
              <span v-for="s in 5" :key="s" class="star" :class="{ filled: s <= Math.round(product.avg_rating) }">★</span>
            </div>
            <span class="rating-avg">{{ product.avg_rating }}</span>
            <span class="rating-count">({{ product.review_count }} reviews)</span>
          </div>

          <div class="price-row">
            <span class="price">${{ Number(product.price).toFixed(2) }}</span>
            <span v-if="product.compare_price" class="compare">${{ Number(product.compare_price).toFixed(2) }}</span>
            <span v-if="product.compare_price" class="save-badge">Save ${{ (product.compare_price - product.price).toFixed(2) }}</span>
          </div>

          <div class="stock-row">
            <span v-if="product.stock_quantity > 10" class="stock in"><i class="fas fa-check-circle"></i> In Stock ({{ product.stock_quantity }} available)</span>
            <span v-else-if="product.stock_quantity > 0" class="stock low"><i class="fas fa-exclamation-circle"></i> Only {{ product.stock_quantity }} left!</span>
            <span v-else class="stock out"><i class="fas fa-times-circle"></i> Out of Stock</span>
          </div>

          <div v-if="product.stock_quantity > 0" class="qty-row">
            <div class="qty-control">
              <button @click="qty = Math.max(1, qty - 1)">−</button>
              <span>{{ qty }}</span>
              <button @click="qty = Math.min(product.stock_quantity, qty + 1)">+</button>
            </div>
          </div>

          <div class="action-btns">
            <button class="btn-wish" :class="{ active: wishlisted }" @click="toggleWishlist">
              <i :class="wishlisted ? 'fas fa-heart' : 'far fa-heart'"></i>
              {{ wishlisted ? 'Wishlisted' : 'Wishlist' }}
            </button>
            <button class="btn-cart" :disabled="product.stock_quantity < 1 || cartLoading" @click="addToCart">
              <i :class="cartLoading ? 'fas fa-spinner fa-spin' : 'fas fa-cart-plus'"></i>
              {{ product.stock_quantity < 1 ? 'Out of Stock' : 'Add to Cart' }}
            </button>
            <button class="btn-buy" :disabled="product.stock_quantity < 1 || buyLoading" @click="buyNow">
              <i :class="buyLoading ? 'fas fa-spinner fa-spin' : 'fas fa-bolt'"></i>
              Buy Now
            </button>
          </div>

          <div v-if="toast" class="toast" :class="toast.type">
            <i :class="toast.type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'"></i>
            {{ toast.message }}
          </div>

          <div v-if="product.description" class="desc-section">
            <h3>Description</h3>
            <p>{{ product.description }}</p>
          </div>

          <div class="meta-row">
            <span v-if="product.sku"><strong>SKU:</strong> {{ product.sku }}</span>
          </div>
        </div>
      </div>

      <!-- Reviews Section -->
      <div class="reviews-section">
        <h2 class="reviews-title">Customer Reviews</h2>

        <!-- Write Review -->
        <div v-if="isLoggedIn" class="review-form-wrap">
          <h3>{{ userReview ? 'Edit Your Review' : 'Write a Review' }}</h3>
          <div class="star-picker">
            <button v-for="s in 5" :key="s" class="star-btn" :class="{ filled: s <= reviewForm.rating }"
              @click="reviewForm.rating = s">★</button>
          </div>
          <textarea v-model="reviewForm.comment" placeholder="Share your experience..." rows="3" class="review-textarea"></textarea>
          <button @click="submitReview" class="submit-review-btn" :disabled="!reviewForm.rating || reviewLoading">
            <i :class="reviewLoading ? 'fas fa-spinner fa-spin' : 'fas fa-paper-plane'"></i>
            {{ userReview ? 'Update Review' : 'Submit Review' }}
          </button>
        </div>
        <p v-else class="login-to-review">
          <router-link to="/login">Login</router-link> to write a review.
        </p>

        <!-- Reviews List -->
        <div v-if="reviews.length" class="reviews-list">
          <div v-for="r in reviews" :key="r.id" class="review-card">
            <div class="review-head">
              <div class="reviewer-avatar">{{ r.user?.name?.charAt(0).toUpperCase() }}</div>
              <div class="reviewer-info">
                <p class="reviewer-name">{{ r.user?.name }}</p>
                <div class="review-stars">
                  <span v-for="s in 5" :key="s" class="rstar" :class="{ filled: s <= r.rating }">★</span>
                </div>
              </div>
              <div class="review-meta">
                <span class="review-date">{{ formatDate(r.created_at) }}</span>
                <button v-if="r.user?.id === currentUserId" class="delete-review" @click="deleteReview(r.id)">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
            <p v-if="r.comment" class="review-comment">{{ r.comment }}</p>
          </div>
        </div>
        <div v-else class="no-reviews">
          <i class="far fa-star"></i>
          <p>No reviews yet. Be the first to review!</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../services/api'
import { cart } from '../../stores/cart'
import { auth } from '../../stores/auth'

const route  = useRoute()
const router = useRouter()

const product     = ref(null)
const loading     = ref(true)
const activeImage = ref(null)
const qty         = ref(1)
const cartLoading = ref(false)
const buyLoading  = ref(false)
const toast       = ref(null)
const wishlisted  = ref(false)

const reviews     = ref([])
const reviewForm  = ref({ rating: 0, comment: '' })
const reviewLoading = ref(false)
const userReview  = ref(null)

const isLoggedIn    = ref(auth.isLoggedIn)
const currentUserId = ref(auth.user?.id)

const allImages = computed(() => {
  const imgs = []
  if (product.value?.image) imgs.push(`http://localhost:8000/storage/${product.value.image}`)
  if (product.value?.images?.length) product.value.images.forEach(i => imgs.push(`http://localhost:8000/storage/${i}`))
  return imgs
})

const discount = computed(() => {
  if (!product.value?.compare_price) return 0
  return Math.round((product.value.compare_price - product.value.price) / product.value.compare_price * 100)
})

function showToast(message, type = 'success') {
  toast.value = { message, type }
  setTimeout(() => toast.value = null, 3000)
}

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}

async function toggleWishlist() {
  if (!auth.isLoggedIn) { router.push('/login'); return }
  const { data } = await api.post('/wishlist/toggle', { product_id: product.value.id })
  wishlisted.value = data.wishlisted
  showToast(data.message)
}

async function addToCart() {
  if (!auth.isLoggedIn) { router.push('/login'); return }
  cartLoading.value = true
  try { await cart.add(product.value.id, qty.value); showToast('Added to cart! 🛒') }
  catch { showToast('Failed to add to cart', 'error') }
  finally { cartLoading.value = false }
}

async function buyNow() {
  if (!auth.isLoggedIn) { router.push('/login'); return }
  buyLoading.value = true
  try { await cart.add(product.value.id, qty.value); router.push('/cart') }
  catch { showToast('Something went wrong', 'error') }
  finally { buyLoading.value = false }
}

async function fetchReviews() {
  const { data } = await api.get(`/products/${route.params.id}/reviews`)
  reviews.value = data.reviews
  userReview.value = data.reviews.find(r => r.user?.id === currentUserId.value) || null
  if (userReview.value) {
    reviewForm.value = { rating: userReview.value.rating, comment: userReview.value.comment || '' }
  }
}

async function submitReview() {
  if (!reviewForm.value.rating) return
  reviewLoading.value = true
  try {
    await api.post(`/products/${route.params.id}/reviews`, reviewForm.value)
    await fetchReviews()
    showToast('Review submitted! ⭐')
  } catch { showToast('Failed to submit review', 'error') }
  finally { reviewLoading.value = false }
}

async function deleteReview(id) {
  await api.delete(`/reviews/${id}`)
  await fetchReviews()
  reviewForm.value = { rating: 0, comment: '' }
  userReview.value = null
  showToast('Review deleted')
}

onMounted(async () => {
  try {
    const { data } = await api.get(`/shop/products/${route.params.id}`)
    product.value = data
    if (data.image) activeImage.value = `http://localhost:8000/storage/${data.image}`
  } catch { product.value = null }
  finally { loading.value = false }

  fetchReviews()

  if (auth.isLoggedIn) {
    const { data } = await api.get('/wishlist/ids')
    wishlisted.value = data.includes(Number(route.params.id))
  }
})
</script>

<style scoped>
.page { background: #fdf8f3; min-height: 100vh; padding: 2rem 0; }
.inner { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem; }

.loading-state, .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 60vh; gap: 1rem; color: #9a7560; }
.loading-state i { font-size: 2rem; color: #c8773a; }
.empty-state i   { font-size: 3rem; }
.back-link { color: #c8773a; text-decoration: none; font-weight: 600; }

.breadcrumb { display: flex; align-items: center; gap: 0.5rem; font-size: 0.82rem; color: #9a7560; margin-bottom: 2rem; flex-wrap: wrap; }
.breadcrumb a { color: #c8773a; text-decoration: none; }
.breadcrumb a:hover { text-decoration: underline; }
.breadcrumb .current { color: #2d1b0e; font-weight: 500; }

.product-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start; }

.images-col { display: flex; flex-direction: column; gap: 0.75rem; }
.main-img { position: relative; border-radius: 20px; overflow: hidden; background: linear-gradient(135deg, #fdf0e6, #f5d5b0); aspect-ratio: 1; }
.main-img img { width: 100%; height: 100%; object-fit: cover; }
.img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 6rem; aspect-ratio: 1; }
.badge-featured { position: absolute; top: 1rem; left: 1rem; background: #c8773a; color: white; font-size: 0.7rem; font-weight: 600; padding: 0.25rem 0.7rem; border-radius: 50px; }
.badge-sale     { position: absolute; top: 1rem; right: 1rem; background: #ef4444; color: white; font-size: 0.7rem; font-weight: 700; padding: 0.25rem 0.7rem; border-radius: 50px; }
.thumb-row { display: flex; gap: 0.6rem; flex-wrap: wrap; }
.thumb { width: 68px; height: 68px; border-radius: 10px; overflow: hidden; cursor: pointer; border: 2px solid transparent; transition: all 0.2s; background: #f0e6d8; }
.thumb img { width: 100%; height: 100%; object-fit: cover; }
.thumb.active { border-color: #c8773a; }

.info-col { display: flex; flex-direction: column; gap: 1.1rem; }
.product-cat  { font-size: 0.75rem; font-weight: 700; color: #c8773a; text-transform: uppercase; letter-spacing: 0.08em; }
.product-name { font-family: 'Playfair Display', serif; font-size: 2rem; color: #2d1b0e; line-height: 1.2; margin: 0; }
.product-short { color: #7a5c42; font-size: 0.95rem; line-height: 1.65; margin: 0; }

.rating-summary { display: flex; align-items: center; gap: 0.5rem; }
.rating-stars .star { color: #d4c4b0; font-size: 1.1rem; }
.rating-stars .star.filled { color: #f59e0b; }
.rating-avg   { font-size: 1rem; font-weight: 700; color: #5a3e28; }
.rating-count { font-size: 0.85rem; color: #9a7560; }

.price-row { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; }
.price      { font-size: 1.8rem; font-weight: 700; color: #c8773a; }
.compare    { font-size: 1.1rem; color: #bba090; text-decoration: line-through; }
.save-badge { background: #fef2f2; color: #ef4444; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.65rem; border-radius: 50px; border: 1px solid #fecaca; }

.stock-row { font-size: 0.85rem; font-weight: 500; }
.stock.in  { color: #16a34a; }
.stock.low { color: #d97706; }
.stock.out { color: #dc2626; }

.qty-row { display: flex; align-items: center; gap: 1rem; }
.qty-control { display: flex; align-items: center; border: 1.5px solid #e8d5c0; border-radius: 10px; overflow: hidden; }
.qty-control button { width: 38px; height: 38px; background: #fdf0e6; border: none; font-size: 1.1rem; color: #8b5e3c; cursor: pointer; transition: background 0.2s; }
.qty-control button:hover { background: #f0d5b0; }
.qty-control span { width: 44px; text-align: center; font-weight: 600; color: #2d1b0e; font-size: 0.95rem; }

.action-btns { display: flex; gap: 0.75rem; flex-wrap: wrap; }

.btn-wish, .btn-cart, .btn-buy {
  display: flex; align-items: center; justify-content: center; gap: 0.5rem;
  padding: 0.75rem 1.25rem; border-radius: 50px; font-size: 0.88rem; font-weight: 600;
  cursor: pointer; transition: all 0.2s; border: none;
}
.btn-wish { background: white; color: #9a7560; border: 2px solid #e8d5c0; }
.btn-wish:hover { border-color: #ef4444; color: #ef4444; }
.btn-wish.active { border-color: #ef4444; color: #ef4444; background: #fef2f2; }
.btn-cart { background: white; color: #8b5e3c; border: 2px solid #e8d5c0; flex: 1; }
.btn-cart:hover:not(:disabled) { border-color: #c8773a; color: #c8773a; background: #fdf5ef; }
.btn-buy  { background: linear-gradient(135deg, #c8773a, #e8945a); color: white; flex: 1; }
.btn-buy:hover:not(:disabled) { filter: brightness(1.08); transform: translateY(-1px); }
.btn-cart:disabled, .btn-buy:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

.toast { display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.1rem; border-radius: 10px; font-size: 0.85rem; font-weight: 500; animation: fadeIn 0.2s ease; }
.toast.success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
.toast.error   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: none; } }

.desc-section h3 { font-family: 'Playfair Display', serif; font-size: 1.1rem; color: #2d1b0e; margin-bottom: 0.6rem; }
.desc-section p  { color: #7a5c42; font-size: 0.9rem; line-height: 1.7; }
.meta-row { font-size: 0.8rem; color: #9a7560; }
.meta-row strong { color: #5a3e28; }

/* Reviews */
.reviews-section { margin-top: 4rem; border-top: 1px solid #f0e6d8; padding-top: 3rem; }
.reviews-title { font-family: 'Playfair Display', serif; font-size: 1.6rem; color: #2d1b0e; margin-bottom: 2rem; }

.review-form-wrap { background: white; border: 1px solid #f0e6d8; border-radius: 16px; padding: 1.5rem; margin-bottom: 2rem; }
.review-form-wrap h3 { font-size: 1rem; font-weight: 600; color: #2d1b0e; margin-bottom: 1rem; }

.star-picker { display: flex; gap: 0.25rem; margin-bottom: 1rem; }
.star-btn { background: none; border: none; font-size: 1.8rem; cursor: pointer; color: #d4c4b0; transition: color 0.15s; padding: 0; line-height: 1; }
.star-btn.filled { color: #f59e0b; }
.star-btn:hover { color: #f59e0b; transform: scale(1.2); }

.review-textarea { width: 100%; border: 1.5px solid #e8d5c0; border-radius: 10px; padding: 0.75rem 1rem; font-size: 0.9rem; font-family: 'Inter', sans-serif; color: #2d1b0e; resize: vertical; outline: none; }
.review-textarea:focus { border-color: #c8773a; }
.submit-review-btn { margin-top: 0.75rem; display: flex; align-items: center; gap: 0.5rem; padding: 0.65rem 1.5rem; background: linear-gradient(135deg, #c8773a, #e8945a); color: white; border: none; border-radius: 50px; font-size: 0.875rem; font-weight: 600; cursor: pointer; transition: all 0.2s; }
.submit-review-btn:disabled { opacity: 0.6; cursor: not-allowed; }

.login-to-review { color: #9a7560; font-size: 0.9rem; margin-bottom: 2rem; }
.login-to-review a { color: #c8773a; font-weight: 600; text-decoration: none; }

.reviews-list { display: flex; flex-direction: column; gap: 1rem; }
.review-card { background: white; border: 1px solid #f0e6d8; border-radius: 14px; padding: 1.25rem; }
.review-head { display: flex; align-items: flex-start; gap: 0.85rem; margin-bottom: 0.75rem; }
.reviewer-avatar { width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #c8773a, #e8945a); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.85rem; flex-shrink: 0; }
.reviewer-info { flex: 1; }
.reviewer-name { font-weight: 600; font-size: 0.9rem; color: #2d1b0e; margin-bottom: 0.25rem; }
.review-stars .rstar { color: #d4c4b0; font-size: 0.85rem; }
.review-stars .rstar.filled { color: #f59e0b; }
.review-meta { display: flex; align-items: center; gap: 0.75rem; }
.review-date { font-size: 0.75rem; color: #9a7560; white-space: nowrap; }
.delete-review { background: none; border: none; color: #9a7560; cursor: pointer; font-size: 0.75rem; padding: 0; transition: color 0.2s; }
.delete-review:hover { color: #ef4444; }
.review-comment { font-size: 0.875rem; color: #6b4c30; line-height: 1.65; }

.no-reviews { text-align: center; padding: 3rem 1rem; color: #9a7560; }
.no-reviews i { font-size: 2.5rem; display: block; margin-bottom: 0.75rem; }
.no-reviews p { font-size: 0.9rem; }

@media (max-width: 768px) {
  .product-layout { grid-template-columns: 1fr; gap: 1.5rem; }
  .product-name { font-size: 1.5rem; }
  .action-btns { flex-direction: column; }
}
</style>
