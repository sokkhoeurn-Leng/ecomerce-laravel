<template>
  <div class="auth-page">
    <!-- Left Panel -->
    <div class="auth-left">
      <div class="auth-left-inner">
        <div class="auth-brand">
          <span class="brand-icon">🎂</span>
          <span class="brand-name">Sweet<span>Bliss</span></span>
        </div>
        <h2>Welcome back!</h2>
        <p>Sign in to explore our delicious handcrafted cakes and pastries.</p>
        <div class="auth-deco">
          <div class="deco-cake">🍰</div>
          <div class="deco-cake">🧁</div>
          <div class="deco-cake">🎂</div>
        </div>
      </div>
    </div>

    <!-- Right Panel - Form -->
    <div class="auth-right">
      <div class="auth-form-wrap">
        <div class="form-header">
          <h1>Sign In</h1>
          <p>Don't have an account? <router-link to="/register">Create one</router-link></p>
        </div>

        <form @submit.prevent="handleLogin" class="auth-form">
          <div class="hint-box">
            <i class="fas fa-info-circle"></i>
            Admin: admin@example.com / password
          </div>

          <div class="field">
            <label>Email address</label>
            <div class="input-wrap">
              <i class="fas fa-envelope"></i>
              <input type="email" v-model="form.email" placeholder="you@example.com" required />
            </div>
          </div>

          <div class="field">
            <label>Password</label>
            <div class="input-wrap">
              <i class="fas fa-lock"></i>
              <input :type="showPass ? 'text' : 'password'" v-model="form.password" placeholder="••••••••" required />
              <button type="button" class="toggle-pass" @click="showPass = !showPass">
                <i :class="showPass ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
              </button>
            </div>
          </div>

          <div v-if="error" class="error-msg">
            <i class="fas fa-exclamation-circle"></i> {{ error }}
          </div>

          <button type="submit" class="submit-btn" :disabled="loading">
            <span v-if="loading"><i class="fas fa-spinner fa-spin"></i> Signing in...</span>
            <span v-else>Sign In 🍰</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const form = ref({ email: '', password: '' })
const error = ref(null)
const loading = ref(false)
const showPass = ref(false)

const handleLogin = async () => {
  loading.value = true
  error.value = null
  try {
    const response = await axios.post('/login', form.value)
    localStorage.setItem('token', response.data.token)
    localStorage.setItem('user', JSON.stringify(response.data.user))
    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
    router.push(response.data.user?.is_admin ? '/admin' : '/')
  } catch (err) {
    error.value = err.response?.data?.message || 'Login failed'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.auth-page {
  min-height: calc(100vh - 70px);
  display: flex;
}

/* Left */
.auth-left {
  flex: 1;
  background: linear-gradient(145deg, #2d1b0e 0%, #5a3520 50%, #8b5e3c 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 3rem;
  position: relative;
  overflow: hidden;
}

.auth-left::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at 30% 70%, rgba(200,119,58,0.25), transparent 60%);
}

.auth-left-inner {
  position: relative;
  z-index: 1;
  color: white;
  max-width: 380px;
}

.auth-brand {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  margin-bottom: 2.5rem;
}

.brand-icon { font-size: 2rem; }

.brand-name {
  font-family: 'Playfair Display', serif;
  font-size: 1.6rem;
  font-weight: 700;
  color: white;
}

.brand-name span { color: #f5c87a; }

.auth-left h2 {
  font-family: 'Playfair Display', serif;
  font-size: 2rem;
  margin-bottom: 1rem;
  color: #fdf0e6;
}

.auth-left p {
  color: #c4a07a;
  line-height: 1.7;
  font-size: 0.95rem;
}

.auth-deco {
  display: flex;
  gap: 1.5rem;
  margin-top: 3rem;
  font-size: 2.5rem;
}

.deco-cake {
  animation: float 3s ease-in-out infinite;
  filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3));
}

.deco-cake:nth-child(2) { animation-delay: 0.5s; }
.deco-cake:nth-child(3) { animation-delay: 1s; }

@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

/* Right */
.auth-right {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 3rem;
  background: #fdf8f3;
}

.auth-form-wrap { width: 100%; max-width: 420px; }

.form-header { margin-bottom: 2rem; }

.form-header h1 {
  font-family: 'Playfair Display', serif;
  font-size: 2rem;
  font-weight: 700;
  color: #2d1b0e;
  margin-bottom: 0.5rem;
}

.form-header p { color: #9a7560; font-size: 0.9rem; }

.form-header a { color: #c8773a; font-weight: 500; text-decoration: none; }
.form-header a:hover { text-decoration: underline; }

.hint-box {
  background: #fdf0e6;
  border: 1px solid #f0d5ba;
  border-radius: 10px;
  padding: 0.75rem 1rem;
  font-size: 0.82rem;
  color: #8b5e3c;
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.field { margin-bottom: 1.25rem; }

.field label {
  display: block;
  font-size: 0.875rem;
  font-weight: 500;
  color: #4a3220;
  margin-bottom: 0.5rem;
}

.input-wrap {
  position: relative;
  display: flex;
  align-items: center;
}

.input-wrap > i:first-child {
  position: absolute;
  left: 1rem;
  color: #c8a07a;
  font-size: 0.875rem;
  pointer-events: none;
}

.input-wrap input {
  width: 100%;
  padding: 0.8rem 1rem 0.8rem 2.75rem;
  border: 1.5px solid #e8d5c0;
  border-radius: 12px;
  font-size: 0.9rem;
  background: white;
  color: #2d1b0e;
  transition: all 0.2s;
  outline: none;
  font-family: 'Inter', sans-serif;
}

.input-wrap input:focus {
  border-color: #c8773a;
  box-shadow: 0 0 0 3px rgba(200,119,58,0.12);
}

.input-wrap input::placeholder { color: #c4a07a; }

.toggle-pass {
  position: absolute;
  right: 1rem;
  background: none;
  border: none;
  color: #c8a07a;
  cursor: pointer;
  font-size: 0.875rem;
  padding: 0;
}

.toggle-pass:hover { color: #c8773a; }

.error-msg {
  background: #fef2f2;
  border: 1px solid #fecaca;
  color: #dc2626;
  border-radius: 10px;
  padding: 0.75rem 1rem;
  font-size: 0.85rem;
  margin-bottom: 1.25rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.submit-btn {
  width: 100%;
  padding: 0.9rem;
  background: linear-gradient(135deg, #c8773a, #e8945a);
  color: white;
  border: none;
  border-radius: 12px;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.25s;
  box-shadow: 0 4px 16px rgba(200,119,58,0.3);
  font-family: 'Inter', sans-serif;
}

.submit-btn:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 6px 22px rgba(200,119,58,0.4);
}

.submit-btn:disabled { opacity: 0.7; cursor: not-allowed; }

@media (max-width: 768px) {
  .auth-page { flex-direction: column; }
  .auth-left { min-height: 200px; flex: none; }
}
</style>
