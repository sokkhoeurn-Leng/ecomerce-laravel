<template>
  <div class="auth-page">
    <!-- Left Panel -->
    <div class="auth-left">
      <!-- Floating particles -->
      <div class="particles">
        <span v-for="n in 12" :key="n" class="particle" :style="particleStyle(n)"></span>
      </div>

      <!-- Background images collage -->
      <div class="bg-collage">
        <div class="collage-item c1">
          <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500&h=600&fit=crop" alt="Cake" />
        </div>
        <div class="collage-item c2">
          <img src="https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?w=400&h=500&fit=crop" alt="Cupcakes" />
        </div>
        <div class="collage-item c3">
          <img src="https://images.unsplash.com/photo-1488477181946-6428a0291777?w=400&h=500&fit=crop" alt="Pastry" />
        </div>
        <div class="collage-item c4">
          <img src="https://images.unsplash.com/photo-1558636508-e0db3814bd1d?w=400&h=400&fit=crop" alt="Strawberry cake" />
        </div>
      </div>

      <!-- Dark overlay gradient -->
      <div class="left-overlay"></div>

      <div class="auth-left-inner">
        <div class="auth-brand">
          <div class="brand-logo">
            <img src="https://images.unsplash.com/photo-1562440499-64c9a111f713?w=80&h=80&fit=crop" alt="Logo" />
          </div>
          <span class="brand-name">Sweet<span>Bliss</span></span>
        </div>
        <h2>Welcome back!</h2>
        <p>Sign in to explore our delicious handcrafted cakes and pastries, made with love from the finest ingredients.</p>

        <div class="social-proof">
          <div class="proof-avatars">
            <img src="https://picsum.photos/seed/user1/40/40.jpg" alt="" />
            <img src="https://picsum.photos/seed/user2/40/40.jpg" alt="" />
            <img src="https://picsum.photos/seed/user3/40/40.jpg" alt="" />
            <img src="https://picsum.photos/seed/user4/40/40.jpg" alt="" />
            <span class="avatar-more">+2k</span>
          </div>
          <p>Joined by <strong>2,400+</strong> happy customers</p>
        </div>

        <div class="trust-badges">
          <div class="badge">
            <i class="fas fa-star"></i>
            <span>4.9 Rating</span>
          </div>
          <div class="badge">
            <i class="fas fa-truck"></i>
            <span>Free Delivery</span>
          </div>
          <div class="badge">
            <i class="fas fa-leaf"></i>
            <span>Fresh Daily</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Panel - Form -->
    <div class="auth-right">
      <!-- Subtle background pattern -->
      <div class="right-pattern"></div>

      <div class="auth-form-wrap">
        <div class="form-header">
          <div class="form-icon">
            <i class="fas fa-user-circle"></i>
          </div>
          <h1>Sign In</h1>
          <p>Welcome back! Please enter your details.</p>
        </div>

        <div class="hint-box">
          <i class="fas fa-lightbulb"></i>
          <div>
            <strong>Demo Account</strong><br />
            admin@example.com &nbsp;/&nbsp; password123
          </div>
        </div>

        <form @submit.prevent="handleLogin" class="auth-form">
          <div class="field">
            <label>Email address</label>
            <div class="input-wrap" :class="{ focused: focusEmail, filled: form.email }">
              <i class="fas fa-envelope"></i>
              <input
                type="email"
                v-model="form.email"
                placeholder="you@example.com"
                required
                @focus="focusEmail = true"
                @blur="focusEmail = false"
              />
              <div class="input-line"></div>
            </div>
          </div>

          <div class="field">
            <label>Password</label>
            <div class="input-wrap" :class="{ focused: focusPass, filled: form.password }">
              <i class="fas fa-lock"></i>
              <input
                :type="showPass ? 'text' : 'password'"
                v-model="form.password"
                placeholder="••••••••"
                required
                @focus="focusPass = true"
                @blur="focusPass = false"
              />
              <button type="button" class="toggle-pass" @click="showPass = !showPass">
                <i :class="showPass ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
              </button>
              <div class="input-line"></div>
            </div>
          </div>

          <div class="form-options">
            <label class="remember-me">
              <input type="checkbox" v-model="remember" />
              <span class="checkmark"></span>
              Remember me
            </label>
            <a href="#" class="forgot-link">Forgot password?</a>
          </div>

          <div v-if="error" class="error-msg">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ error }}</span>
          </div>

          <button type="submit" class="submit-btn" :disabled="loading">
            <span v-if="loading" class="btn-loading">
              <i class="fas fa-spinner fa-spin"></i>
              Signing in...
            </span>
            <span v-else class="btn-idle">
              <span>Sign In</span>
              <i class="fas fa-arrow-right"></i>
            </span>
          </button>
        </form>

        <div class="divider">
          <span>or continue with</span>
        </div>

        <div class="social-buttons">
          <button type="button" class="social-btn google" @click="loginWithGoogle">
            <svg width="18" height="18" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
            Google
          </button>
          <button type="button" class="social-btn facebook" @click="loginWithFacebook">
            <i class="fab fa-facebook-f"></i>
            Facebook
          </button>
        </div>

        <p class="signup-text">
          Don't have an account?
          <router-link to="/register">Create one free</router-link>
        </p>
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
const remember = ref(false)
const focusEmail = ref(false)
const focusPass = ref(false)

