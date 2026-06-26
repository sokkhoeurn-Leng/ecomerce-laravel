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
      <div class="footer-inner">
        <span class="footer-logo">
          <img src="./image/icon.png" alt="" />
        </span>
        <p>© 2025 SkCake Store. Made with love 🩷</p>
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

.footer { background: #2d1b0e; padding: 1.5rem 2rem; text-align: center; }
.footer-inner { display: flex; align-items: center; justify-content: center; gap: 0.75rem; }
.footer p { color: #a0856a; font-size: 0.85rem; }
.footer-logo {
  width: 34px;
  height: 34px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0.85;
}
.footer-logo img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  display: block;
}
</style>
