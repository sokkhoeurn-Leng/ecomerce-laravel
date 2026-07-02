<template>
  <div class="auth-page">
    <!-- Left Panel -->
    <div class="auth-left">
      <!-- Grain texture -->
      <div class="grain"></div>

      <!-- Geometric accents -->
      <div class="geo-accent g1"></div>
      <div class="geo-accent g2"></div>
      <div class="geo-accent g3"></div>

      <!-- Floating particles -->
      <div class="particles">
        <span v-for="n in 16" :key="n" class="particle" :style="particleStyle(n)"></span>
      </div>

      <!-- Background images collage -->
      <div class="bg-collage">
        <div class="collage-item c1" @mouseenter="parallaxHover($event, 4)" @mouseleave="parallaxReset($event)" @mousemove="parallaxMove($event, 4)">
          <img src="https://images.unsplash.com/photo-1562440499-64c9a111f713?w=600&h=750&fit=crop" alt="Cake" />
          <div class="collage-shine"></div>
        </div>
        <div class="collage-item c2" @mouseenter="parallaxHover($event, 3)" @mouseleave="parallaxReset($event)" @mousemove="parallaxMove($event, 3)">
          <img src="https://images.unsplash.com/photo-1558301211-0d8c8ddee6ec?w=500&h=600&fit=crop" alt="Pastries" />
          <div class="collage-shine"></div>
        </div>
        <div class="collage-item c3" @mouseenter="parallaxHover($event, 3)" @mouseleave="parallaxReset($event)" @mousemove="parallaxMove($event, 3)">
          <img src="https://images.unsplash.com/photo-1588195538326-c5b1e9f80a1b?w=500&h=600&fit=crop" alt="Cupcakes" />
          <div class="collage-shine"></div>
        </div>
        <div class="collage-item c4" @mouseenter="parallaxHover($event, 2)" @mouseleave="parallaxReset($event)" @mousemove="parallaxMove($event, 2)">
          <img src="https://images.unsplash.com/photo-1535141192574-5d4897c12f40?w=400&h=400&fit=crop" alt="Cake slice" />
          <div class="collage-shine"></div>
        </div>
      </div>

      <!-- Dark overlay -->
      <div class="left-overlay"></div>

      <div class="auth-left-inner">
        <div class="auth-brand">
          <div class="brand-logo">
            <img src="https://images.unsplash.com/photo-1562440499-64c9a111f713?w=80&h=80&fit=crop" alt="Logo" />
            <div class="logo-glow"></div>
          </div>
          <span class="brand-name">Sweet<span>Bliss</span></span>
        </div>

        <h2>Join our sweet community!</h2>
        <p>Create an account and enjoy exclusive deals, early access to new flavours, and so much more.</p>

        <!-- Perks -->
        <div class="perks">
          <div
            class="perk"
            v-for="(perk, i) in perks"
            :key="i"
            :style="{ animationDelay: `${i * 0.15}s` }"
            @mouseenter="perk.hover = true"
            @mouseleave="perk.hover = false"
          >
            <div class="perk-icon" :style="{ background: perk.bg }">
              <i :class="perk.icon"></i>
              <div class="perk-icon-ring" :style="{ borderColor: perk.ring }"></div>
            </div>
            <div class="perk-text">
              <strong>{{ perk.title }}</strong>
              <span>{{ perk.desc }}</span>
            </div>
            <i class="fas fa-chevron-right perk-arrow" :class="{ visible: perk.hover }"></i>
          </div>
        </div>

        <!-- Testimonial card -->
        <div class="testimonial-card">
          <div class="testimonial-stars">
            <i class="fas fa-star" v-for="n in 5" :key="n"></i>
          </div>
          <p class="testimonial-text">"Best cakes I've ever ordered online. The red velvet is absolutely divine!"</p>
          <div class="testimonial-author">
            <img src="https://picsum.photos/seed/testi1/48/48.jpg" alt="" />
            <div>
              <strong>Sarah Mitchell</strong>
              <span>Verified Customer</span>
            </div>
          </div>
        </div>

        <!-- Social proof -->
        <div class="social-proof">
          <div class="proof-avatars">
            <img src="https://picsum.photos/seed/rg1/40/40.jpg" alt="" />
            <img src="https://picsum.photos/seed/rg2/40/40.jpg" alt="" />
            <img src="https://picsum.photos/seed/rg3/40/40.jpg" alt="" />
            <img src="https://picsum.photos/seed/rg4/40/40.jpg" alt="" />
            <img src="https://picsum.photos/seed/rg5/40/40.jpg" alt="" />
            <span class="avatar-more">+2k</span>
          </div>
          <p>Loved by <strong>2,400+</strong> customers</p>
        </div>
      </div>
    </div>

    <!-- Right Panel - Form -->
    <div class="auth-right">
      <div class="right-pattern"></div>
      <div class="right-dots"></div>

      <!-- Corner deco images -->
      <div class="corner-deco top-right">
        <img src="https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=220&h=220&fit=crop" alt="" />
      </div>
      <div class="corner-deco bottom-left">
        <img src="https://images.unsplash.com/photo-1488477181946-6428a0291777?w=180&h=180&fit=crop" alt="" />
      </div>

      <div class="auth-form-wrap">
        <!-- Form completion bar -->
    
        <div class="form-header">
          <div class="form-icon-wrap">
            <div class="form-icon">
              <i class="fas fa-user-plus"></i>
            </div>
            <div class="form-icon-pulse"></div>
          </div>
          <h1>Create Account</h1>
          <p>Fill in your details to get started</p>
        </div>

        <form @submit.prevent="handleRegister" class="auth-form" novalidate>
          <!-- Name field -->
          <div class="field" :class="{ 'field-active': focusName, 'field-filled': form.name }">
            <label for="name">Full Name</label>
            <div class="input-wrap" :class="{ focused: focusName, filled: form.name }">
              <div class="input-icon">
                <i class="fas fa-user"></i>
              </div>
              <input
                id="name"
                type="text"
                v-model="form.name"
                placeholder="Jane Doe"
                required
                @focus="focusName = true"
                @blur="focusName = false"
                autocomplete="name"
              />
              <div class="input-bg-icon">J</div>
            </div>
          </div>

          <!-- Email field -->
          <div class="field" :class="{ 'field-active': focusEmail, 'field-filled': form.email }">
            <label for="email">Email address</label>
            <div class="input-wrap" :class="{ focused: focusEmail, filled: form.email }">
              <div class="input-icon">
                <i class="fas fa-envelope"></i>
              </div>
              <input
                id="email"
                type="email"
                v-model="form.email"
                placeholder="you@example.com"
                required
                @focus="focusEmail = true"
                @blur="focusEmail = false"
                autocomplete="email"
              />
              <div class="input-bg-icon">@</div>
            </div>
          </div>

          <!-- Password field -->
          <div class="field" :class="{ 'field-active': focusPass, 'field-filled': form.password }">
            <label for="password">Password</label>
            <div class="input-wrap" :class="{ focused: focusPass, filled: form.password }">
              <div class="input-icon">
                <i class="fas fa-lock"></i>
              </div>
              <input
                id="password"
                :type="showPass ? 'text' : 'password'"
                v-model="form.password"
                placeholder="Min. 8 characters"
                required
                @focus="focusPass = true"
                @blur="focusPass = false"
                autocomplete="new-password"
              />
              <button type="button" class="toggle-pass" @click="showPass = !showPass" :aria-label="showPass ? 'Hide password' : 'Show password'">
                <i :class="showPass ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
              </button>
            </div>

            <!-- Strength bar -->
            <transition name="strength-fade">
              <div class="strength-bar" v-if="form.password">
                <div class="strength-track">
                  <div class="strength-fill" :style="{ width: strength.percent + '%', background: strength.color }"></div>
                </div>
                <span class="strength-label" :style="{ color: strength.color }">{{ strength.label }}</span>
              </div>
            </transition>

            <!-- Password requirements -->
            <transition name="req-fade">
              <div class="pw-requirements" v-if="focusPass || form.password">
                <div class="req-item" :class="{ met: form.password.length >= 8 }">
                  <i :class="form.password.length >= 8 ? 'fas fa-check-circle' : 'far fa-circle'"></i>
                  At least 8 characters
                </div>
                <div class="req-item" :class="{ met: /[A-Z]/.test(form.password) }">
                  <i :class="/[A-Z]/.test(form.password) ? 'fas fa-check-circle' : 'far fa-circle'"></i>
                  One uppercase letter
                </div>
                <div class="req-item" :class="{ met: /[0-9]/.test(form.password) }">
                  <i :class="/[0-9]/.test(form.password) ? 'fas fa-check-circle' : 'far fa-circle'"></i>
                  One number
                </div>
                <div class="req-item" :class="{ met: /[^A-Za-z0-9]/.test(form.password) }">
                  <i :class="/[^A-Za-z0-9]/.test(form.password) ? 'fas fa-check-circle' : 'far fa-circle'"></i>
                  One special character
                </div>
              </div>
            </transition>
          </div>

          <!-- Confirm password field -->
          <div class="field" :class="{ 'field-active': focusConfirm, 'field-filled': form.password_confirmation }">
            <label for="confirm">Confirm Password</label>
            <div
              class="input-wrap"
              :class="{
                focused: focusConfirm,
                filled: form.password_confirmation,
                'match-error': form.password_confirmation && form.password !== form.password_confirmation,
                'match-ok': form.password_confirmation && form.password === form.password_confirmation
              }"
            >
              <div class="input-icon">
                <i class="fas fa-shield-alt"></i>
              </div>
              <input
                id="confirm"
                :type="showConfirm ? 'text' : 'password'"
                v-model="form.password_confirmation"
                placeholder="Repeat password"
                required
                @focus="focusConfirm = true"
                @blur="focusConfirm = false"
                autocomplete="new-password"
              />
              <button type="button" class="toggle-pass" @click="showConfirm = !showConfirm" :aria-label="showConfirm ? 'Hide password' : 'Show password'">
                <i :class="showConfirm ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
              </button>
              <transition name="match-pop">
                <span v-if="form.password_confirmation && form.password === form.password_confirmation" class="match-icon ok"><i class="fas fa-check-circle"></i></span>
              </transition>
              <transition name="match-pop">
                <span v-if="form.password_confirmation && form.password !== form.password_confirmation" class="match-icon err"><i class="fas fa-times-circle"></i></span>
              </transition>
            </div>
          </div>

          <!-- Error -->
          <transition name="error-slide">
            <div v-if="error" class="error-msg">
              <div class="error-icon-wrap">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
              <div>
                <strong>Registration Failed</strong>
                <span>{{ error }}</span>
              </div>
              <button type="button" class="error-close" @click="error = null">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </transition>

          <button type="submit" class="submit-btn" :disabled="loading || !isFormValid" @click="rippleEffect">
            <span v-if="loading" class="btn-loading">
              <svg class="spinner" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-dasharray="31.416" stroke-dashoffset="10"></circle></svg>
              Creating your account...
            </span>
            <span v-else class="btn-idle">
              <span>Create Account</span>
              <div class="btn-arrow">
                <i class="fas fa-arrow-right"></i>
              </div>
            </span>
          </button>

          <p class="terms">
            <i class="fas fa-shield-alt"></i>
            By registering, you agree to our
            <a href="#">Terms of Service</a> and
            <a href="#">Privacy Policy</a>.
          </p>
        </form>

        <div class="divider">
          <span>or sign up with</span>
        </div>

        <div class="social-buttons">
          <button type="button" class="social-btn google">
            <svg width="18" height="18" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
            Google
          </button>
          <button type="button" class="social-btn facebook">
            <i class="fab fa-facebook-f"></i>
            Facebook
          </button>
        </div>

        <p class="signin-text">
          Already have an account?
          <router-link to="/login">
            Sign in
            <i class="fas fa-arrow-right" style="font-size:0.7rem;margin-left:2px"></i>
          </router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const form = ref({ name: '', email: '', password: '', password_confirmation: '' })
