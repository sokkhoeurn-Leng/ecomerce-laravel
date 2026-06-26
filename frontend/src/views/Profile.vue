<template>
  <div class="page">
    <div class="page-inner">
      <h1 class="page-title">👤 My Profile</h1>

      <div class="profile-layout">
        <!-- Left: Avatar + Nav -->
        <div class="sidebar">
          <div class="avatar-section">
            <div class="avatar-wrap">
              <img v-if="avatarPreview" :src="avatarPreview" class="avatar-img" />
              <div v-else class="avatar-placeholder">{{ userInitial }}</div>
              <label class="avatar-edit" title="Upload photo">
                <i class="fas fa-camera"></i>
                <input type="file" accept="image/*" @change="handleAvatar" hidden />
              </label>
            </div>
            <p class="avatar-name">{{ profileForm.name }}</p>
            <p class="avatar-email">{{ profileForm.email }}</p>
          </div>

          <nav class="profile-nav">
            <button @click="activeTab = 'profile'" :class="{ active: activeTab === 'profile' }">
              <i class="fas fa-user"></i> Profile Info
            </button>
            <button @click="activeTab = 'password'" :class="{ active: activeTab === 'password' }">
              <i class="fas fa-lock"></i> Change Password
            </button>
            <button @click="activeTab = 'orders'" :class="{ active: activeTab === 'orders' }">
              <i class="fas fa-receipt"></i> My Orders
            </button>
          </nav>
        </div>

        <!-- Right: Tab Content -->
        <div class="content-col">
          <!-- Profile Info Tab -->
          <div v-if="activeTab === 'profile'" class="tab-card">
            <h2>Profile Information</h2>
            <div class="fields-grid">
              <div class="field full">
                <label>Full Name</label>
                <div class="input-wrap"><i class="fas fa-user"></i><input v-model="profileForm.name" type="text" placeholder="Your name" /></div>
              </div>
              <div class="field full">
                <label>Email Address</label>
                <div class="input-wrap"><i class="fas fa-envelope"></i><input v-model="profileForm.email" type="email" placeholder="your@email.com" /></div>
              </div>
              <div class="field">
                <label>Phone</label>
                <div class="input-wrap"><i class="fas fa-phone"></i><input v-model="profileForm.phone" type="tel" placeholder="+855 xx xxx xxx" /></div>
              </div>
              <div class="field">
                <label>Address</label>
                <div class="input-wrap"><i class="fas fa-map-marker-alt"></i><input v-model="profileForm.address" type="text" placeholder="Your address" /></div>
              </div>
            </div>

            <div v-if="profileMsg" class="msg" :class="profileMsg.type">
              <i :class="profileMsg.type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'"></i>
              {{ profileMsg.text }}
            </div>

            <button @click="saveProfile" :disabled="profileLoading" class="save-btn">
              <i :class="profileLoading ? 'fas fa-spinner fa-spin' : 'fas fa-save'"></i>
              {{ profileLoading ? 'Saving...' : 'Save Changes' }}
            </button>
          </div>

          <!-- Password Tab -->
          <div v-if="activeTab === 'password'" class="tab-card">
            <h2>Change Password</h2>
            <div class="fields-grid single">
              <div class="field full">
                <label>Current Password</label>
                <div class="input-wrap">
                  <i class="fas fa-lock"></i>
                  <input :type="showCurrent ? 'text' : 'password'" v-model="passForm.current_password" placeholder="Current password" />
                  <button type="button" class="eye-btn" @click="showCurrent = !showCurrent"><i :class="showCurrent ? 'fas fa-eye-slash' : 'fas fa-eye'"></i></button>
                </div>
              </div>
              <div class="field full">
                <label>New Password</label>
                <div class="input-wrap">
                  <i class="fas fa-lock"></i>
                  <input :type="showNew ? 'text' : 'password'" v-model="passForm.password" placeholder="Min. 8 characters" />
                  <button type="button" class="eye-btn" @click="showNew = !showNew"><i :class="showNew ? 'fas fa-eye-slash' : 'fas fa-eye'"></i></button>
                </div>
              </div>
              <div class="field full">
                <label>Confirm New Password</label>
                <div class="input-wrap">
                  <i class="fas fa-lock"></i>
                  <input :type="showConfirm ? 'text' : 'password'" v-model="passForm.password_confirmation" placeholder="Repeat new password" />
                  <button type="button" class="eye-btn" @click="showConfirm = !showConfirm"><i :class="showConfirm ? 'fas fa-eye-slash' : 'fas fa-eye'"></i></button>
                </div>
              </div>
            </div>

            <div v-if="passMsg" class="msg" :class="passMsg.type">
              <i :class="passMsg.type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'"></i>
              {{ passMsg.text }}
            </div>

            <button @click="changePassword" :disabled="passLoading" class="save-btn">
              <i :class="passLoading ? 'fas fa-spinner fa-spin' : 'fas fa-key'"></i>
              {{ passLoading ? 'Updating...' : 'Update Password' }}
            </button>
          </div>

          <!-- Orders Tab -->
          <div v-if="activeTab === 'orders'" class="tab-card">
            <h2>Recent Orders</h2>
            <div v-if="ordersLoading" class="loading-state"><i class="fas fa-spinner fa-spin"></i></div>
            <div v-else-if="!recentOrders.length" class="empty-orders">
              <i class="fas fa-receipt"></i><p>No orders yet.</p>
              <router-link to="/shop">Start Shopping</router-link>
            </div>
            <div v-else class="orders-list">
              <router-link v-for="o in recentOrders" :key="o.id" :to="`/orders/${o.id}`" class="order-row">
                <div class="order-left">
                  <p class="o-num">{{ o.order_number }}</p>
                  <p class="o-date">{{ formatDate(o.created_at) }}</p>
                </div>
                <div class="order-right">
                  <span class="o-total">${{ Number(o.total).toFixed(2) }}</span>
                  <span class="status-badge" :class="`status-${o.status}`">{{ o.status }}</span>
                </div>
              </router-link>
              <router-link to="/orders" class="view-all-link">View all orders <i class="fas fa-arrow-right"></i></router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import api from '../services/api'