const particleStyle = (n) => {
  const size = Math.random() * 6 + 3
  return {
    width: `${size}px`,
    height: `${size}px`,
    left: `${Math.random() * 100}%`,
    top: `${Math.random() * 100}%`,
    animationDelay: `${Math.random() * 5}s`,
    animationDuration: `${Math.random() * 4 + 4}s`
  }
}

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
    error.value = err.response?.data?.message || 'Login failed. Please check your credentials.'
  } finally {
    loading.value = false
  }
}

const loginWithGoogle = () => {
  window.location.href = '/api/auth/google'
}

const loginWithFacebook = () => {
  window.location.href = '/api/auth/facebook'
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap');

.auth-page {
  min-height: calc(100vh - 70px);
  display: flex;
  font-family: 'Inter', sans-serif;
}

/* ===== LEFT PANEL ===== */
.auth-left {
  flex: 1;
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: flex-end;
  padding: 3rem;
}

/* Background collage */
.bg-collage {
  position: absolute;
  inset: 0;
}

.collage-item {
  position: absolute;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(0,0,0,0.4);
  transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1);
}

.collage-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.collage-item:hover { transform: scale(1.03) rotate(0deg) !important; }

.c1 {
  width: 55%;
  height: 70%;
  top: 5%;
  left: -5%;
  transform: rotate(-4deg);
  z-index: 2;
}

.c2 {
  width: 50%;
  height: 55%;
  top: 0%;
  right: -8%;
  transform: rotate(5deg);
  z-index: 1;
}

.c3 {
  width: 45%;
  height: 50%;
  bottom: 0%;
  left: 10%;
  transform: rotate(3deg);
  z-index: 1;
}

.c4 {
  width: 40%;
  height: 40%;
  bottom: 5%;
  right: 0%;
  transform: rotate(-6deg);
  z-index: 3;
}

/* Dark overlay */
.left-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    180deg,
    rgba(30, 15, 5, 0.65) 0%,
    rgba(45, 27, 14, 0.85) 50%,
    rgba(30, 15, 5, 0.95) 100%
  );
  z-index: 4;
}

/* Floating particles */
.particles {
  position: absolute;
  inset: 0;
  z-index: 5;
  pointer-events: none;
}

.particle {
  position: absolute;
  background: rgba(245, 200, 122, 0.5);
  border-radius: 50%;
  animation: particleFloat linear infinite;
}

@keyframes particleFloat {
  0% { transform: translateY(0) scale(1); opacity: 0; }
  10% { opacity: 1; }
  90% { opacity: 1; }
  100% { transform: translateY(-100vh) scale(0.3); opacity: 0; }
}

/* Content */
.auth-left-inner {
  position: relative;
  z-index: 6;
  color: white;
  max-width: 400px;
  margin-bottom: 2rem;
}