const error = ref(null)
const loading = ref(false)
const showPass = ref(false)
const showConfirm = ref(false)
const focusName = ref(false)
const focusEmail = ref(false)
const focusPass = ref(false)
const focusConfirm = ref(false)

const perks = reactive([
  { icon: 'fas fa-gift', title: '10% Off', desc: 'Your first order', bg: 'rgba(245,200,122,0.15)', ring: 'rgba(245,200,122,0.3)', hover: false },
  { icon: 'fas fa-truck', title: 'Free Delivery', desc: 'On orders over $50', bg: 'rgba(129,199,132,0.15)', ring: 'rgba(129,199,132,0.3)', hover: false },
  { icon: 'fas fa-birthday-cake', title: 'Birthday Surprise', desc: 'Special treat every year', bg: 'rgba(239,154,154,0.15)', ring: 'rgba(239,154,154,0.3)', hover: false }
])

const particleStyle = (n) => {
  const size = Math.random() * 5 + 2
  return {
    width: `${size}px`,
    height: `${size}px`,
    left: `${Math.random() * 100}%`,
    top: `${Math.random() * 100}%`,
    animationDelay: `${Math.random() * 8}s`,
    animationDuration: `${Math.random() * 5 + 6}s`
  }
}

const parallaxHover = (e, intensity) => { e.currentTarget.style.zIndex = 10 }
const parallaxReset = (e) => {
  const el = e.currentTarget
  el.style.zIndex = ''
  el.style.transform = el.dataset.origTransform || ''
}
const parallaxMove = (e, intensity) => {
  const el = e.currentTarget
  if (!el.dataset.origTransform) el.dataset.origTransform = getComputedStyle(el).transform
  const rect = el.getBoundingClientRect()
  const x = ((e.clientX - rect.left) / rect.width - 0.5) * intensity
  const y = ((e.clientY - rect.top) / rect.height - 0.5) * intensity
  const base = el.dataset.origTransform === 'none' ? '' : el.dataset.origTransform
  el.style.transform = `${base} translate(${x}px, ${y}px) scale(1.02)`
}

