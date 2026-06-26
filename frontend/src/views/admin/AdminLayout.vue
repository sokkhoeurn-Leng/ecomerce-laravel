<template>
  <div class="admin-shell theme-light">

    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-brand">
        <span class="brand-icon">🎂</span>
        <div>
          <p class="brand-name">SweetBliss</p>
          <p class="brand-sub">Admin Panel</p>
        </div>
      </div>

      <nav class="sidebar-nav">
        <p class="nav-section-label">Main Menu</p>
        <router-link
          v-for="item in navItems" :key="item.to" :to="item.to"
          class="nav-item" :class="{ active: $route.path === item.to }"
        >
          <span class="nav-icon"><i :class="item.icon"></i></span>
          <span>{{ item.label }}</span>
        </router-link>
      </nav>

      <div class="sidebar-footer">
        <div class="admin-user">
          <div class="admin-avatar">{{ userInitial }}</div>
          <div class="admin-info">
            <p class="admin-name">{{ user?.name }}</p>
            <p class="admin-role">Administrator</p>
          </div>
        </div>
      </div>

      <div class="sidebar-logout">
        <button @click="logout" class="logout-full-btn">
          <i class="fas fa-sign-out-alt"></i>
          <span>Sign Out</span>
        </button>
      </div>
    </aside>

    <!-- Main -->
    <div class="admin-main">

      <!-- Topbar -->
      <header class="topbar">
        <div class="topbar-left">
          <div class="breadcrumb">
            <span class="breadcrumb-home"><i class="fas fa-home"></i></span>
            <span class="breadcrumb-sep">/</span>
            <span class="breadcrumb-current">{{ currentPageTitle }}</span>
          </div>
        </div>
        <div class="topbar-right">
          <span class="topbar-date">{{ currentDate }}</span>

          <div class="topbar-user">
            <div class="topbar-avatar">{{ userInitial }}</div>
            <span>{{ user?.name }}</span>
          </div>
        </div>
      </header>

      <main class="admin-content">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from 'axios'

const route  = useRoute()
const router = useRouter()
const user   = ref(JSON.parse(localStorage.getItem('user') || 'null'))

const userInitial = computed(() => user.value?.name?.charAt(0).toUpperCase() || 'A')

const navItems = [
  { to: '/admin',            label: 'Dashboard',  icon: 'fas fa-chart-pie'    },
  { to: '/admin/categories', label: 'Categories', icon: 'fas fa-folder-open'  },
  { to: '/admin/products',   label: 'Products',   icon: 'fas fa-box-open'     },
  { to: '/admin/orders',     label: 'Orders',     icon: 'fas fa-shopping-bag' },
  { to: '/admin/users',      label: 'Users',      icon: 'fas fa-users'        },
]

const currentPageTitle = computed(() => navItems.find(i => i.to === route.path)?.label || 'Admin')

const currentDate = computed(() =>
  new Date().toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' })
)

const logout = async () => {
  try { await axios.post('/logout') } catch (e) {}
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  delete axios.defaults.headers.common['Authorization']
  router.push('/login')
}
</script>

<style scoped>
/* ── CSS Variables ── */
.theme-light {
  --bg-body:       #f1f5f9;
  --bg-sidebar:    #ffffff;
  --bg-topbar:     #ffffff;
  --bg-card:       #ffffff;
  --bg-hover:      #f8fafc;
  --bg-input:      #f8fafc;
  --border:        #e2e8f0;
  --border-input:  #cbd5e1;
  --text-primary:  #1e293b;
  --text-secondary:#475569;
  --text-muted:    #94a3b8;
  --text-label:    #cbd5e1;
  --brand-name:    #c8773a;
  --accent:        #c8773a;
  --accent-hover:  rgba(200,119,58,0.08);
  --accent-border: rgba(200,119,58,0.2);
  --active-text:   #c8773a;
  --nav-icon-bg:   #f1f5f9;
  --logout-bg:     rgba(239,68,68,0.06);
  --logout-border: rgba(239,68,68,0.15);
  --logout-color:  #ef4444;
  --logout-hover:  rgba(239,68,68,0.1);
  --content-color: #1e293b;
}

/* ── Shell ── */
.admin-shell {
  display: flex;
  min-height: 100vh;
  background: var(--bg-body);
  font-family: 'Inter', sans-serif;
  transition: background 0.3s, color 0.3s;
}

