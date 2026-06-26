<template>
  <div class="auth-page">
    <!-- Left Panel -->
    <div class="auth-left">
      <div class="auth-left-inner">
        <div class="auth-brand">
          <span class="brand-icon">🎂</span>
          <span class="brand-name">Sweet<span>Bliss</span></span>
        </div>
        <h2>Join our sweet community!</h2>
        <p>Create an account and enjoy exclusive deals, early access to new flavours, and more.</p>
        <div class="perks">
          <div class="perk"><span>🎁</span> 10% off your first order</div>
          <div class="perk"><span>🚚</span> Free delivery over $50</div>
          <div class="perk"><span>🎂</span> Birthday surprise every year</div>
        </div>
      </div>
    </div>

    <!-- Right Panel - Form -->
    <div class="auth-right">
      <div class="auth-form-wrap">
        <div class="form-header">
          <h1>Create Account</h1>
          <p>Already have an account? <router-link to="/login">Sign in</router-link></p>
        </div>

        <form @submit.prevent="handleRegister" class="auth-form">
          <div class="field">
            <label>Full Name</label>
            <div class="input-wrap">
              <i class="fas fa-user"></i>
              <input type="text" v-model="form.name" placeholder="Jane Doe" required />
            </div>
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
              <input :type="showPass ? 'text' : 'password'" v-model="form.password" placeholder="Min. 8 characters" required />
              <button type="button" class="toggle-pass" @click="showPass = !showPass">
                <i :class="showPass ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
              </button>
            </div>
          </div>

          <div class="field">
            <label>Confirm Password</label>
            <div class="input-wrap">
              <i class="fas fa-lock"></i>
              <input :type="showConfirm ? 'text' : 'password'" v-model="form.password_confirmation" placeholder="Repeat password" required />
              <button type="button" class="toggle-pass" @click="showConfirm = !showConfirm">
                <i :class="showConfirm ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
              </button>
            </div>
          </div>

          <div v-if="error" class="error-msg">
            <i class="fas fa-exclamation-circle"></i> {{ error }}
          </div>

          <button type="submit" class="submit-btn" :disabled="loading">
            <span v-if="loading"><i class="fas fa-spinner fa-spin"></i> Creating account...</span>
            <span v-else>Create Account 🧁</span>
          </button>

          <p class="terms">By registering, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.</p>
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
const form = ref({ name: '', email: '', password: '', password_confirmation: '' })
const error = ref(null)
const loading = ref(false)
const showPass = ref(false)
const showConfirm = ref(false)

const handleRegister = async () => {
  loading.value = true
  error.value = null
  try {
    const response = await axios.post('/register', form.value)
    localStorage.setItem('token', response.data.token)
    localStorage.setItem('user', JSON.stringify(response.data.user))
    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
    router.push('/')
  } catch (err) {
    error.value = err.response?.data?.message || 'Registration failed'
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
  background: radial-gradient(circle at 70% 30%, rgba(200,119,58,0.25), transparent 60%);
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
  font-size: 1.8rem;
  margin-bottom: 1rem;
  color: #fdf0e6;
  line-height: 1.3;
}

.auth-left p { color: #c4a07a; line-height: 1.7; font-size: 0.95rem; margin-bottom: 2rem; }

.perks { display: flex; flex-direction: column; gap: 0.9rem; }

.perk {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 0.88rem;
  color: #e8cfa8;
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.1);
  padding: 0.65rem 1rem;
  border-radius: 10px;
}

.perk span { font-size: 1.1rem; }

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
  margin-bottom: 1rem;
}

.submit-btn:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 6px 22px rgba(200,119,58,0.4);
}

.submit-btn:disabled { opacity: 0.7; cursor: not-allowed; }

.terms { font-size: 0.78rem; color: #9a7560; text-align: center; line-height: 1.5; }
.terms a { color: #c8773a; text-decoration: none; }
.terms a:hover { text-decoration: underline; }

@media (max-width: 768px) {
  .auth-page { flex-direction: column; }
  .auth-left { min-height: 220px; flex: none; }
}
</style>
