<template>
  <div class="callback-page">
    <div class="callback-container">
      <div v-if="loading" class="loading-state">
        <div class="spinner"></div>
        <p>Completing authentication...</p>
      </div>
      
      <div v-else-if="error" class="error-state">
        <i class="fas fa-exclamation-circle"></i>
        <h2>Authentication Failed</h2>
        <p>{{ error }}</p>
        <router-link to="/login" class="back-btn">
          <i class="fas fa-arrow-left"></i>
          Back to Login
        </router-link>
      </div>

      <div v-else class="success-state">
        <i class="fas fa-check-circle"></i>
        <h2>Welcome!</h2>
        <p>Redirecting...</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const loading = ref(true)
const error = ref(null)

onMounted(() => {
  const urlParams = new URLSearchParams(window.location.search)
  const token = urlParams.get('token')
  const userData = urlParams.get('user')

  if (token && userData) {
    try {
      const user = JSON.parse(atob(userData))
      
      // Store token and user data
      localStorage.setItem('token', token)
      localStorage.setItem('user', JSON.stringify(user))
      
      // Set default authorization header
      axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
      
      // Redirect to home or admin dashboard
      setTimeout(() => {
        router.push(user?.is_admin ? '/admin' : '/')
      }, 1000)
    } catch (err) {
      error.value = 'Invalid authentication data received.'
      loading.value = false
    }
  } else {
    error.value = 'No authentication data received. Please try again.'
    loading.value = false
  }
})
</script>

<style scoped>
.callback-page {
  min-height: calc(100vh - 70px);
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #fefaf6 0%, #fdf0e6 100%);
  font-family: 'Inter', sans-serif;
}

.callback-container {
  text-align: center;
  padding: 3rem;
  background: white;
  border-radius: 20px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.08);
  max-width: 400px;
  width: 90%;
}

.loading-state,
.error-state,
.success-state {
  padding: 1rem;
}

.spinner {
  width: 50px;
  height: 50px;
  border: 4px solid #f0d5ba;
  border-top-color: #c8773a;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 1.5rem;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.loading-state p,
.success-state p {
  color: #9a7560;
  font-size: 0.95rem;
  margin-top: 1rem;
}

.error-state i {
  font-size: 3rem;
  color: #dc2626;
  margin-bottom: 1rem;
}

.error-state h2 {
  font-family: 'Playfair Display', serif;
  color: #1a0e06;
  margin-bottom: 0.5rem;
}

.error-state p {
  color: #9a7560;
  font-size: 0.9rem;
  margin-bottom: 1.5rem;
}

.back-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background: linear-gradient(135deg, #c8773a 0%, #e0914e 100%);
  color: white;
  text-decoration: none;
  border-radius: 12px;
  font-weight: 600;
  transition: all 0.3s;
}

.back-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 20px rgba(200,119,58,0.3);
}

.success-state i {
  font-size: 3rem;
  color: #10b981;
  margin-bottom: 1rem;
}

.success-state h2 {
  font-family: 'Playfair Display', serif;
  color: #1a0e06;
  margin-bottom: 0.5rem;
}
</style>