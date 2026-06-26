<template>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                    <input 
                        v-model="search" 
                        type="text" 
                        placeholder="Search categories..."
                        class="bg-gray-900 border border-gray-800 rounded-lg pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 w-64"
                        @input="debouncedSearch"
                    >
                </div>
                <select 
                    v-model="statusFilter" 
                    @change="fetchCategories"
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
                Add Category
            </button>
        </div>

        <!-- Table -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-800">
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Category</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Slug</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Products</th>
                            <th class="text-left text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Status</th>
                            <th class="text-right text-xs font-medium text-gray-500 uppercase tracking-wider px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        <tr v-for="category in categories" :key="category.id" class="hover:bg-gray-800/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-800 overflow-hidden flex-shrink-0">
                                        <img v-if="category.image" :src="'/storage/' + category.image" :alt="category.name" class="w-full h-full object-cover">
                                        <div v-else class="w-full h-full flex items-center justify-center text-gray-600">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">{{ category.name }}</p>
                                        <p class="text-xs text-gray-500 truncate max-w-[200px]">{{ category.description || 'No description' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <code class="text-xs text-gray-500 bg-gray-800 px-2 py-1 rounded">{{ category.slug }}</code>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm">{{ category.products_count || 0 }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <button 
                                    @click="toggleStatus(category)"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium transition-colors"
                                    :class="category.status ? 'bg-green-500/10 text-green-500' : 'bg-gray-500/10 text-gray-500'"
                                >
                                    <span class="w-1.5 h-1.5 rounded-full" :class="category.status ? 'bg-green-500' : 'bg-gray-500'"></span>
                                    {{ category.status ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEditModal(category)" class="p-2 text-gray-500 hover:text-blue-500 transition-colors" title="Edit">
                                        <i class="fas fa-pen text-sm"></i>
                                    </button>
                                    <button @click="confirmDelete(category)" class="p-2 text-gray-500 hover:text-red-500 transition-colors" title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!categories.length && !loading">
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-folder-open text-3xl mb-3 block"></i>
                                No categories found
                            </td>
                        </tr>
                        <tr v-if="loading">
                            <td colspan="5" class="px-6 py-12 text-center">
                                <i class="fas fa-spinner fa-spin text-amber-500 text-2xl"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <Pagination :pagination="pagination" @change="fetchCategories" />

        <!-- Create/Edit Modal -->
        <Modal :show="showModal" :title="editingCategory ? 'Edit Category' : 'Create Category'" @close="closeModal">
            <form @submit.prevent="submitForm" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Name *</label>
                    <input 
                        v-model="form.name" 
                        type="text" 
                        required
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500"
                        placeholder="Category name"
                    >
                    <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name[0] }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Slug</label>
                    <input 
                        v-model="form.slug" 
                        type="text" 
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500"
                        placeholder="auto-generated-from-name"
                    >
                    <p class="text-xs text-gray-600 mt-1">Auto-generated from name if left empty</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Description</label>
                    <textarea 
                        v-model="form.description" 
                        rows="3"
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500 resize-none"
                        placeholder="Category description"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5">Image</label>
                    <input 
                        type="file" 
                        accept="image/*"
                        @change="handleImageUpload"
                        class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-800 file:text-gray-300 hover:file:bg-gray-700 file:cursor-pointer"
                    >
                    <div v-if="imagePreview || (editingCategory?.image)" class="mt-3">
                        <img :src="imagePreview || '/storage/' + editingCategory?.image" class="w-20 h-20 object-cover rounded-lg">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" v-model="form.status" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-amber-500/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                    </label>
                    <span class="text-sm text-gray-400">Active</span>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-800">
                    <button type="button" @click="closeModal" class="px-4 py-2.5 text-sm text-gray-400 hover:text-white transition-colors">
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        :disabled="submitting"
                        class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-gray-900 font-medium rounded-lg text-sm transition-colors disabled:opacity-50"
                    >
                        <i v-if="submitting" class="fas fa-spinner fa-spin mr-2"></i>
                        {{ editingCategory ? 'Update' : 'Create' }} Category
                    </button>
                </div>
            </form>
        </Modal>

        <!-- Delete Confirmation Modal -->
        <Modal :show="showDeleteModal" title="Delete Category" size="sm" @close="showDeleteModal = false">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                </div>
                <p class="text-sm text-gray-400 mb-6">
                    Are you sure you want to delete <strong class="text-white">{{ deletingCategory?.name }}</strong>? 
                    This will also delete all associated products. This action cannot be undone.
                </p>
                <div class="flex justify-center gap-3">
                    <button @click="showDeleteModal = false" class="px-4 py-2.5 text-sm text-gray-400 hover:text-white transition-colors">
                        Cancel
                    </button>
                    <button 
                        @click="deleteCategory"
                        :disabled="deleting"
                        class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg text-sm transition-colors disabled:opacity-50"
                    >
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

const categories = ref([]);
const pagination = ref({});
const loading = ref(false);
const submitting = ref(false);
const deleting = ref(false);

const search = ref('');
const statusFilter = ref('');
const page = ref(1);

const showModal = ref(false);
const showDeleteModal = ref(false);
const editingCategory = ref(null);
const deletingCategory = ref(null);

const form = ref({
    name: '',
    slug: '',
    description: '',
    status: true,
});

const imageFile = ref(null);
const imagePreview = ref(null);
const errors = ref({});

let searchTimeout;

function debouncedSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        page.value = 1;
        fetchCategories();
    }, 300);
}