.auth-brand {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 2rem;
}

.brand-logo {
  width: 48px;
  height: 48px;
  border-radius: 14px;
  overflow: hidden;
  border: 2px solid rgba(245, 200, 122, 0.5);
  box-shadow: 0 4px 20px rgba(0,0,0,0.3);
  flex-shrink: 0;
}

.brand-logo img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.brand-name {
  font-family: 'Playfair Display', serif;
  font-size: 1.7rem;
  font-weight: 700;
  color: white;
  letter-spacing: -0.02em;
}

.brand-name span {
  color: #f5c87a;
  font-style: italic;
}

.auth-left h2 {
  font-family: 'Playfair Display', serif;
  font-size: 2.4rem;
  font-weight: 800;
  margin-bottom: 0.75rem;
  color: #fff;
  line-height: 1.15;
  letter-spacing: -0.02em;
}

.auth-left > .auth-left-inner > p {
  color: rgba(255,255,255,0.7);
  line-height: 1.7;
  font-size: 0.92rem;
  margin-bottom: 2rem;
}

/* Social proof */
.social-proof {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
}

.proof-avatars {
  display: flex;
  align-items: center;
}

.proof-avatars img {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  border: 2px solid rgba(255,255,255,0.3);
  margin-left: -8px;
  object-fit: cover;
}

.proof-avatars img:first-child { margin-left: 0; }

.avatar-more {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: rgba(245, 200, 122, 0.25);
  border: 2px solid rgba(245, 200, 122, 0.5);
  margin-left: -8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.55rem;
  font-weight: 700;
  color: #f5c87a;
}

.social-proof p {
  color: rgba(255,255,255,0.6);
  font-size: 0.8rem;
}

.social-proof strong {
  color: #f5c87a;
}

/* Trust badges */
.trust-badges {
  display: flex;
  gap: 0.75rem;
}

.badge {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  background: rgba(255,255,255,0.08);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 50px;
  padding: 0.4rem 0.85rem;
  font-size: 0.72rem;
  color: rgba(255,255,255,0.8);
}

.badge i {
  color: #f5c87a;
  font-size: 0.7rem;
}

/* ===== RIGHT PANEL ===== */
.auth-right {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 3rem 2rem;
  background: #fefaf6;
  position: relative;
  overflow: hidden;
}

.right-pattern {
  position: absolute;
  inset: 0;
  background-image:
    radial-gradient(circle at 20% 20%, rgba(200, 119, 58, 0.04) 0%, transparent 50%),
    radial-gradient(circle at 80% 80%, rgba(200, 119, 58, 0.03) 0%, transparent 50%),
    radial-gradient(circle at 50% 50%, rgba(200, 119, 58, 0.02) 0%, transparent 70%);
  pointer-events: none;
}

/* Decorative corner cake image */
.auth-right::before {
  content: '';
  position: absolute;
  top: -40px;
  right: -40px;
  width: 200px;
  height: 200px;
  background: url('https://images.unsplash.com/photo-1588195538326-c5b1e9f80a1b?w=200&h=200&fit=crop') center/cover;
  border-radius: 0 0 0 100%;
  opacity: 0.08;
  pointer-events: none;
}

.auth-right::after {
  content: '';
  position: absolute;
  bottom: -30px;
  left: -30px;
  width: 150px;
  height: 150px;
  background: url('https://images.unsplash.com/photo-1558301211-0d8c8ddee6ec?w=150&h=150&fit=crop') center/cover;
  border-radius: 0 100% 0 0;
  opacity: 0.06;
  pointer-events: none;
}

.auth-form-wrap {
  width: 100%;
  max-width: 420px;
  position: relative;
  z-index: 1;
}

.form-header {
  text-align: center;
  margin-bottom: 1.75rem;
}

.form-icon {
  width: 56px;
  height: 56px;
  border-radius: 16px;
  background: linear-gradient(135deg, rgba(200, 119, 58, 0.1), rgba(200, 119, 58, 0.05));
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 1rem;
}