const strength = computed(() => {
  const p = form.value.password
  if (!p) return { percent: 0, label: '', color: 'transparent' }
  let score = 0
  if (p.length >= 8) score++
  if (p.length >= 12) score++
  if (/[A-Z]/.test(p)) score++
  if (/[0-9]/.test(p)) score++
  if (/[^A-Za-z0-9]/.test(p)) score++
  const levels = [
    { percent: 20, label: 'Very weak', color: '#ef4444' },
    { percent: 40, label: 'Weak', color: '#f97316' },
    { percent: 60, label: 'Fair', color: '#eab308' },
    { percent: 80, label: 'Strong', color: '#22c55e' },
    { percent: 100, label: 'Very strong', color: '#16a34a' }
  ]
  return levels[Math.min(score, 4)]
})

const isFormValid = computed(() => {
  const f = form.value
  return f.name && f.email && f.password.length >= 8 && f.password === f.password_confirmation
})

const completionPercent = computed(() => {
  const f = form.value
  let done = 0
  if (f.name) done++
  if (f.email) done++
  if (f.password && f.password.length >= 8) done++
  if (f.password_confirmation && f.password === f.password_confirmation) done++
  return Math.round((done / 4) * 100)
})

const rippleEffect = (e) => {
  const btn = e.currentTarget
  const ripple = document.createElement('span')
  const rect = btn.getBoundingClientRect()
  const size = Math.max(rect.width, rect.height)
  ripple.style.width = ripple.style.height = size + 'px'
  ripple.style.left = (e.clientX - rect.left - size / 2) + 'px'
  ripple.style.top = (e.clientY - rect.top - size / 2) + 'px'
  ripple.className = 'ripple-effect'
  btn.appendChild(ripple)
  setTimeout(() => ripple.remove(), 600)
}