import { auth } from '../stores/auth'

const activeTab = ref('profile')

const profileForm = ref({ name: '', email: '', phone: '', address: '' })
const profileLoading = ref(false)
const profileMsg = ref(null)
const avatarPreview = ref(null)

const passForm = ref({ current_password: '', password: '', password_confirmation: '' })
const passLoading = ref(false)
const passMsg = ref(null)
const showCurrent = ref(false)
const showNew = ref(false)
const showConfirm = ref(false)

const recentOrders = ref([])
const ordersLoading = ref(false)

const userInitial = computed(() => profileForm.value.name?.charAt(0).toUpperCase() || '?')

function formatDate(d) {
  return new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

function handleAvatar(e) {
  const file = e.target.files[0]
  if (!file) return
  avatarPreview.value = URL.createObjectURL(file)
  // TODO: upload to backend
}

async function saveProfile() {
  profileLoading.value = true
  profileMsg.value = null
  try {
    const { data } = await api.put('/profile', {
      name: profileForm.value.name,
      email: profileForm.value.email,
    })
    auth.user = data.user
    localStorage.setItem('user', JSON.stringify(data.user))
    profileMsg.value = { type: 'success', text: 'Profile updated successfully!' }
  } catch (e) {
    profileMsg.value = { type: 'error', text: e.response?.data?.message || 'Update failed.' }
  } finally { profileLoading.value = false }
  setTimeout(() => profileMsg.value = null, 4000)
}

async function changePassword() {
  passLoading.value = true
  passMsg.value = null
  try {
    await api.put('/profile/password', passForm.value)
    passMsg.value = { type: 'success', text: 'Password changed successfully!' }
    passForm.value = { current_password: '', password: '', password_confirmation: '' }
  } catch (e) {
    passMsg.value = { type: 'error', text: e.response?.data?.message || 'Failed to change password.' }
  } finally { passLoading.value = false }
  setTimeout(() => passMsg.value = null, 4000)
}

onMounted(async () => {
  const { data } = await api.get('/profile')
  const user = data.user
  profileForm.value = { name: user.name, email: user.email, phone: user.phone || '', address: user.address || '' }

  ordersLoading.value = true
  try {
    const { data: od } = await api.get('/orders', { params: { page: 1, per_page: 5 } })
    recentOrders.value = od.data
  } finally { ordersLoading.value = false }
})
</script>

<style scoped>
.page { background: #fdf8f3; min-height: 100vh; padding: 2.5rem 0; }
.page-inner { max-width: 1000px; margin: 0 auto; padding: 0 1.5rem; }
.page-title { font-family: 'Playfair Display', serif; font-size: 2rem; color: #2d1b0e; margin-bottom: 2rem; }

.profile-layout { display: grid; grid-template-columns: 240px 1fr; gap: 2rem; align-items: start; }

/* Sidebar */
.sidebar { display: flex; flex-direction: column; gap: 1.25rem; }

.avatar-section { background: white; border: 1px solid #f0e6d8; border-radius: 16px; padding: 1.5rem; text-align: center; }
.avatar-wrap { position: relative; width: 90px; height: 90px; margin: 0 auto 1rem; }
.avatar-img { width: 90px; height: 90px; border-radius: 50%; object-fit: cover; }
.avatar-placeholder { width: 90px; height: 90px; border-radius: 50%; background: linear-gradient(135deg, #c8773a, #e8945a); display: flex; align-items: center; justify-content: center; font-size: 2.2rem; font-weight: 700; color: white; }
.avatar-edit { position: absolute; bottom: 0; right: 0; width: 28px; height: 28px; background: white; border: 2px solid #f0e6d8; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.7rem; color: #c8773a; transition: all 0.2s; }
.avatar-edit:hover { background: #fdf0e6; border-color: #c8773a; }
.avatar-name  { font-family: 'Playfair Display', serif; font-weight: 700; color: #2d1b0e; margin-bottom: 0.25rem; }
.avatar-email { font-size: 0.78rem; color: #9a7560; }

.profile-nav { background: white; border: 1px solid #f0e6d8; border-radius: 16px; overflow: hidden; }
.profile-nav button { display: flex; align-items: center; gap: 0.75rem; width: 100%; padding: 0.9rem 1.1rem; border: none; background: transparent; font-family: 'Inter', sans-serif; font-size: 0.875rem; color: #6b4c30; cursor: pointer; transition: all 0.15s; border-bottom: 1px solid #f0e6d8; }
.profile-nav button:last-child { border-bottom: none; }
.profile-nav button:hover { background: #fdf5ef; color: #c8773a; }
.profile-nav button.active { background: #fdf5ef; color: #c8773a; font-weight: 600; }
.profile-nav button i { width: 16px; text-align: center; }

/* Content */
.tab-card { background: white; border: 1px solid #f0e6d8; border-radius: 16px; padding: 1.75rem; }
.tab-card h2 { font-family: 'Playfair Display', serif; font-size: 1.3rem; color: #2d1b0e; margin-bottom: 1.5rem; }

.fields-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; }
.fields-grid.single { grid-template-columns: 1fr; }
.field { display: flex; flex-direction: column; gap: 0.35rem; }
.field.full { grid-column: 1 / -1; }
.field label { font-size: 0.8rem; font-weight: 500; color: #4a3220; }

.input-wrap { position: relative; display: flex; align-items: center; }
.input-wrap > i:first-child { position: absolute; left: 0.85rem; color: #c8a07a; font-size: 0.82rem; pointer-events: none; }
.input-wrap input { width: 100%; padding: 0.7rem 0.9rem 0.7rem 2.5rem; border: 1.5px solid #e8d5c0; border-radius: 10px; font-size: 0.875rem; color: #2d1b0e; outline: none; font-family: 'Inter', sans-serif; background: white; transition: border-color 0.2s; }
.input-wrap input:focus { border-color: #c8773a; box-shadow: 0 0 0 3px rgba(200,119,58,0.1); }
.input-wrap input::placeholder { color: #c4a07a; }
.eye-btn { position: absolute; right: 0.85rem; background: none; border: none; color: #c8a07a; cursor: pointer; font-size: 0.82rem; }
.eye-btn:hover { color: #c8773a; }

.msg { display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1rem; border-radius: 10px; font-size: 0.85rem; font-weight: 500; margin-bottom: 1rem; }
.msg.success { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
.msg.error   { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

.save-btn { display: flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.75rem; background: linear-gradient(135deg, #c8773a, #e8945a); color: white; border: none; border-radius: 50px; font-size: 0.9rem; font-weight: 600; cursor: pointer; transition: all 0.2s; font-family: 'Inter', sans-serif; }
.save-btn:hover:not(:disabled) { filter: brightness(1.08); transform: translateY(-1px); }
.save-btn:disabled { opacity: 0.7; cursor: not-allowed; transform: none; }

/* Orders */
.loading-state { display: flex; justify-content: center; padding: 2rem; }
.loading-state i { color: #c8773a; font-size: 1.5rem; }
.empty-orders { text-align: center; padding: 2rem; color: #9a7560; }
.empty-orders i { font-size: 2rem; display: block; margin-bottom: 0.5rem; }
.empty-orders a { color: #c8773a; font-weight: 600; text-decoration: none; margin-top: 0.5rem; display: inline-block; }
.orders-list { display: flex; flex-direction: column; gap: 0.5rem; }
.order-row { display: flex; align-items: center; justify-content: space-between; padding: 0.85rem 1rem; border: 1px solid #f0e6d8; border-radius: 10px; text-decoration: none; transition: all 0.2s; }
.order-row:hover { background: #fdf5ef; border-color: #e8c9a0; }
.o-num  { font-size: 0.875rem; font-weight: 600; color: #2d1b0e; margin-bottom: 0.15rem; }
.o-date { font-size: 0.75rem; color: #9a7560; }
.order-right { display: flex; align-items: center; gap: 0.75rem; }
.o-total { font-size: 0.9rem; font-weight: 700; color: #c8773a; }
.status-badge { font-size: 0.68rem; font-weight: 600; padding: 0.2rem 0.6rem; border-radius: 50px; text-transform: capitalize; }
.status-pending    { background: rgba(245,158,11,0.12); color: #d97706; }
.status-processing { background: rgba(99,102,241,0.12); color: #6366f1; }
.status-shipped    { background: rgba(139,92,246,0.12); color: #7c3aed; }
.status-delivered  { background: rgba(16,185,129,0.12); color: #059669; }
.status-cancelled  { background: rgba(239,68,68,0.12); color: #dc2626; }
.view-all-link { display: flex; align-items: center; gap: 0.4rem; color: #c8773a; font-size: 0.85rem; font-weight: 600; text-decoration: none; padding: 0.5rem 0; justify-content: flex-end; margin-top: 0.25rem; }

@media (max-width: 768px) {
  .profile-layout { grid-template-columns: 1fr; }
  .fields-grid { grid-template-columns: 1fr; }
}
</style>
