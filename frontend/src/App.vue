<template>
  <div id="app">
    <header v-if="!isAdmin" class="navbar">
      <div class="navbar-inner">
        <router-link to="/" class="logo">
          <span class="logo-icon">
            <img src="./image/icon.png" alt="">
          </span>
          <span class="logo-text">Sk<span class="logo-accent">Cake</span></span>
        </router-link>

        <nav class="nav-links">
          <router-link to="/" class="nav-link">Home</router-link>
          <router-link to="/shop" class="nav-link">Shop</router-link>
        </nav>

        <div class="nav-auth">
          <template v-if="!token">
            <router-link to="/login" class="btn-outline">Login</router-link>
            <router-link to="/register" class="btn-primary">Register</router-link>
          </template>
          <template v-else>
            <router-link to="/wishlist" class="icon-btn" title="Wishlist">
              <i class="far fa-heart"></i>
            </router-link>
            <router-link to="/cart" class="icon-btn cart-btn" title="Cart">
              <i class="fas fa-shopping-cart"></i>
              <span v-if="cart.count > 0" class="cart-badge">{{ cart.count }}</span>
            </router-link>
            <div class="user-menu">
              <router-link to="/profile" class="user-avatar" title="My Profile">
                {{ userInitial }}
              </router-link>
              <span class="user-name">{{ user?.name?.split(' ')[0] }}</span>
              <router-link v-if="user?.is_admin" to="/admin" class="btn-admin">
                <i class="fas fa-cog"></i> Admin
              </router-link>
              <button @click="handleLogout" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Logout
              </button>
            </div>
          </template>
        </div>
      </div>
    </header>

    <main class="main-content">
      <router-view />
    </main>

    <footer v-if="!isAdmin" class="footer">
      <div class="footer-main">
        <div class="footer-grid">
          <div class="footer-brand">
            <h3 class="footer-store-name">Sweet Cake Store</h3>
            <p class="footer-tagline">Freshly baked cakes for every celebration.</p>
            <div class="footer-contact">
              <p><i class="fas fa-map-marker-alt"></i> Phnom Penh, Cambodia</p>
              <p><i class="fas fa-phone"></i> +855 88 563 476</p>
              <p><i class="fas fa-envelope"></i> info@sweetcakestore.com</p>
            </div>
          </div>

          <div class="footer-links-group">
            <h4>Quick Links</h4>
            <ul class="footer-links">
              <li><router-link to="/">Home</router-link></li>
              <li><router-link to="/about">About</router-link></li>
              <li><router-link to="/shop">Cakes</router-link></li>
              <li><router-link to="/contact">Contact</router-link></li>
            </ul>
          </div>

          <div class="footer-links-group">
            <h4>Customer Service</h4>
            <ul class="footer-links">
              <li><router-link to="/faq">FAQ</router-link></li>
              <li><router-link to="/delivery">Delivery</router-link></li>
              <li><router-link to="/refund-policy">Refund Policy</router-link></li>
            </ul>
          </div>

          <div class="footer-links-group">
            <h4>Follow Us</h4>
            <ul class="footer-links">
              <li><a href="https://facebook.com" target="_blank" rel="noopener">Facebook</a></li>
              <li><a href="https://instagram.com" target="_blank" rel="noopener">Instagram</a></li>
              <li><a href="https://tiktok.com" target="_blank" rel="noopener">TikTok</a></li>
            </ul>
          </div>
        </div>

        <div class="footer-newsletter">
          <h4>Subscribe to our newsletter</h4>
          <form @submit.prevent class="newsletter-form">
            <input type="email" placeholder="Enter your email" class="newsletter-input" />
            <button type="submit" class="newsletter-btn">Subscribe</button>
          </form>
        </div>
      </div>

      <div class="footer-bottom">
        <p>© 2026 SK Sweet Cake Store. Made with love 🩷</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { auth } from './stores/auth'
import { cart } from './stores/cart'

const router = useRouter()
const route  = useRoute()

const token = ref(localStorage.getItem('token'))
const user  = ref(JSON.parse(localStorage.getItem('user')))

const isAdmin    = computed(() => route.path.startsWith('/admin'))
const userInitial = computed(() => user.value?.name?.charAt(0).toUpperCase() || '?')

async function handleLogout() {
  await auth.logout()
  token.value = null
  user.value  = null
  cart.items = []; cart.total = 0; cart.count = 0
  router.push('/login')
}