.form-icon i {
  font-size: 1.5rem;
  color: #c8773a;
}

.form-header h1 {
  font-family: 'Playfair Display', serif;
  font-size: 2rem;
  font-weight: 800;
  color: #1a0e06;
  margin-bottom: 0.35rem;
  letter-spacing: -0.02em;
}

.form-header p {
  color: #9a7560;
  font-size: 0.88rem;
}

/* Hint box */
.hint-box {
  background: linear-gradient(135deg, #fef6ee, #fdf0e6);
  border: 1px solid #f0d5ba;
  border-radius: 14px;
  padding: 0.85rem 1.1rem;
  font-size: 0.8rem;
  color: #8b5e3c;
  margin-bottom: 1.5rem;
  display: flex;
  align-items: flex-start;
  gap: 0.6rem;
  line-height: 1.5;
}

.hint-box i {
  color: #e8945a;
  margin-top: 2px;
  flex-shrink: 0;
}

.hint-box strong {
  color: #6b3f1f;
}

/* Fields */
.field {
  margin-bottom: 1.25rem;
}

.field label {
  display: block;
  font-size: 0.82rem;
  font-weight: 600;
  color: #3d2415;
  margin-bottom: 0.45rem;
  letter-spacing: 0.01em;
}

.input-wrap {
  position: relative;
  display: flex;
  align-items: center;
  border-radius: 14px;
  transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
}

.input-wrap > i:first-child {
  position: absolute;
  left: 1rem;
  color: #c4a07a;
  font-size: 0.85rem;
  pointer-events: none;
  transition: color 0.3s;
  z-index: 2;
}

.input-wrap.focused > i:first-child {
  color: #c8773a;
}

.input-wrap input {
  width: 100%;
  padding: 0.85rem 3rem 0.85rem 2.8rem;
  border: 1.5px solid #ead9c6;
  border-radius: 14px;
  font-size: 0.88rem;
  background: white;
  color: #1a0e06;
  transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
  outline: none;
  font-family: 'Inter', sans-serif;
}

.input-wrap.focused input {
  border-color: #c8773a;
  box-shadow: 0 0 0 4px rgba(200,119,58,0.1), 0 2px 8px rgba(200,119,58,0.08);
}

.input-wrap.filled input {
  border-color: #d4b896;
}

.input-wrap input::placeholder {
  color: #c4a07a;
  font-weight: 400;
}

.toggle-pass {
  position: absolute;
  right: 0.75rem;
  background: rgba(200, 119, 58, 0.06);
  border: none;
  color: #c8a07a;
  cursor: pointer;
  font-size: 0.8rem;
  padding: 0.4rem 0.5rem;
  border-radius: 8px;
  transition: all 0.2s;
  z-index: 2;
}

.toggle-pass:hover {
  color: #c8773a;
  background: rgba(200, 119, 58, 0.12);
}

/* Form options row */
.form-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.remember-me {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.82rem;
  color: #6b4a32;
  cursor: pointer;
  user-select: none;
  position: relative;
  padding-left: 0;
}

.remember-me input {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

.checkmark {
  width: 18px;
  height: 18px;
  border: 1.5px solid #d4b896;
  border-radius: 5px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
  flex-shrink: 0;
  background: white;
}

.remember-me input:checked ~ .checkmark {
  background: #c8773a;
  border-color: #c8773a;
}

.remember-me input:checked ~ .checkmark::after {
  content: '';
  width: 5px;
  height: 9px;
  border: solid white;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg) translateY(-1px);
}

.forgot-link {
  font-size: 0.82rem;
  color: #c8773a;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s;
}

.forgot-link:hover {
  color: #a05c28;
  text-decoration: underline;
}

/* Error */
.error-msg {
  background: linear-gradient(135deg, #fef2f2, #fff1f1);
  border: 1px solid #fecaca;
  color: #dc2626;
  border-radius: 12px;
  padding: 0.75rem 1rem;
  font-size: 0.84rem;
  margin-bottom: 1.25rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  animation: shakeIn 0.4s ease;
}

@keyframes shakeIn {
  0%, 100% { transform: translateX(0); }
  20% { transform: translateX(-6px); }
  40% { transform: translateX(6px); }
  60% { transform: translateX(-4px); }
  80% { transform: translateX(4px); }
}

/* Submit button */
.submit-btn {
  width: 100%;
  padding: 0.95rem;
  background: linear-gradient(135deg, #c8773a 0%, #e0914e 50%, #d4803f 100%);
  background-size: 200% 200%;
  color: white;
  border: none;
  border-radius: 14px;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.35s cubic-bezier(0.23, 1, 0.32, 1);
  box-shadow: 0 4px 20px rgba(200,119,58,0.3), inset 0 1px 0 rgba(255,255,255,0.15);
  font-family: 'Inter', sans-serif;
  position: relative;
  overflow: hidden;
}

.submit-btn::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, transparent 40%, rgba(255,255,255,0.15) 50%, transparent 60%);
  background-size: 250% 250%;
  animation: shimmer 3s ease-in-out infinite;
}

@keyframes shimmer {
  0% { background-position: 200% 200%; }
  100% { background-position: -200% -200%; }
}

.submit-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 30px rgba(200,119,58,0.4), inset 0 1px 0 rgba(255,255,255,0.2);
  background-position: 100% 100%;
}

