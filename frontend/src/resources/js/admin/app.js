import { createApp } from 'vue';

// Components
import Dashboard from './components/Dashboard.vue';
import Categories from './components/Categories.vue';
import Products from './components/Product.vue';
import Orders from './components/Order.vue';
import OrderShow from './components/OrderShow.vue';
import Users from './components/Users.vue';
import UserShow from './components/UserShow.vue';

// Mount components based on page
document.addEventListener('DOMContentLoaded', () => {
    const app = createApp({});

    // Dashboard
    const dashboardEl = document.getElementById('admin-dashboard');
    if (dashboardEl) {
        app.component('admin-dashboard', Dashboard);
        app.mount('#admin-dashboard');
        return;
    }

    // Categories
    const categoriesEl = document.getElementById('admin-categories');
    if (categoriesEl) {
        app.component('admin-categories', Categories);
        app.mount('#admin-categories');
        return;
    }

    // Products
    const productsEl = document.getElementById('admin-products');
    if (productsEl) {
        app.component('admin-products', Products);
        app.mount('#admin-products');
        return;
    }

    // Orders
    const ordersEl = document.getElementById('admin-orders');
    if (ordersEl) {
        app.component('admin-orders', Orders);
        app.mount('#admin-orders');
        return;
    }

    // Order Show
    const orderShowEl = document.getElementById('admin-order-show');
    if (orderShowEl) {
        app.component('admin-order-show', OrderShow);
        app.mount('#admin-order-show');
        return;
    }

    // Users
    const usersEl = document.getElementById('admin-users');
    if (usersEl) {
        app.component('admin-users', Users);
        app.mount('#admin-users');
        return;
    }

    // User Show
    const userShowEl = document.getElementById('admin-user-show');
    if (userShowEl) {
        app.component('admin-user-show', UserShow);
        app.mount('#admin-user-show');
        return;
    }
});