/* ── Sidebar ── */
.sidebar {
  width: 260px;
  background: var(--bg-sidebar);
  border-right: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  position: fixed;
  height: 100vh;
  z-index: 50;
  transition: background 0.3s, border-color 0.3s;
  box-shadow: 2px 0 12px rgba(0,0,0,0.06);
}

.sidebar-brand {
  display: flex;
  align-items: center;
  gap: 0.85rem;
  padding: 1.5rem 1.25rem;
  border-bottom: 1px solid var(--border);
}

.brand-icon  { font-size: 1.8rem; }
.brand-name  { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; color: var(--brand-name); line-height: 1.2; }
.brand-sub   { font-size: 0.7rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.08em; }

.sidebar-nav { flex: 1; padding: 1.25rem 0.75rem; overflow-y: auto; }

.nav-section-label {
  font-size: 0.65rem;
  font-weight: 600;
  color: var(--text-label);
  text-transform: uppercase;
  letter-spacing: 0.1em;
  padding: 0 0.75rem;
  margin-bottom: 0.5rem;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.7rem 0.75rem;
  border-radius: 10px;
  text-decoration: none;
  font-size: 0.875rem;
  font-weight: 500;
  color: var(--text-secondary);
  transition: all 0.18s;
  margin-bottom: 0.2rem;
  border: 1px solid transparent;
}

.nav-item:hover { background: var(--bg-hover); color: var(--text-primary); }

.nav-item.active {
  background: var(--accent-hover);
  color: var(--active-text);
  border-color: var(--accent-border);
}

.nav-item.active .nav-icon { background: var(--accent-hover); color: var(--active-text); }

.nav-icon {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--nav-icon-bg);
  font-size: 0.8rem;
  color: var(--text-muted);
  flex-shrink: 0;
  transition: all 0.18s;
}

/* Sidebar footer */
.sidebar-footer {
  padding: 1rem 1.25rem;
  border-top: 1px solid var(--border);
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.admin-user { display: flex; align-items: center; gap: 0.6rem; flex: 1; min-width: 0; }

.admin-avatar {
  width: 34px; height: 34px;
  background: linear-gradient(135deg, #c8773a, #e8945a);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 0.8rem; font-weight: 700; color: white; flex-shrink: 0;
}

.admin-name { font-size: 0.8rem; font-weight: 600; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.admin-role { font-size: 0.68rem; color: var(--text-muted); }

.sidebar-logout { padding: 0 0.75rem 1.25rem; }

.logout-full-btn {
  width: 100%;
  display: flex; align-items: center; justify-content: center; gap: 0.6rem;
  padding: 0.65rem 1rem;
  background: var(--logout-bg);
  border: 1px solid var(--logout-border);
  border-radius: 10px;
  color: var(--logout-color);
  font-size: 0.83rem; font-weight: 500;
  cursor: pointer;
  transition: all 0.18s;
  font-family: 'Inter', sans-serif;
}

.logout-full-btn:hover { background: var(--logout-hover); }

/* ── Main ── */
.admin-main {
  flex: 1;
  margin-left: 260px;
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

/* Topbar */
.topbar {
  background: var(--bg-topbar);
  border-bottom: 1px solid var(--border);
  padding: 0 2rem;
  height: 62px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: sticky;
  top: 0;
  z-index: 40;
  transition: background 0.3s, border-color 0.3s;
  box-shadow: 0 1px 8px rgba(0,0,0,0.04);
}

.breadcrumb { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; }
.breadcrumb-home    { color: var(--text-muted); }
.breadcrumb-sep     { color: var(--border); }
.breadcrumb-current { color: var(--text-primary); font-weight: 500; }

.topbar-right { display: flex; align-items: center; gap: 1.25rem; }
.topbar-date  { font-size: 0.78rem; color: var(--text-muted); }

.topbar-user {
  display: flex; align-items: center; gap: 0.6rem;
  font-size: 0.82rem; color: var(--text-secondary);
}

.topbar-avatar {
  width: 30px; height: 30px;
  background: linear-gradient(135deg, #c8773a, #e8945a);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 0.75rem; font-weight: 700; color: white;
}

/* Content */
.admin-content {
  flex: 1;
  padding: 2rem;
  color: var(--content-color);
  transition: color 0.3s;
}
</style>