.submit-btn:active:not(:disabled) {
  transform: translateY(0);
  box-shadow: 0 2px 10px rgba(200,119,58,0.3);
}

.submit-btn:disabled {
  opacity: 0.75;
  cursor: not-allowed;
}

.btn-idle, .btn-loading {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  position: relative;
  z-index: 1;
}

.btn-idle i {
  transition: transform 0.3s;
}

.submit-btn:hover:not(:disabled) .btn-idle i {
  transform: translateX(3px);
}

/* Divider */
.divider {
  display: flex;
  align-items: center;
  margin: 1.5rem 0;
  gap: 1rem;
}

.divider::before,
.divider::after {
  content: '';
  flex: 1;
  height: 1px;
  background: linear-gradient(90deg, transparent, #e8d5c0, transparent);
}

.divider span {
  font-size: 0.78rem;
  color: #b89b82;
  white-space: nowrap;
}

/* Social buttons */
.social-buttons {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
}

.social-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.75rem;
  border-radius: 12px;
  border: 1.5px solid #e8d5c0;
  background: white;
  font-size: 0.82rem;
  font-weight: 500;
  color: #4a3220;
  cursor: pointer;
  transition: all 0.25s;
  font-family: 'Inter', sans-serif;
}

.social-btn:hover {
  border-color: #c8773a;
  background: #fefaf6;
  box-shadow: 0 2px 12px rgba(200,119,58,0.1);
  transform: translateY(-1px);
}

.social-btn.google svg {
  flex-shrink: 0;
}

.social-btn.facebook i {
  color: #1877F2;
  font-size: 0.95rem;
}

/* Signup text */
.signup-text {
  text-align: center;
  font-size: 0.85rem;
  color: #9a7560;
}

.signup-text a {
  color: #c8773a;
  font-weight: 600;
  text-decoration: none;
  transition: color 0.2s;
}

.signup-text a:hover {
  color: #a05c28;
  text-decoration: underline;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
  .c1 { width: 60%; height: 60%; }
  .c2 { width: 50%; height: 45%; }
  .c3 { width: 40%; height: 40%; }
  .c4 { display: none; }
  .trust-badges { flex-wrap: wrap; }
}

@media (max-width: 768px) {
  .auth-page { flex-direction: column; }
  .auth-left {
    min-height: 260px;
    flex: none;
    align-items: flex-end;
    padding: 2rem;
  }
  .c1 { width: 70%; height: 80%; top: -10%; left: -10%; }
  .c2 { width: 55%; height: 60%; top: -5%; right: -15%; }
  .c3 { display: none; }
  .c4 { display: none; }
  .auth-left h2 { font-size: 1.8rem; }
  .trust-badges { display: none; }
  .social-proof { display: none; }
  .auth-right { padding: 2rem 1.5rem; }
}
</style>