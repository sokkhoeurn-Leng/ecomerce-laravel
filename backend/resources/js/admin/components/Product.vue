<template>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3 flex-wrap">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                    <input 
                        v-model="search" 
                        type="text" 
                        placeholder="Search products..."
                        class="bg-gray-900 border border-gray-800 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 w-64"
                        @input="debouncedSearch"
                    >
                </div>
                <select 
                    v-model="categoryFilter" 
                    @change="fetchProducts"
                    class="bg-gray-900 border border-gray-800 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50"
                >
                    <option value="">All Categories</option>
                    <option v-for="cat in categoriesList" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
                <select 
                    v-model="statusFilter" 
                    @change="fetchProducts"
                    class="bg-gray-900 border border-gray-800 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50"
                >
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <button 
                @click="openCreateModal"
                class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-gray-900 font-medium px-4 py-2.5 rounded-lg transition-colors text-sm"
            >
                <i class="fas fa-plus"></i>
                Add Product
            </button>
        </div>

        <!-- Table -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-800">
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Product</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Category</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Price</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Stock</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Status</th>
                            <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <tr v-for="product in products" :key="product.id" class="hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-gray-800 overflow-hidden flex-shrink-0">
                                        <img v-if="product.image" :src="'/storage/' + product.image" :alt="product.name" class="w-full h-full object-cover">
                                        <div v-else class="w-full h-full flex items-center justify-center text-gray-600">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">{{ product.name }}</p>
                                        <p class="text-xs text-gray-500">SKU: {{ product.sku || 'N/A' }}</p>
                                        <div v-if="product.featured" class="flex items-center gap-1 mt-1">
                                            <i class="fas fa-star text-amber-500 text-[10px]"></i>
                                            <span class="text-[10px] text-amber-500">Featured</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-400">{{ product.category?.name || 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-medium">{{ formatCurrency(product.price) }}</p>
                                    <p v-if="product.compare_price" class="text-xs text-gray-500 line-through">
                                        {{ formatCurrency(product.compare_price) }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span 
                                    class="text-sm font-medium"
                                    :class="product.stock_quantity < 10 ? 'text-red-500' : 'text-gray-300'"
                                >
                                    {{ product.stock_quantity }}
                                </span>
                                <p v-if="product.stock_quantity < 10" class="text-[10px] text-red-500">Low Stock</p>
                            </td>
                            <td class="px-6 py-4">
                                <button 
                                    @click="toggleStatus(product)"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium transition-colors"
                                    :class="product.status ? 'bg-green-500/10 text-green-500' : 'bg-gray-500/10 text-gray-500'"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full" :class="product.status ? 'bg-green-500' : 'bg-gray-500'"></span>
                                    {{ product.status ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEditModal(product)" class="p-2 text-gray-500 hover:text-blue-500 transition-colors" title="Edit">
                                        <i class="fas fa-pen text-sm"></i>
                                    </button>
                                    <button @click="confirmDelete(product)" class="p-2 text-gray-500 hover:text-red-500 transition-colors" title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!products.length && !loading">
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-box-open text-3xl mb-3 block"></i>
                                No products found
                            </td>
                        </tr>
                        <tr v-if="loading">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <i class="fas fa-spinner fa-spin text-amber-500 text-2xl"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <Pagination :pagination="pagination" @change="fetchProducts" />

        <!-- Create/Edit Modal -->
        <Modal :show="showModal" :title="editingProduct ? 'Edit Product' : 'Create Product'" size="lg" @close="closeModal">
            <form @submit.prevent="submitForm" class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1.5">Name *</label>
                        <input v-model="form.name" type="text" required
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500"
                            placeholder="Product name">
                        <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name[0] }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1.5">SKU</label>
                        <input v-model="form.sku" type="text"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500"
                            placeholder="SKU-001">
                        <p v-if="errors.sku" class="text-red-500 text-xs mt-1">{{ errors.sku[0] }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Category *</label>
                    <select v-model="form.category_id" required
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500">
                        <option value="">Select Category</option>
                        <option v-for="cat in categoriesList" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                    </select>
                    <p v-if="errors.category_id" class="text-red-500 text-xs mt-1">{{ errors.category_id[0] }}</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1.5">Price *</label>
                        <input v-model="form.price" type="number" step="0.01" min="0" required
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500"
                            placeholder="0.00">
                        <p v-if="errors.price" class="text-red-500 text-xs mt-1">{{ errors.price[0] }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1.5">Compare Price</label>
                        <input v-model="form.compare_price" type="number" step="0.01" min="0"
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500"
                            placeholder="0.00">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1.5">Stock Quantity *</label>
                        <input v-model="form.stock_quantity" type="number" min="0" required
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500"
                            placeholder="0">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Short Description</label>
                    <input v-model="form.short_description" type="text" maxlength="500"
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500"
                        placeholder="Brief description">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Description</label>
                    <textarea v-model="form.description" rows="4"
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 resize-none"
                        placeholder="Full product description"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Main Image</label>
                    <input type="file" accept="image/*" @change="handleImageUpload"
                        class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-800 file:text-gray-300 hover:file:bg-gray-700 file:cursor-pointer">
                    <div v-if="imagePreview || editingProduct?.image" class="mt-3">
                        <img :src="imagePreview || '/storage/' + editingProduct?.image" class="w-24 h-24 object-cover rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Additional Images</label>
                    <input type="file" accept="image/*" multiple @change="handleImagesUpload"
                        class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-800 file:text-gray-300 hover:file:bg-gray-700 file:cursor-pointer">
                    <div v-if="imagesPreview.length || editingProduct?.images?.length" class="flex gap-2 mt-3 flex-wrap">
                        <img v-for="(img, i) in (imagesPreview.length ? imagesPreview : editingProduct?.images?.map(i => '/storage/' + i))" 
                             :key="i" :src="img" class="w-16 h-16 object-cover rounded-lg">
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" v-model="form.status" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-amber-500/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                        <span class="ml-3 text-sm text-gray-400">Active</span>
                    </label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" v-model="form.featured" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-amber-500/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                        <span class="ml-3 text-sm text-gray-400">Featured</span>
                    </label>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-800">
                    <button type="button" @click="closeModal" class="px-4 py-2.5 text-sm text-gray-400 hover:text-white transition-colors">
                        Cancel
                    </button>
                    <button type="submit" :disabled="submitting"
                        class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-gray-900 font-medium rounded-lg text-sm transition-colors disabled:opacity-50">
                        <i v-if="submitting" class="fas fa-spinner fa-spin mr-2"></i>
                        {{ editingProduct ? 'Update' : 'Create' }} Product
                    </button>
                </div>
            </form>
        </Modal>

        <!-- Delete Modal -->
        <Modal :show="showDeleteModal" title="Delete Product" size="sm" @close="showDeleteModal = false">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                </div>
                <p class="text-sm text-gray-400 mb-6">
                    Are you sure you want to delete <strong class="text-white">{{ deletingProduct?.name }}</strong>? This action cannot be undone.
                </p>
                <div class="flex justify-center gap-3">
                    <button @click="showDeleteModal = false" class="px-4 py-2.5 text-sm text-gray-400 hover:text-white transition-colors">
                        Cancel
                    </button>
                    <button @click="deleteProduct" :disabled="deleting"
                        class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg text-sm transition-colors disabled:opacity-50">
                        <i v-if="deleting" class="fas fa-spinner fa-spin mr-2"></i>
                        Delete
                    </button>
                </div>
            </div>
        </Modal>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Modal from './common/Modal.vue';
import Pagination from './common/Pagination.vue';

const products = ref([]);
const categoriesList = ref([]);
const pagination = ref({});
const loading = ref(false);
const submitting = ref(false);
const deleting = ref(false);

const search = ref('');
const categoryFilter = ref('');
const statusFilter = ref('');

const showModal = ref(false);
const showDeleteModal = ref(false);
const editingProduct = ref(null);
const deletingProduct = ref(null);

const form = ref({
    name: '', sku: '', category_id: '', price: '', compare_price: '',
    stock_quantity: '', short_description: '', description: '',
    status: true, featured: false,
});

const imageFile = ref(null);
const imagePreview = ref(null);
const imagesFiles = ref([]);
const imagesPreview = ref([]);
const errors = ref({});

let searchTimeout;

function debouncedSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => fetchProducts(1), 300);
}

async function fetchCategoriesList() {
    try {
        const response = await fetch('/admin/api/categories/list', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        categoriesList.value = await response.json();
    } catch (e) { console.error(e); }
}

async function fetchProducts(page = 1) {
    loading.value = true;
    try {
        const params = new URLSearchParams({
            page, search: search.value, category_id: categoryFilter.value, status: statusFilter.value
        });
        const response = await fetch(`/admin/api/products?${params}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        products.value = data.data;
        pagination.value = {
            current_page: data.current_page, last_page: data.last_page,
            per_page: data.per_page, total: data.total, from: data.from, to: data.to,
        };
    } catch (e) { console.error(e); }
    finally { loading.value = false; }
}

function openCreateModal() {
    editingProduct.value = null;
    form.value = { name: '', sku: '', category_id: '', price: '', compare_price: '', stock_quantity: '', short_description: '', description: '', status: true, featured: false };
    imageFile.value = null; imagePreview.value = null; imagesFiles.value = []; imagesPreview.value = [];
    errors.value = {}; showModal.value = true;
}

function openEditModal(product) {
    editingProduct.value = product;
    form.value = {
        name: product.name, sku: product.sku || '', category_id: product.category_id,
        price: product.price, compare_price: product.compare_price || '',
        stock_quantity: product.stock_quantity, short_description: product.short_description || '',
        description: product.description || '', status: product.status, featured: product.featured,
    };
    imageFile.value = null; imagePreview.value = null; imagesFiles.value = []; imagesPreview.value = [];
    errors.value = {}; showModal.value = true;
}

function closeModal() { showModal.value = false; editingProduct.value = null; }

function handleImageUpload(e) {
    const file = e.target.files[0];
    if (file) { imageFile.value = file; imagePreview.value = URL.createObjectURL(file); }
}

function handleImagesUpload(e) {
    imagesFiles.value = Array.from(e.target.files);
    imagesPreview.value = imagesFiles.value.map(f => URL.createObjectURL(f));
}

async function submitForm() {
    submitting.value = true; errors.value = {};
    try {
        const formData = new FormData();
        Object.entries(form.value).forEach(([key, val]) => {
            if (key === 'status' || key === 'featured') formData.append(key, val ? '1' : '0');
            else formData.append(key, val);
        });
        if (imageFile.value) formData.append('image', imageFile.value);
        imagesFiles.value.forEach(img => formData.append('images[]', img));

        const url = editingProduct.value ? `/admin/api/products/${editingProduct.value.id}` : '/admin/api/products';
        const method = editingProduct.value ? 'PUT' : 'POST';
        const response = await fetch(url, {
            method, headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, body: formData,
        });
        const data = await response.json();
        if (!response.ok) { if (data.errors) errors.value = data.errors; throw new Error(data.message); }
        closeModal(); await fetchProducts(); showToast(editingProduct.value ? 'Product updated' : 'Product created');
    } catch (e) { console.error(e); }
    finally { submitting.value = false; }
}

async function toggleStatus(product) {
    try {
        const formData = new FormData();
        Object.entries({
            name: product.name, price: product.price, stock_quantity: product.stock_quantity,
            category_id: product.category_id, status: !product.status ? '1' : '0'
        }).forEach(([k, v]) => formData.append(k, v));
        const response = await fetch(`/admin/api/products/${product.id}`, {
            method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-HTTP-Method-Override': 'PUT' }, body: formData,
        });
        if (response.ok) { product.status = !product.status; showToast('Status updated'); }
    } catch (e) { console.error(e); }
}

function confirmDelete(product) { deletingProduct.value = product; showDeleteModal.value = true; }

async function deleteProduct() {
    deleting.value = true;
    try {
        const response = await fetch(`/admin/api/products/${deletingProduct.value.id}`, {
            method: 'DELETE', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        });
        if (!response.ok) throw new Error();
        showDeleteModal.value = false; await fetchProducts(); showToast('Product deleted');
    } catch (e) { console.error(e); }
    finally { deleting.value = false; }
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
}

function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[200] flex items-center gap-2';
    toast.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(() => toast.remove(), 300); }, 3000);
}

onMounted(() => { fetchProducts(); fetchCategoriesList(); });
</script>