const handleRegister = async () => {
  if (form.value.password !== form.value.password_confirmation) {
    error.value = 'Passwords do not match'
    return
  }
  loading.value = true
  error.value = null
  try {
    const response = await axios.post('/register', form.value)
    localStorage.setItem('token', response.data.token)
    localStorage.setItem('user', JSON.stringify(response.data.user))
    axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`
    router.push('/')
  } catch (err) {
    error.value = err.response?.data?.message || 'Registration failed. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;1,400&family=Inter:wght@300;400;500;600;700&display=swap');

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
  background: #1a0e06;
}

/* Grain overlay */
.grain {
  position: absolute;
  inset: 0;
  z-index: 8;
  pointer-events: none;
  opacity: 0.035;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  background-size: 128px 128px;
}

/* Geometric accents */
.geo-accent {
  position: absolute;
  z-index: 3;
  border: 1.5px solid rgba(245,200,122,0.08);
  border-radius: 20px;
  pointer-events: none;
}

.g1 {
  width: 200px;
  height: 200px;
  top: 10%;
  left: -60px;
  transform: rotate(15deg);
  animation: geoFloat 12s ease-in-out infinite;
}

.g2 {
  width: 120px;
  height: 120px;
  top: 60%;
  right: 5%;
  transform: rotate(-20deg);
  border-radius: 50%;
  animation: geoFloat 10s ease-in-out infinite reverse;
}

.g3 {
  width: 80px;
  height: 80px;
  bottom: 20%;
  left: 15%;
  transform: rotate(45deg);
  animation: geoFloat 14s ease-in-out infinite;
  animation-delay: -3s;
}

@keyframes geoFloat {
  0%, 100% { transform: rotate(15deg) translate(0, 0); opacity: 0.5; }
  50% { transform: rotate(20deg) translate(10px, -15px); opacity: 1; }
}

/* Particles */
.particles { position: absolute; inset: 0; z-index: 5; pointer-events: none; }

.particle {
  position: absolute;
  background: rgba(245,200,122,0.45);
  border-radius: 50%;
  animation: particleFloat linear infinite;
  box-shadow: 0 0 6px rgba(245,200,122,0.2);
}

@keyframes particleFloat {
  0% { transform: translateY(0) scale(1); opacity: 0; }
  8% { opacity: 0.8; }
  92% { opacity: 0.8; }
  100% { transform: translateY(-100vh) scale(0.2); opacity: 0; }
}

/* Collage */
.bg-collage { position: absolute; inset: 0; z-index: 1; }

.collage-item {
  position: absolute;
  border-radius: 18px;
  overflow: hidden;
  box-shadow: 0 25px 60px rgba(0,0,0,0.5);
  transition: transform 0.5s cubic-bezier(0.23, 1, 0.32, 1), box-shadow 0.5s ease;
  cursor: pointer;
}

.collage-item:hover {
  box-shadow: 0 30px 80px rgba(0,0,0,0.6), 0 0 40px rgba(200,119,58,0.15);
}

.collage-item img { width: 100%; height: 100%; object-fit: cover; display: block; }

.collage-shine {
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(255,255,255,0.08) 0%, transparent 50%, transparent 100%);
  pointer-events: none;
  transition: opacity 0.5s;
  opacity: 0;
}

.collage-item:hover .collage-shine { opacity: 1; }

.c1 { width: 52%; height: 65%; top: 5%; left: -4%; transform: rotate(-4deg); z-index: 2; }
.c2 { width: 48%; height: 52%; top: 0%; right: -7%; transform: rotate(5deg); z-index: 1; }
.c3 { width: 42%; height: 48%; bottom: 0%; left: 10%; transform: rotate(3deg); z-index: 1; }
.c4 { width: 36%; height: 36%; bottom: 8%; right: 2%; transform: rotate(-6deg); z-index: 3; }

/* Overlay */
.left-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, rgba(26,14,6,0.55) 0%, rgba(40,22,10,0.82) 45%, rgba(26,14,6,0.96) 100%);
  z-index: 4;
}

/* Content */
.auth-left-inner {
  position: relative;
  z-index: 6;
  color: white;
  max-width: 400px;
  margin-bottom: 1.5rem;
}

.auth-brand {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1.75rem;
}

.brand-logo {
  width: 48px;
  height: 48px;
  border-radius: 14px;
  overflow: hidden;
  border: 2px solid rgba(245,200,122,0.4);
  flex-shrink: 0;
  position: relative;
}

.brand-logo img { width: 100%; height: 100%; object-fit: cover; }

.logo-glow {
  position: absolute;
  inset: -4px;
  border-radius: 18px;
  background: radial-gradient(circle, rgba(245,200,122,0.2), transparent 70%);
  z-index: -1;
  animation: logoPulse 3s ease-in-out infinite;
}

@keyframes logoPulse {
  0%, 100% { opacity: 0.5; transform: scale(1); }
  50% { opacity: 1; transform: scale(1.1); }
}

.brand-name {
  font-family: 'Playfair Display', serif;
  font-size: 1.7rem;
  font-weight: 700;
  color: white;
  letter-spacing: -0.02em;
}

.brand-name span { color: #f5c87a; font-style: italic; }

.auth-left h2 {
  font-family: 'Playfair Display', serif;
  font-size: 2.1rem;
  font-weight: 800;
  margin-bottom: 0.65rem;
  color: #fff;
  line-height: 1.15;
  letter-spacing: -0.02em;
}

.auth-left > .auth-left-inner > p {
  color: rgba(255,255,255,0.6);
  line-height: 1.7;
  font-size: 0.9rem;
  margin-bottom: 1.5rem;
}

/* Perks */
.perks { display: flex; flex-direction: column; gap: 0.55rem; margin-bottom: 1.25rem; }

.perk {
  display: flex;
  align-items: center;
  gap: 0.85rem;
  background: rgba(255,255,255,0.05);
  border: 1px solid rgba(255,255,255,0.06);
  backdrop-filter: blur(12px);
  padding: 0.65rem 1rem;
  border-radius: 14px;
  transition: all 0.35s cubic-bezier(0.23, 1, 0.32, 1);
  animation: perkSlide 0.5s ease both;
  position: relative;
  overflow: hidden;
}

.perk::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(90deg, rgba(255,255,255,0.03), transparent);
  opacity: 0;
  transition: opacity 0.3s;
}

.perk:hover::before { opacity: 1; }

.perk:hover {
  background: rgba(255,255,255,0.09);
  border-color: rgba(255,255,255,0.12);
  transform: translateX(6px);
}

@keyframes perkSlide {
  from { opacity: 0; transform: translateX(-16px); }
  to { opacity: 1; transform: translateX(0); }
}

.perk-icon {
  width: 38px;
  height: 38px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  position: relative;
}

.perk-icon i { font-size: 0.85rem; color: #f5c87a; position: relative; z-index: 1; }

.perk-icon-ring {
  position: absolute;
  inset: -3px;
  border-radius: 13px;
  border: 1.5px solid;
  opacity: 0;
  transition: opacity 0.3s;
}

.perk:hover .perk-icon-ring { opacity: 1; }

.perk-text { display: flex; flex-direction: column; flex: 1; }
.perk-text strong { font-size: 0.84rem; font-weight: 600; color: #fff; }
.perk-text span { font-size: 0.73rem; color: rgba(255,255,255,0.45); }

.perk-arrow {
  font-size: 0.65rem;
  color: rgba(255,255,255,0.2);
  transition: all 0.3s;
  transform: translateX(-6px);
  opacity: 0;
}

.perk-arrow.visible {
  opacity: 1;
  transform: translateX(0);
  color: #f5c87a;
}

/* Testimonial */
.testimonial-card {
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(255,255,255,0.08);
  backdrop-filter: blur(16px);
  border-radius: 16px;
  padding: 1rem 1.15rem;
  margin-bottom: 1.25rem;
  animation: fadeUp 0.6s ease 0.5s both;
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(12px); }
  to { opacity: 1; transform: translateY(0); }
}

.testimonial-stars { margin-bottom: 0.5rem; }
.testimonial-stars i { color: #f5c87a; font-size: 0.7rem; margin-right: 1px; }

.testimonial-text {
  font-size: 0.82rem;
  color: rgba(255,255,255,0.75);
  line-height: 1.6;
  font-style: italic;
  margin-bottom: 0.75rem;
}

.testimonial-author {
  display: flex;
  align-items: center;
  gap: 0.6rem;
}

.testimonial-author img {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid rgba(245,200,122,0.3);
}

.testimonial-author strong {
  display: block;
  font-size: 0.78rem;
  color: #fff;
  font-weight: 600;
}

.testimonial-author span {
  font-size: 0.68rem;
  color: rgba(255,255,255,0.4);
}

/* Social proof */
.social-proof {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  animation: fadeUp 0.6s ease 0.7s both;
}

.proof-avatars { display: flex; align-items: center; }

.proof-avatars img {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  border: 2px solid rgba(255,255,255,0.25);
  margin-left: -6px;
  object-fit: cover;
}

.proof-avatars img:first-child { margin-left: 0; }

.avatar-more {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: rgba(245,200,122,0.2);
  border: 2px solid rgba(245,200,122,0.4);
  margin-left: -6px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.45rem;
  font-weight: 700;
  color: #f5c87a;
}

.social-proof p { color: rgba(255,255,255,0.45); font-size: 0.75rem; }
.social-proof strong { color: #f5c87a; }

/* ===== RIGHT PANEL ===== */
.auth-right {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem 2rem;
  background: #fefaf6;
  position: relative;
  overflow: hidden;
  overflow-y: auto;
}

.right-pattern {
  position: absolute;
  inset: 0;
  background-image:
    radial-gradient(circle at 15% 15%, rgba(200,119,58,0.05) 0%, transparent 50%),
    radial-gradient(circle at 85% 85%, rgba(200,119,58,0.04) 0%, transparent 50%),
    radial-gradient(circle at 50% 40%, rgba(200,119,58,0.02) 0%, transparent 60%);
  pointer-events: none;
}

.right-dots {
  position: absolute;
  inset: 0;
  background-image: radial-gradient(circle, rgba(200,119,58,0.06) 1px, transparent 1px);
  background-size: 24px 24px;
  pointer-events: none;
  opacity: 0.5;
}

/* Corner deco */
.corner-deco {
  position: absolute;
  pointer-events: none;
  overflow: hidden;
}

.corner-deco img { width: 100%; height: 100%; object-fit: cover; }

.corner-deco.top-right {
  top: 0;
  right: 0;
  width: 180px;
  height: 180px;
  border-radius: 0 0 0 100%;
  opacity: 0.06;
}

.corner-deco.bottom-left {
  bottom: 0;
  left: 0;
  width: 140px;
  height: 140px;
  border-radius: 0 100% 0 0;
  opacity: 0.05;
}

.auth-form-wrap {
  width: 100%;
  max-width: 430px;
  position: relative;
  z-index: 1;
}

/* Completion bar */
.completion-bar {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1.75rem;
}

.completion-track {
  flex: 1;
  height: 5px;
  background: #f0e6da;
  border-radius: 5px;
  overflow: hidden;
}

.completion-fill {
  height: 100%;
  border-radius: 5px;
  background: linear-gradient(90deg, #c8773a, #e8a05c);
  transition: width 0.5s cubic-bezier(0.23, 1, 0.32, 1);
  position: relative;
}

.completion-fill::after {
  content: '';
  position: absolute;
  right: 0;
  top: 50%;
  transform: translateY(-50%);
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: #c8773a;
  box-shadow: 0 0 8px rgba(200,119,58,0.5);
  opacity: 0;
  transition: opacity 0.3s;
}

.completion-fill[style*="width: 0"]::after { opacity: 0; }
.completion-fill:not([style*="width: 0"])::after { opacity: 1; }

.completion-label {
  font-size: 0.72rem;
  font-weight: 600;
  color: #b89b82;
  white-space: nowrap;
  min-width: 70px;
  text-align: right;
  transition: color 0.3s;
}

/* Form header */
.form-header {
  text-align: center;
  margin-bottom: 1.5rem;
}

.form-icon-wrap {
  display: inline-block;
  position: relative;
  margin-bottom: 0.85rem;
}

.form-icon {
  width: 58px;
  height: 58px;
  border-radius: 18px;
  background: linear-gradient(135deg, rgba(200,119,58,0.12), rgba(200,119,58,0.04));
  border: 1px solid rgba(200,119,58,0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  z-index: 1;
}

.form-icon i { font-size: 1.4rem; color: #c8773a; }

.form-icon-pulse {
  position: absolute;
  inset: -6px;
  border-radius: 24px;
  border: 1.5px solid rgba(200,119,58,0.15);
  animation: iconPulse 2.5s ease-in-out infinite;
}

@keyframes iconPulse {
  0%, 100% { transform: scale(1); opacity: 0.5; }
  50% { transform: scale(1.08); opacity: 0; }
}

.form-header h1 {
  font-family: 'Playfair Display', serif;
  font-size: 2rem;
  font-weight: 800;
  color: #1a0e06;
  margin-bottom: 0.3rem;
  letter-spacing: -0.02em;
}

.form-header p { color: #9a7560; font-size: 0.88rem; }

/* Fields */
.field {
  margin-bottom: 0.15rem;
  transition: margin 0.3s;
}

.field-active { margin-bottom: 0.15rem; }
.field-filled { margin-bottom: 0.15rem; }

.field label {
  display: block;
  font-size: 0.8rem;
  font-weight: 600;
  color: #6b4a32;
  margin-bottom: 0.4rem;
  letter-spacing: 0.01em;
  transition: color 0.3s;
}

.field-active label { color: #c8773a; }

.input-wrap {
  position: relative;
  display: flex;
  align-items: center;
  border-radius: 14px;
  transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
}

.input-icon {
  position: absolute;
  left: 0.85rem;
  z-index: 2;
  width: 28px;
  height: 28px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(200,119,58,0.05);
  transition: all 0.3s;
}

.input-icon i { font-size: 0.75rem; color: #c4a07a; transition: color 0.3s; }
.input-wrap.focused .input-icon { background: rgba(200,119,58,0.1); }
.input-wrap.focused .input-icon i { color: #c8773a; }

.input-bg-icon {
  position: absolute;
  right: 1rem;
  font-size: 2.5rem;
  font-weight: 800;
  color: rgba(200,119,58,0.04);
  pointer-events: none;
  font-family: 'Playfair Display', serif;
  user-select: none;
  transition: opacity 0.3s;
}

.input-wrap.focused .input-bg-icon { opacity: 0.07; }
.input-wrap.match-error .input-bg-icon { color: rgba(239,68,68,0.04); }
.input-wrap.match-ok .input-bg-icon { color: rgba(34,197,94,0.04); }

.input-wrap input {
  width: 100%;
  padding: 0.85rem 3rem 0.85rem 3.2rem;
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
  box-shadow: 0 0 0 4px rgba(200,119,58,0.08), 0 2px 12px rgba(200,119,58,0.06);
}

.input-wrap.filled input { border-color: #d4b896; }

.input-wrap.match-error input {
  border-color: #fca5a5;
  box-shadow: 0 0 0 3px rgba(239,68,68,0.06);
}

.input-wrap.match-ok input {
  border-color: #86efac;
  box-shadow: 0 0 0 3px rgba(34,197,94,0.06);
}

.input-wrap input::placeholder { color: #c4a07a; font-weight: 400; }

.toggle-pass {
  position: absolute;
  right: 0.6rem;
  background: rgba(200,119,58,0.05);
  border: none;
  color: #c8a07a;
  cursor: pointer;
  font-size: 0.78rem;
  padding: 0.4rem 0.5rem;
  border-radius: 8px;
  transition: all 0.2s;
  z-index: 2;
}

.toggle-pass:hover { color: #c8773a; background: rgba(200,119,58,0.1); }

/* Match icons */
.match-icon {
  position: absolute;
  right: 2.4rem;
  font-size: 0.9rem;
  z-index: 2;
}

.match-icon.ok { color: #22c55e; }
.match-icon.err { color: #ef4444; }

.match-pop-enter-active { animation: popIn 0.3s ease; }
.match-pop-leave-active { animation: popIn 0.2s ease reverse; }

@keyframes popIn {
  0% { transform: scale(0); opacity: 0; }
  70% { transform: scale(1.2); }
  100% { transform: scale(1); opacity: 1; }
}

/* Strength bar */
.strength-fade-enter-active { transition: all 0.3s ease; }
.strength-fade-leave-active { transition: all 0.2s ease; }
.strength-fade-enter-from, .strength-fade-leave-to { opacity: 0; transform: translateY(-6px); }

.strength-bar {
  display: flex;
  align-items: center;
  gap: 0.6rem;
  margin-top: 0.5rem;
  padding: 0 0.25rem;
}

.strength-track {
  flex: 1;
  height: 4px;
  background: #f0e6da;
  border-radius: 4px;
  overflow: hidden;
}

.strength-fill {
  height: 100%;
  border-radius: 4px;
  transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
}

.strength-label {
  font-size: 0.68rem;
  font-weight: 600;
  white-space: nowrap;
  min-width: 72px;
  text-align: right;
  transition: color 0.3s;
}

/* Password requirements */
.req-fade-enter-active { transition: all 0.3s ease; }
.req-fade-leave-active { transition: all 0.2s ease; }
.req-fade-enter-from, .req-fade-leave-to { opacity: 0; max-height: 0; margin-top: 0; }
.req-fade-enter-to, .req-fade-leave-from { opacity: 1; max-height: 150px; }

.pw-requirements {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.3rem 0.75rem;
  margin-top: 0.55rem;
  padding: 0.6rem 0.75rem;
  background: rgba(200,119,58,0.03);
  border-radius: 10px;
  border: 1px solid rgba(200,119,58,0.06);
  overflow: hidden;
}

.req-item {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.7rem;
  color: #b89b82;
  transition: color 0.3s;
}

.req-item i { font-size: 0.65rem; transition: all 0.3s; flex-shrink: 0; }
.req-item.met { color: #16a34a; }
.req-item.met i { color: #22c55e; }

/* Error */
.error-slide-enter-active { animation: errorIn 0.4s ease; }
.error-slide-leave-active { animation: errorIn 0.3s ease reverse; }

@keyframes errorIn {
  0% { transform: translateX(-10px); opacity: 0; }
  50% { transform: translateX(5px); }
  100% { transform: translateX(0); opacity: 1; }
}

.error-msg {
  background: linear-gradient(135deg, #fef2f2, #fff5f5);
  border: 1px solid #fecaca;
  border-radius: 14px;
  padding: 0.85rem 1rem;
  font-size: 0.82rem;
  margin-top: 0.5rem;
  margin-bottom: 0.75rem;
  display: flex;
  align-items: flex-start;
  gap: 0.65rem;
}

.error-icon-wrap {
  width: 32px;
  height: 32px;
  border-radius: 10px;
  background: rgba(239,68,68,0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.error-icon-wrap i { color: #ef4444; font-size: 0.8rem; }

.error-msg strong {
  display: block;
  font-size: 0.82rem;
  color: #dc2626;
  margin-bottom: 0.1rem;
}

.error-msg span { color: #b91c1c; font-size: 0.78rem; line-height: 1.4; }

.error-close {
  position: absolute;
  right: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  background: none;
  border: none;
  color: #fca5a5;
  cursor: pointer;
  padding: 0.25rem;
  font-size: 0.75rem;
  transition: color 0.2s;
}

.error-close:hover { color: #ef4444; }

.error-msg { position: relative; }

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
  box-shadow: 0 4px 20px rgba(200,119,58,0.25), inset 0 1px 0 rgba(255,255,255,0.15);
  font-family: 'Inter', sans-serif;
  position: relative;
  overflow: hidden;
  margin-top: 0.75rem;
  margin-bottom: 0.75rem;
}

.submit-btn::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, transparent 35%, rgba(255,255,255,0.12) 50%, transparent 65%);
  background-size: 250% 250%;
  animation: shimmer 4s ease-in-out infinite;
}

@keyframes shimmer {
  0% { background-position: 200% 200%; }
  100% { background-position: -200% -200%; }
}

.submit-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 8px 30px rgba(200,119,58,0.35), inset 0 1px 0 rgba(255,255,255,0.2);
  background-position: 100% 100%;
}

.submit-btn:active:not(:disabled) {
  transform: translateY(0);
  box-shadow: 0 2px 10px rgba(200,119,58,0.25);
}

.submit-btn:disabled {
  opacity: 0.55;
  cursor: not-allowed;
  transform: none;
}

.submit-btn:disabled::before { animation: none; }

.btn-idle, .btn-loading {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  position: relative;
  z-index: 1;
}

.btn-arrow {
  width: 28px;
  height: 28px;
  border-radius: 8px;
  background: rgba(255,255,255,0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s;
}

.btn-arrow i { font-size: 0.75rem; transition: transform 0.3s; }
.submit-btn:hover:not(:disabled) .btn-arrow { background: rgba(255,255,255,0.2); }
.submit-btn:hover:not(:disabled) .btn-arrow i { transform: translateX(2px); }

.spinner {
  width: 20px;
  height: 20px;
  animation: spin 0.8s linear infinite;
}

@keyframes spin { to { transform: rotate(360deg); } }

/* Ripple */
.ripple-effect {
  position: absolute;
  border-radius: 50%;
  background: rgba(255,255,255,0.25);
  transform: scale(0);
  animation: ripple 0.6s ease-out;
  pointer-events: none;
  z-index: 0;
}

@keyframes ripple {
  to { transform: scale(4); opacity: 0; }
}

/* Terms */
.terms {
  font-size: 0.74rem;
  color: #9a7560;
  text-align: center;
  line-height: 1.6;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
  flex-wrap: wrap;
}

.terms i { color: #c8a07a; font-size: 0.7rem; }
.terms a { color: #c8773a; text-decoration: none; font-weight: 500; }
.terms a:hover { text-decoration: underline; }

/* Divider */
.divider {
  display: flex;
  align-items: center;
  margin: 1.15rem 0;
  gap: 1rem;
}

.divider::before, .divider::after {
  content: '';
  flex: 1;
  height: 1px;
  background: linear-gradient(90deg, transparent, #e8d5c0, transparent);
}

.divider span { font-size: 0.76rem; color: #b89b82; white-space: nowrap; }

/* Social */
.social-buttons {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.65rem;
  margin-bottom: 1.15rem;
}

.social-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.72rem;
  border-radius: 12px;
  border: 1.5px solid #e8d5c0;
  background: white;
  font-size: 0.82rem;
  font-weight: 500;
  color: #4a3220;
  cursor: pointer;
  transition: all 0.25s;
  font-family: 'Inter', sans-serif;
  position: relative;
  overflow: hidden;
}

.social-btn::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, transparent, rgba(200,119,58,0.03));
  opacity: 0;
  transition: opacity 0.3s;
}

.social-btn:hover::after { opacity: 1; }

.social-btn:hover {
  border-color: #c8773a;
  box-shadow: 0 2px 12px rgba(200,119,58,0.08);
  transform: translateY(-1px);
}

.social-btn.google svg { flex-shrink: 0; position: relative; z-index: 1; }
.social-btn span { position: relative; z-index: 1; }
.social-btn.facebook i { color: #1877F2; font-size: 0.95rem; position: relative; z-index: 1; }

/* Signin text */
.signin-text {
  text-align: center;
  font-size: 0.85rem;
  color: #9a7560;
}

.signin-text a {
  color: #c8773a;
  font-weight: 600;
  text-decoration: none;
  transition: color 0.2s;
  display: inline-flex;
  align-items: center;
  gap: 3px;
}

.signin-text a:hover { color: #a05c28; }

/* ===== RESPONSIVE ===== */
@media (max-width: 1100px) {
  .c1 { width: 58%; height: 58%; }
  .c2 { width: 48%; height: 44%; }
  .c3 { width: 38%; height: 38%; }
  .c4 { display: none; }
  .testimonial-card { display: none; }
}

@media (max-width: 768px) {
  .auth-page { flex-direction: column; }
  .auth-left {
    min-height: 230px;
    flex: none;
    align-items: flex-end;
    padding: 1.75rem;
  }
  .c1 { width: 70%; height: 85%; top: -15%; left: -12%; }
  .c2 { width: 55%; height: 65%; top: -10%; right: -18%; }
  .c3, .c4 { display: none; }
  .auth-left h2 { font-size: 1.5rem; }
  .social-proof { display: none; }
  .geo-accent { display: none; }
  .auth-right { padding: 1.75rem 1.25rem; }
  .pw-requirements { grid-template-columns: 1fr; }
  .corner-deco { display: none; }
}

@media (max-width: 400px) {
  .social-buttons { grid-template-columns: 1fr; }
}
</style>