onMounted(() => {
  window.addEventListener('storage', () => {
    token.value = localStorage.getItem('token')
    user.value  = JSON.parse(localStorage.getItem('user'))
  })
  if (auth.isLoggedIn) cart.fetch()
})
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap');

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body { font-family: 'Inter', sans-serif; background-color: #fdf8f3; color: #2d1b0e; min-height: 100vh; }

#app { min-height: 100vh; display: flex; flex-direction: column; }

.navbar {
  background: rgba(255,255,255,0.95);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid #f0e6d8;
  position: sticky; top: 0; z-index: 100;
  box-shadow: 0 2px 20px rgba(180,100,50,0.08);
}

.navbar-inner {
  max-width: 1200px; margin: 0 auto;
  padding: 0 2rem; height: 70px;
  display: flex; align-items: center; justify-content: space-between; gap: 2rem;
}

.logo { display: flex; align-items: center; gap: 0.6rem; text-decoration: none; }
.logo-icon {
  width: 42px;
  height: 42px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}
.logo-icon img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  display: block;
}
.logo-text { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; color: #2d1b0e; }
.logo-accent { color: #c8773a; }

.nav-links { display: flex; align-items: center; gap: 0.25rem; }
.nav-link { text-decoration: none; color: #6b4c30; font-size: 0.9rem; font-weight: 500; padding: 0.45rem 1rem; border-radius: 8px; transition: all 0.2s; }
.nav-link:hover, .nav-link.router-link-active { background: #fdf0e6; color: #c8773a; }

.nav-auth { display: flex; align-items: center; gap: 0.75rem; }

.btn-outline { text-decoration: none; padding: 0.45rem 1.2rem; border: 1.5px solid #c8773a; border-radius: 50px; color: #c8773a; font-size: 0.875rem; font-weight: 500; transition: all 0.2s; }
.btn-outline:hover { background: #c8773a; color: white; }

.btn-primary { text-decoration: none; padding: 0.45rem 1.2rem; background: linear-gradient(135deg, #c8773a, #e8945a); border-radius: 50px; color: white; font-size: 0.875rem; font-weight: 500; transition: all 0.2s; box-shadow: 0 2px 10px rgba(200,119,58,0.3); }
.btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(200,119,58,0.4); }

/* Icon buttons */
.icon-btn {
  position: relative;
  width: 38px; height: 38px;
  border-radius: 10px;
  background: #fdf0e6;
  display: flex; align-items: center; justify-content: center;
  color: #8b5e3c; font-size: 0.95rem;
  text-decoration: none;
  transition: all 0.2s;
}
.icon-btn:hover { background: #f0d5b0; color: #c8773a; }

.cart-badge {
  position: absolute;
  top: -5px; right: -5px;
  min-width: 18px; height: 18px;
  background: #c8773a;
  color: white;
  font-size: 0.65rem;
  font-weight: 700;
  border-radius: 50px;
  display: flex; align-items: center; justify-content: center;
  padding: 0 4px;
  border: 2px solid white;
}

.user-menu { display: flex; align-items: center; gap: 0.75rem; }

.user-avatar {
  width: 36px; height: 36px;
  background: linear-gradient(135deg, #c8773a, #e8945a);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  color: white; font-weight: 600; font-size: 0.875rem;
  text-decoration: none;
  transition: all 0.2s;
}
.user-avatar:hover { transform: scale(1.08); box-shadow: 0 4px 12px rgba(200,119,58,0.35); }

.user-name { font-size: 0.875rem; font-weight: 500; color: #4a3220; }

.btn-admin { text-decoration: none; padding: 0.4rem 1rem; background: #2d1b0e; border-radius: 8px; color: #f5c87a; font-size: 0.8rem; font-weight: 500; transition: all 0.2s; display: flex; align-items: center; gap: 0.4rem; }
.btn-admin:hover { background: #3d2b1e; }

.btn-logout { display: flex; align-items: center; gap: 0.4rem; padding: 0.4rem 1rem; background: transparent; border: 1.5px solid #e8d5c0; border-radius: 50px; color: #8b5e3c; font-size: 0.8rem; font-weight: 500; cursor: pointer; transition: all 0.2s; font-family: 'Inter', sans-serif; }
.btn-logout:hover { background: #fff0f0; border-color: #ef4444; color: #ef4444; }

.main-content { flex: 1; }

.footer {
  background: linear-gradient(180deg, #2d1b0e 0%, #1f1209 100%);
  color: #d4b896;
  padding: 3rem 2rem 1rem;
  margin-top: auto;
}

.footer-main {
  max-width: 1200px;
  margin: 0 auto;
}

.footer-grid {
  display: grid;
  grid-template-columns: 1.5fr 1fr 1fr 1fr;
  gap: 2.5rem;
  margin-bottom: 2.5rem;
}

.footer-brand {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.footer-logo {
  width: 50px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 0.25rem;
}

.footer-logo img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  display: block;
  filter: brightness(0) invert(1);
  opacity: 0.9;
}

.footer-store-name {
  font-family: 'Playfair Display', serif;
  font-size: 1.35rem;
  font-weight: 700;
  color: #f5c87a;
  margin: 0;
}

.footer-tagline {
  font-size: 0.9rem;
  color: #c4a882;
  font-style: italic;
  margin: 0;
  line-height: 1.5;
}

.footer-contact {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-top: 0.5rem;
}

.footer-contact p {
  font-size: 0.85rem;
  color: #b8956e;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 0.6rem;
}

.footer-contact i {
  color: #c8773a;
  font-size: 0.8rem;
  width: 14px;
  text-align: center;
}

.footer-links-group {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.footer-links-group h4 {
  font-family: 'Playfair Display', serif;
  font-size: 1rem;
  font-weight: 600;
  color: #f5c87a;
  margin: 0 0 0.25rem;
  position: relative;
  padding-bottom: 0.5rem;
}

.footer-links-group h4::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 30px;
  height: 2px;
  background: #c8773a;
  border-radius: 2px;
}

.footer-links {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 0.6rem;
}

.footer-links li {
  margin: 0;
}

.footer-links a {
  color: #b8956e;
  text-decoration: none;
  font-size: 0.875rem;
  transition: all 0.2s;
  display: inline-block;
}

.footer-links a:hover {
  color: #f5c87a;
  transform: translateX(4px);
}

.footer-newsletter {
  grid-column: 1 / -1;
  background: rgba(255, 255, 255, 0.03);
  border: 1px solid rgba(200, 119, 58, 0.2);
  border-radius: 12px;
  padding: 1.5rem 2rem;
  margin-top: 0.5rem;
}

.footer-newsletter h4 {
  font-family: 'Playfair Display', serif;
  font-size: 1.1rem;
  font-weight: 600;
  color: #f5c87a;
  margin: 0 0 1rem;
}

.newsletter-form {
  display: flex;
  gap: 0.75rem;
  max-width: 500px;
}

.newsletter-input {
  flex: 1;
  padding: 0.75rem 1rem;
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(200, 119, 58, 0.3);
  border-radius: 8px;
  color: #f5c87a;
  font-size: 0.9rem;
  font-family: 'Inter', sans-serif;
  transition: all 0.2s;
}

.newsletter-input::placeholder {
  color: #8b6f5c;
}

.newsletter-input:focus {
  outline: none;
  border-color: #c8773a;
  background: rgba(255, 255, 255, 0.12);
  box-shadow: 0 0 0 3px rgba(200, 119, 58, 0.15);
}

.newsletter-btn {
  padding: 0.75rem 1.5rem;
  background: linear-gradient(135deg, #c8773a, #e8945a);
  border: none;
  border-radius: 8px;
  color: white;
  font-size: 0.9rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  font-family: 'Inter', sans-serif;
  white-space: nowrap;
}

.newsletter-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(200, 119, 58, 0.4);
}

.footer-bottom {
  max-width: 1200px;
  margin: 0 auto;
  padding-top: 1.5rem;
  border-top: 1px solid rgba(200, 119, 58, 0.2);
  text-align: center;
}

.footer-bottom p {
  color: #8b6f5c;
  font-size: 0.85rem;
  margin: 0;
}

@media (max-width: 768px) {
  .footer {
    padding: 2rem 1.5rem 1rem;
  }

  .footer-grid {
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
  }

  .footer-brand {
    grid-column: 1 / -1;
  }

  .footer-newsletter {
    grid-column: 1 / -1;
  }

  .newsletter-form {
    flex-direction: column;
  }

  .newsletter-btn {
    width: 100%;
  }
}

@media (max-width: 480px) {
  .footer-grid {
    grid-template-columns: 1fr;
    gap: 1.75rem;
  }

  .footer-links-group h4::after {
    display: none;
  }
}
</style>
