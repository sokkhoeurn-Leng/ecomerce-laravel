import { reactive } from 'vue'
import api from '../services/api'

export const auth = reactive({
  user: JSON.parse(localStorage.getItem('user')),
  token: localStorage.getItem('token'),

  get isLoggedIn() { return !!this.token },
  get isAdmin()    { return this.user?.is_admin },

  setAuth(user, token) {
    this.user  = user
    this.token = token
    localStorage.setItem('user', JSON.stringify(user))
    localStorage.setItem('token', token)
    api.defaults.headers.common['Authorization'] = `Bearer ${token}`
  },

  async logout() {
    try { await api.post('/logout') } catch {}
    this.user  = null
    this.token = null
    localStorage.removeItem('user')
    localStorage.removeItem('token')
    delete api.defaults.headers.common['Authorization']
  },

  async refresh() {
    if (!this.token) return
    try {
      const { data } = await api.get('/user')
      this.user = data.user
      localStorage.setItem('user', JSON.stringify(data.user))
    } catch {
      await this.logout()
    }
  }
})
