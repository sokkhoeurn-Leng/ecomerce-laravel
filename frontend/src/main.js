import { createApp } from 'vue'
import App from './App.vue'
import './main.css'
import { createRouter, createWebHistory } from 'vue-router'
import axios from 'axios'

axios.defaults.baseURL = 'http://localhost:8000/api'
axios.defaults.withCredentials = true

const token = localStorage.getItem('token')
if (token) axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

const authOnly = (to, from, next) => {
  if (!localStorage.getItem('token')) next('/login')
  else next()
}

const router = createRouter({
  history: createWebHistory(),
  scrollBehavior: () => ({ top: 0 }),
  routes: [
    { path: '/',              name: 'Home',        component: () => import('./views/Home.vue') },
    { path: '/login',         name: 'Login',       component: () => import('./views/Login.vue') },
    { path: '/register',      name: 'Register',    component: () => import('./views/Register.vue') },
    { path: '/shop',          name: 'Shop',        component: () => import('./views/shop/Products.vue') },
    { path: '/products/:id',  name: 'ProductDetail', component: () => import('./views/shop/ProductDetail.vue') },
    { path: '/wishlist',      name: 'Wishlist',    component: () => import('./views/Wishlist.vue'),   beforeEnter: authOnly },
    { path: '/cart',          name: 'Cart',        component: () => import('./views/Cart.vue'),        beforeEnter: authOnly },
    { path: '/checkout',      name: 'Checkout',    component: () => import('./views/Checkout.vue'),    beforeEnter: authOnly },
    { path: '/orders',        name: 'Orders',      component: () => import('./views/Orders.vue'),      beforeEnter: authOnly },
    { path: '/orders/:id',    name: 'OrderDetail', component: () => import('./views/OrderDetail.vue'), beforeEnter: authOnly },
    { path: '/profile',       name: 'Profile',     component: () => import('./views/Profile.vue'),     beforeEnter: authOnly },
    { path: '/about',         name: 'About',       component: () => import('./views/About.vue') },
    {
      path: '/admin',
      component: () => import('./views/admin/AdminLayout.vue'),
      meta: { requiresAdmin: true },
      children: [
        { path: '',           name: 'AdminDashboard',  component: () => import('./views/admin/AdminDashboard.vue') },
        { path: 'categories', name: 'AdminCategories', component: () => import('./views/admin/AdminCategories.vue') },
        { path: 'products',   name: 'AdminProducts',   component: () => import('./views/admin/AdminProducts.vue') },
        { path: 'orders',     name: 'AdminOrders',     component: () => import('./views/admin/AdminOrders.vue') },
        { path: 'users',      name: 'AdminUsers',      component: () => import('./views/admin/AdminUsers.vue') },
      ]
    },
    { path: '/:pathMatch(.*)*', redirect: '/' },
  ]
})

router.beforeEach((to) => {
  const user = JSON.parse(localStorage.getItem('user'))
  if (to.meta.requiresAdmin && (!user || !user.is_admin)) return { name: 'Login' }
})

createApp(App).use(router).mount('#app')
