import { reactive } from 'vue'
import api from '../services/api'

export const cart = reactive({
  items: [],
  total: 0,
  count: 0,
  loading: false,

  async fetch() {
    if (!localStorage.getItem('token')) return
    try {
      this.loading = true
      const { data } = await api.get('/cart')
      this.items = data.items
      this.total = data.total
      this.count = data.count
    } catch {}
    finally { this.loading = false }
  },

  async add(productId, quantity = 1) {
    await api.post('/cart', { product_id: productId, quantity })
    await this.fetch()
  },

  async update(cartId, quantity) {
    await api.put(`/cart/${cartId}`, { quantity })
    await this.fetch()
  },

  async remove(cartId) {
    await api.delete(`/cart/${cartId}`)
    await this.fetch()
  },

  async clear() {
    await api.delete('/cart')
    this.items = []; this.total = 0; this.count = 0
  }
})
