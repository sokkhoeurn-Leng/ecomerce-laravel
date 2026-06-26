<template>
  <div class="card">
    <div class="card-img" @click="go">
      <img v-if="product.image" :src="`http://localhost:8000/storage/${product.image}`" :alt="product.name" />
      <div v-else class="card-img-placeholder">🎂</div>
      <span v-if="product.featured" class="badge-featured">⭐ Featured</span>
      <span v-if="product.compare_price" class="badge-sale">-{{ discount }}%</span>
      <span v-if="product.stock_quantity < 1" class="badge-out">Out of Stock</span>
      <button class="btn-wish" :class="{ active: wishlisted }" @click.stop="$emit('toggle-wishlist', product.id)" title="Wishlist">
        <i :class="wishlisted ? 'fas fa-heart' : 'far fa-heart'"></i>
      </button>
    </div>

    <div class="card-body">
      <p class="card-cat">{{ product.category?.name || 'Uncategorized' }}</p>
      <h3 class="card-name" @click="go">{{ product.name }}</h3>

      <!-- Rating -->
      <div v-if="product.avg_rating" class="card-rating">
        <span class="stars">{{ '★'.repeat(Math.round(product.avg_rating)) }}{{ '☆'.repeat(5 - Math.round(product.avg_rating)) }}</span>
        <span class="rating-num">{{ product.avg_rating }}</span>
        <span class="review-count">({{ product.review_count }})</span>
      </div>

      <p class="card-desc">{{ product.short_description || product.description?.slice(0, 80) || 'No description available.' }}</p>

      <div class="card-prices">
        <span class="price">${{ Number(product.price).toFixed(2) }}</span>
        <span v-if="product.compare_price" class="compare">${{ Number(product.compare_price).toFixed(2) }}</span>
      </div>

      <div class="card-actions">
        <button class="btn-detail" @click="go">
          <i class="fas fa-eye"></i> View
        </button>
        <button class="btn-cart" @click.stop="$emit('add-to-cart', product.id)" :disabled="product.stock_quantity < 1">
          <i class="fas fa-cart-plus"></i> {{ product.stock_quantity < 1 ? 'Sold Out' : 'Add to Cart' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'

const props = defineProps({ product: Object, wishlisted: Boolean })
defineEmits(['add-to-cart', 'toggle-wishlist'])

const router = useRouter()
const discount = computed(() => {
  if (!props.product.compare_price) return 0
  return Math.round((props.product.compare_price - props.product.price) / props.product.compare_price * 100)
})
const go = () => router.push(`/products/${props.product.id}`)
</script>

<style scoped>
.card { background: white; border: 1px solid #f0e6d8; border-radius: 16px; overflow: hidden; transition: all 0.25s; display: flex; flex-direction: column; }
.card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(200,119,58,0.14); }

.card-img { position: relative; height: 200px; overflow: hidden; cursor: pointer; background: linear-gradient(135deg, #fdf0e6, #f5d5b0); flex-shrink: 0; }
.card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.35s; }
.card:hover .card-img img { transform: scale(1.06); }
.card-img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 4rem; }

.badge-featured { position: absolute; top: 0.65rem; left: 0.65rem; background: #c8773a; color: white; font-size: 0.65rem; font-weight: 600; padding: 0.2rem 0.55rem; border-radius: 50px; }
.badge-sale     { position: absolute; bottom: 0.65rem; left: 0.65rem; background: #ef4444; color: white; font-size: 0.65rem; font-weight: 700; padding: 0.2rem 0.55rem; border-radius: 50px; }
.badge-out      { position: absolute; top: 0.65rem; right: 2.5rem; background: #6b7280; color: white; font-size: 0.65rem; font-weight: 600; padding: 0.2rem 0.55rem; border-radius: 50px; }

.btn-wish { position: absolute; top: 0.6rem; right: 0.65rem; width: 30px; height: 30px; border-radius: 50%; background: white; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.12); transition: all 0.2s; color: #c8a07a; font-size: 0.85rem; }
.btn-wish:hover { transform: scale(1.15); }
.btn-wish.active { color: #ef4444; }
.btn-wish.active i { animation: heartPop 0.25s ease; }
@keyframes heartPop { 0% { transform: scale(1); } 50% { transform: scale(1.4); } 100% { transform: scale(1); } }

.card-body { padding: 1rem 1.1rem 1.1rem; display: flex; flex-direction: column; flex: 1; }
.card-cat  { font-size: 0.7rem; color: #c8773a; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.3rem; }
.card-name { font-family: 'Playfair Display', serif; font-size: 1rem; color: #2d1b0e; margin-bottom: 0.25rem; cursor: pointer; line-height: 1.3; }
.card-name:hover { color: #c8773a; }

.card-rating { display: flex; align-items: center; gap: 0.3rem; margin-bottom: 0.3rem; }
.stars { color: #f59e0b; font-size: 0.75rem; letter-spacing: 1px; }
.rating-num { font-size: 0.75rem; font-weight: 600; color: #5a3e28; }
.review-count { font-size: 0.72rem; color: #9a7560; }

.card-desc { font-size: 0.78rem; color: #9a7560; line-height: 1.55; margin-bottom: 0.75rem; flex: 1; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.card-prices { display: flex; align-items: baseline; gap: 0.45rem; margin-bottom: 0.9rem; }
.price   { font-size: 1.1rem; font-weight: 700; color: #c8773a; }
.compare { font-size: 0.8rem; color: #bba090; text-decoration: line-through; }

.card-actions { display: flex; gap: 0.5rem; }
.btn-detail { flex: 1; display: flex; align-items: center; justify-content: center; gap: 0.35rem; padding: 0.5rem 0; border: 1.5px solid #e8d5c0; background: white; color: #8b5e3c; border-radius: 50px; font-size: 0.76rem; font-weight: 600; cursor: pointer; transition: all 0.2s; }
.btn-detail:hover { border-color: #c8773a; color: #c8773a; background: #fdf5ef; }
.btn-cart { flex: 1; display: flex; align-items: center; justify-content: center; gap: 0.35rem; padding: 0.5rem 0; background: linear-gradient(135deg, #c8773a, #e8945a); color: white; border: none; border-radius: 50px; font-size: 0.76rem; font-weight: 600; cursor: pointer; transition: all 0.2s; }
.btn-cart:hover:not(:disabled) { filter: brightness(1.08); transform: scale(1.02); }
.btn-cart:disabled { background: #d4c4b0; cursor: not-allowed; }
</style>