async function fetchCategories(pageNum = 1) {
    loading.value = true;
    page.value = pageNum;
    
    try {
        const params = new URLSearchParams({
            page: pageNum,
            search: search.value,
            status: statusFilter.value,
        });

        const response = await fetch(`/admin/api/categories?${params}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        });

        const data = await response.json();
        categories.value = data.data;
        pagination.value = {
            current_page: data.current_page,
            last_page: data.last_page,
            per_page: data.per_page,
            total: data.total,
            from: data.from,
            to: data.to,
        };
    } catch (error) {
        console.error('Error fetching categories:', error);
    } finally {
        loading.value = false;
    }
}

function openCreateModal() {
    editingCategory.value = null;
    form.value = { name: '', slug: '', description: '', status: true };
    imageFile.value = null;
    imagePreview.value = null;
    errors.value = {};
    showModal.value = true;
}

function openEditModal(category) {
    editingCategory.value = category;
    form.value = {
        name: category.name,
        slug: category.slug,
        description: category.description || '',
        status: category.status,
    };
    imageFile.value = null;
    imagePreview.value = null;
    errors.value = {};
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
    editingCategory.value = null;
}

function handleImageUpload(event) {
    const file = event.target.files[0];
    if (file) {
        imageFile.value = file;
        imagePreview.value = URL.createObjectURL(file);
    }
}

async function submitForm() {
    submitting.value = true;
    errors.value = {};

    try {
        const formData = new FormData();
        formData.append('name', form.value.name);
        formData.append('slug', form.value.slug);
        formData.append('description', form.value.description);
        formData.append('status', form.value.status ? '1' : '0');
        
        if (imageFile.value) {
            formData.append('image', imageFile.value);
        }

        const url = editingCategory.value 
            ? `/admin/api/categories/${editingCategory.value.id}`
            : '/admin/api/categories';

        const method = editingCategory.value ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: formData,
        });

        const data = await response.json();

        if (!response.ok) {
            if (data.errors) {
                errors.value = data.errors;
            }
            throw new Error(data.message || 'Something went wrong');
        }

        closeModal();
        await fetchCategories();
        
        // Show success notification
        showToast(editingCategory.value ? 'Category updated successfully' : 'Category created successfully');
    } catch (error) {
        console.error('Error submitting form:', error);
    } finally {
        submitting.value = false;
    }
}

async function toggleStatus(category) {
    try {
        const formData = new FormData();
        formData.append('name', category.name);
        formData.append('status', !category.status ? '1' : '0');

        const response = await fetch(`/admin/api/categories/${category.id}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-HTTP-Method-Override': 'PUT',
            },
            body: formData,
        });

        if (response.ok) {
            category.status = !category.status;
            showToast('Status updated successfully');
        }
    } catch (error) {
        console.error('Error updating status:', error);
    }
}

function confirmDelete(category) {
    deletingCategory.value = category;
    showDeleteModal.value = true;
}

async function deleteCategory() {
    deleting.value = true;
    
    try {
        const response = await fetch(`/admin/api/categories/${deletingCategory.value.id}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        });

        if (!response.ok) throw new Error('Failed to delete');

        showDeleteModal.value = false;
        await fetchCategories();
        showToast('Category deleted successfully');
    } catch (error) {
        console.error('Error deleting category:', error);
    } finally {
        deleting.value = false;
    }
}

function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-[200] flex items-center gap-2 animate-fade-in';
    toast.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

onMounted(() => {
    fetchCategories();
});
</script>