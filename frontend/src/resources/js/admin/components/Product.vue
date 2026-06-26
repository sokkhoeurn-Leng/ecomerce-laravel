<template>
  <div class="page">
    <!-- Toolbar -->
    <div class="toolbar">
      <div class="toolbar-left">
        <div class="search-box">
          <i class="fas fa-search search-icon"></i>
          <input
            v-model="search"
            type="text"
            placeholder="Search products..."
            @input="debouncedSearch"
          />
        </div>
        <select
          v-model="categoryFilter"
          @change="fetchProducts"
          class="filter-select"
        >
          <option value="">All Categories</option>
          <option v-for="cat in categoriesList" :key="cat.id" :value="cat.id">
            {{ cat.name }}
          </option>
        </select>
        <select
          v-model="statusFilter"
          @change="fetchProducts"
          class="filter-select"
        >
          <option value="">All Status</option>
          <option value="1">Active</option>
          <option value="0">Inactive</option>
        </select>
      </div>
      <button @click="openCreateModal" class="btn-primary">
        <i class="fas fa-plus"></i> Add Product
      </button>
    </div>

    <!-- Table -->
    <div class="table-card">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Product</th>
              <th>Category</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Status</th>
              <th class="th-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="6" class="td-center">
                <i class="fas fa-spinner fa-spin spinner"></i>
              </td>
            </tr>
            <tr v-else-if="!products.length">
              <td colspan="6" class="td-center td-empty">
                <i class="fas fa-box-open empty-icon"></i>
                <p>No products found</p>
              </td>
            </tr>
            <tr v-for="product in products" :key="product.id" v-else>
              <td>
                <div class="product-info">
                  <div class="product-thumb">
                    <img
                      v-if="product.image"
                      :src="'http://localhost:8000/storage/' + product.image"
                      :alt="product.name"
                    />
                    <i v-else class="fas fa-box"></i>
                  </div>
                  <div>
                    <p class="product-name">{{ product.name }}</p>
                    <p class="product-sku">SKU: {{ product.sku || "N/A" }}</p>
                    <span v-if="product.featured" class="featured-badge">
                      <i class="fas fa-star"></i> Featured
                    </span>
                  </div>
                </div>
              </td>
              <td>
                <span class="category-name">{{
                  product.category?.name || "N/A"
                }}</span>
              </td>
              <td>
                <p class="price-main">{{ formatCurrency(product.price) }}</p>
                <p v-if="product.compare_price" class="price-compare">
                  {{ formatCurrency(product.compare_price) }}
                </p>
              </td>
              <td>
                <span
                  class="stock-num"
                  :class="product.stock_quantity < 10 ? 'stock-low' : ''"
                >
                  {{ product.stock_quantity }}
                </span>
                <p v-if="product.stock_quantity < 10" class="stock-label">
                  Low Stock
                </p>
              </td>
              <td>
                <button
                  @click="toggleStatus(product)"
                  class="status-badge"
                  :class="product.status ? 'status-active' : 'status-inactive'"
                >
                  <span class="status-dot"></span>
                  {{ product.status ? "Active" : "Inactive" }}
                </button>
              </td>
              <td class="td-right">
                <div class="action-btns">
                  <button
                    @click="openEditModal(product)"
                    class="action-btn action-edit"
                    title="Edit"
                  >
                    <i class="fas fa-pen"></i>
                  </button>
                  <button
                    @click="confirmDelete(product)"
                    class="action-btn action-delete"
                    title="Delete"
                  >
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <Pagination :pagination="pagination" @change="fetchProducts" />

    <!-- Create/Edit Modal -->
    <Modal
      :show="showModal"
      :title="editingProduct ? 'Edit Product' : 'Create Product'"
      size="lg"
      @close="closeModal"
    >
      <form @submit.prevent="submitForm" class="modal-form">
        <div class="form-row">
          <div class="form-group">
            <label>Name <span class="required">*</span></label>
            <input
              v-model="form.name"
              type="text"
              required
              placeholder="Product name"
            />
            <p v-if="errors.name" class="form-error">{{ errors.name[0] }}</p>
          </div>
          <div class="form-group">
            <label>SKU</label>
            <input v-model="form.sku" type="text" placeholder="SKU-001" />
            <p v-if="errors.sku" class="form-error">{{ errors.sku[0] }}</p>
          </div>
        </div>

        <div class="form-group">
          <label>Category <span class="required">*</span></label>
          <select v-model="form.category_id" required>
            <option value="">Select Category</option>
            <option v-for="cat in categoriesList" :key="cat.id" :value="cat.id">
              {{ cat.name }}
            </option>
          </select>
          <p v-if="errors.category_id" class="form-error">
            {{ errors.category_id[0] }}
          </p>
        </div>

        <div class="form-row form-row-3">
          <div class="form-group">
            <label>Price <span class="required">*</span></label>
            <input
              v-model="form.price"
              type="number"
              step="0.01"
              min="0"
              required
              placeholder="0.00"
            />
            <p v-if="errors.price" class="form-error">{{ errors.price[0] }}</p>
          </div>
          <div class="form-group">
            <label>Compare Price</label>
            <input
              v-model="form.compare_price"
              type="number"
              step="0.01"
              min="0"
              placeholder="0.00"
            />
          </div>
          <div class="form-group">
            <label>Stock <span class="required">*</span></label>
            <input
              v-model="form.stock_quantity"
              type="number"
              min="0"
              required
              placeholder="0"
            />
          </div>
        </div>

        <div class="form-group">
          <label>Short Description</label>
          <input
            v-model="form.short_description"
            type="text"
            maxlength="500"
            placeholder="Brief description"
          />
        </div>

        <div class="form-group">
          <label>Description</label>
          <textarea
            v-model="form.description"
            rows="4"
            placeholder="Full product description"
          ></textarea>
        </div>

        <div class="form-group">
          <label>Main Image</label>
          <div class="upload-zone" @click="$refs.mainImageInput.click()" @dragover.prevent @drop.prevent="handleImageDrop">
            <input ref="mainImageInput" type="file" accept="image/*" @change="handleImageUpload" hidden />
            <div v-if="imagePreview || editingProduct?.image" class="upload-preview">
              <img :src="imagePreview || 'http://localhost:8000/storage/' + editingProduct?.image" />
              <div class="upload-overlay"><i class="fas fa-camera"></i> Change</div>
            </div>
            <div v-else class="upload-placeholder">
              <i class="fas fa-cloud-upload-alt"></i>
              <p>Click or drag to upload main image</p>
              <span>PNG, JPG, WEBP up to 2MB</span>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label>Additional Images</label>
          <div class="upload-zone multi" @click="$refs.multiImageInput.click()" @dragover.prevent @drop.prevent="handleImagesDrop">
            <input ref="multiImageInput" type="file" accept="image/*" multiple @change="handleImagesUpload" hidden />
            <div v-if="imagesPreview.length || editingProduct?.images?.length" class="imgs-preview">
              <img v-for="(img, i) in imagesPreview.length ? imagesPreview : editingProduct?.images?.map(x => 'http://localhost:8000/storage/' + x)" :key="i" :src="img" />
              <div class="add-more"><i class="fas fa-plus"></i></div>
            </div>
            <div v-else class="upload-placeholder">
              <i class="fas fa-images"></i>
              <p>Click or drag to upload more images</p>
              <span>Select multiple files</span>
            </div>
          </div>
        </div>

        <div class="toggle-row">
          <label class="toggle-label">
            <div class="toggle-wrap">
              <input
                type="checkbox"
                v-model="form.status"
                class="toggle-input"
              />
              <div class="toggle-track"></div>
            </div>
            <span>Active</span>
          </label>
          <label class="toggle-label">
            <div class="toggle-wrap">
              <input
                type="checkbox"
                v-model="form.featured"
                class="toggle-input"
              />
              <div class="toggle-track"></div>
            </div>
            <span>Featured</span>
          </label>
        </div>

        <div class="form-footer">
          <button type="button" @click="closeModal" class="btn-cancel">
            Cancel
          </button>
          <button type="submit" :disabled="submitting" class="btn-submit">
            <i v-if="submitting" class="fas fa-spinner fa-spin"></i>
            {{ editingProduct ? "Update" : "Create" }} Product
          </button>
        </div>
      </form>
    </Modal>

    <!-- Delete Modal -->
    <Modal
      :show="showDeleteModal"
      title="Delete Product"
      size="sm"
      @close="showDeleteModal = false"
    >
      <div class="confirm-modal">
        <div class="confirm-icon confirm-danger">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <p class="confirm-text">
          Are you sure you want to delete
          <strong>{{ deletingProduct?.name }}</strong
          >? This action cannot be undone.
        </p>
        <div class="confirm-btns">
          <button @click="showDeleteModal = false" class="btn-cancel">
            Cancel
          </button>
          <button
            @click="deleteProduct"
            :disabled="deleting"
            class="btn-danger"
          >
            <i v-if="deleting" class="fas fa-spinner fa-spin"></i> Delete
          </button>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Modal from "./common/Modal.vue";
import Pagination from "./common/Pagination.vue";
import { apiGet, apiPost, apiPut, apiDelete } from "../api.js";

const products = ref([]);
const categoriesList = ref([]);
const pagination = ref({});
const loading = ref(false);
const submitting = ref(false);
const deleting = ref(false);
const search = ref("");
const categoryFilter = ref("");
const statusFilter = ref("");
const showModal = ref(false);
const showDeleteModal = ref(false);
const editingProduct = ref(null);
const deletingProduct = ref(null);
const form = ref({
  name: "",
  sku: "",
  category_id: "",
  price: "",
  compare_price: "",
  stock_quantity: "",
  short_description: "",
  description: "",
  status: true,
  featured: false,
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
    const data = await apiGet("/admin/categories/list");
    categoriesList.value = data;
  } catch (e) {
    console.error(e);
  }
}

async function fetchProducts(page = 1) {
  loading.value = true;
  try {
    const params = new URLSearchParams({
      page,
      search: search.value,
      category_id: categoryFilter.value,
      status: statusFilter.value,
    });
    const data = await apiGet(`/admin/products?${params}`);
    products.value = data.data;
    pagination.value = {
      current_page: data.current_page,
      last_page: data.last_page,
      per_page: data.per_page,
      total: data.total,
      from: data.from,
      to: data.to,
    };
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
}

function openCreateModal() {
  editingProduct.value = null;
  form.value = {
    name: "",
    sku: "",
    category_id: "",
    price: "",
    compare_price: "",
    stock_quantity: "",
    short_description: "",
    description: "",
    status: true,
    featured: false,
  };
  imageFile.value = null;
  imagePreview.value = null;
  imagesFiles.value = [];
  imagesPreview.value = [];
  errors.value = {};
  showModal.value = true;
}

function openEditModal(product) {
  editingProduct.value = product;
  form.value = {
    name: product.name,
    sku: product.sku || "",
    category_id: product.category_id,
    price: product.price,
    compare_price: product.compare_price || "",
    stock_quantity: product.stock_quantity,
    short_description: product.short_description || "",
    description: product.description || "",
    status: product.status,
    featured: product.featured,
  };
  imageFile.value = null;
  imagePreview.value = null;
  imagesFiles.value = [];
  imagesPreview.value = [];
  errors.value = {};
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
  editingProduct.value = null;
}
function handleImageUpload(e) {
  const f = e.target.files[0];
  if (f) {
    imageFile.value = f;
    imagePreview.value = URL.createObjectURL(f);
  }
}
function handleImageDrop(e) {
  const f = e.dataTransfer.files[0];
  if (f && f.type.startsWith('image/')) {
    imageFile.value = f;
    imagePreview.value = URL.createObjectURL(f);
  }
}
function handleImagesUpload(e) {
  imagesFiles.value = Array.from(e.target.files);
  imagesPreview.value = imagesFiles.value.map((f) => URL.createObjectURL(f));
}
function handleImagesDrop(e) {
  const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
  imagesFiles.value = files;
  imagesPreview.value = files.map(f => URL.createObjectURL(f));
}

async function submitForm() {
  submitting.value = true;
  errors.value = {};
  try {
    const fd = new FormData();
    Object.entries(form.value).forEach(([k, v]) => {
      if (k === "status" || k === "featured") fd.append(k, v ? "1" : "0");
      else fd.append(k, v);
    });
    if (imageFile.value) fd.append("image", imageFile.value);
    imagesFiles.value.forEach((img) => fd.append("images[]", img));
    // PHP doesn't parse multipart on PUT, so always POST and spoof method for updates
    if (editingProduct.value) fd.append("_method", "PUT");
    const url = editingProduct.value
      ? `/admin/products/${editingProduct.value.id}`
      : "/admin/products";
    const res = await apiPost(url, fd);
    const data = await res.json();
    if (!res.ok) {
      if (data.errors) errors.value = data.errors;
      throw new Error(data.message);
    }
    closeModal();
    await fetchProducts();
    showToast(editingProduct.value ? "Product updated" : "Product created");
  } catch (e) {
    console.error(e);
  } finally {
    submitting.value = false;
  }
}

async function toggleStatus(product) {
  try {
    const res = await apiPut(`/admin/products/${product.id}`, {
      name: product.name,
      price: product.price,
      stock_quantity: product.stock_quantity,
      category_id: product.category_id,
      status: !product.status,
    });
    if (res.ok) {
      product.status = !product.status;
      showToast("Status updated");
    }
  } catch (e) {
    console.error(e);
  }
}

function confirmDelete(product) {
  deletingProduct.value = product;
  showDeleteModal.value = true;
}

async function deleteProduct() {
  deleting.value = true;
  try {
    const res = await apiDelete(`/admin/products/${deletingProduct.value.id}`);
    if (!res.ok) throw new Error();
    showDeleteModal.value = false;
    await fetchProducts();
    showToast("Product deleted");
  } catch (e) {
    console.error(e);
  } finally {
    deleting.value = false;
  }
}

function formatCurrency(v) {
  return new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: "USD",
  }).format(v);
}
function showToast(msg) {
  const t = document.createElement("div");
  t.className = "admin-toast";
  t.innerHTML = `<i class="fas fa-check-circle"></i> ${msg}`;
  document.body.appendChild(t);
  setTimeout(() => {
    t.style.opacity = "0";
    setTimeout(() => t.remove(), 300);
  }, 3000);
}

onMounted(() => {
  fetchProducts();
  fetchCategoriesList();
});
</script>

<style scoped>
.page {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.toolbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  flex-wrap: wrap;
}
.toolbar-left {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.search-box {
  position: relative;
}
.search-icon {
  position: absolute;
  left: 0.85rem;
  top: 50%;
  transform: translateY(-50%);
  color: #4a5068;
  font-size: 0.8rem;
  pointer-events: none;
}
.search-box input {
  background: #1a1e2e;
  border: 1px solid #2a2f45;
  border-radius: 10px;
  padding: 0.6rem 1rem 0.6rem 2.4rem;
  color: #e2e8f0;
  font-size: 0.875rem;
  width: 220px;
  outline: none;
  transition: border-color 0.2s;
}
.search-box input:focus {
  border-color: #c8773a;
}
.search-box input::placeholder {
  color: #3a4060;
}

.filter-select {
  background: #1a1e2e;
  border: 1px solid #2a2f45;
  border-radius: 10px;
  padding: 0.6rem 1rem;
  color: #e2e8f0;
  font-size: 0.875rem;
  outline: none;
  cursor: pointer;
}
.filter-select:focus {
  border-color: #c8773a;
}

.btn-primary {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.6rem 1.25rem;
  background: linear-gradient(135deg, #c8773a, #e8945a);
  color: white;
  border: none;
  border-radius: 10px;
  font-size: 0.875rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  white-space: nowrap;
}
.btn-primary:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 16px rgba(200, 119, 58, 0.35);
}

.table-card {
  background: #13151e;
  border: 1px solid #1f2330;
  border-radius: 16px;
  overflow: hidden;
}
.table-wrap {
  overflow-x: auto;
}
table {
  width: 100%;
  border-collapse: collapse;
}
thead tr {
  border-bottom: 1px solid #1f2330;
}
th {
  text-align: left;
  font-size: 0.72rem;
  font-weight: 600;
  color: #4a5068;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  padding: 0.9rem 1.25rem;
  white-space: nowrap;
}
.th-right {
  text-align: right;
}
tbody tr {
  border-bottom: 1px solid #1a1e2e;
  transition: background 0.15s;
}
tbody tr:last-child {
  border-bottom: none;
}
tbody tr:hover {
  background: #1a1e2e;
}
td {
  padding: 1rem 1.25rem;
  font-size: 0.875rem;
  color: #cbd5e1;
  vertical-align: middle;
}
.td-right {
  text-align: right;
}
.td-center {
  text-align: center;
  padding: 3rem 1rem;
}
.td-empty {
  color: #3a4060;
}
.empty-icon {
  font-size: 2rem;
  display: block;
  margin-bottom: 0.75rem;
}
.spinner {
  font-size: 1.5rem;
  color: #c8773a;
}

.product-info {
  display: flex;
  align-items: center;
  gap: 0.85rem;
}
.product-thumb {
  width: 48px;
  height: 48px;
  border-radius: 10px;
  background: #1e2235;
  overflow: hidden;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #3a4060;
  font-size: 1.1rem;
}
.product-thumb img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.product-name {
  font-weight: 600;
  color: #e2e8f0;
  margin-bottom: 0.15rem;
}
.product-sku {
  font-size: 0.72rem;
  color: #4a5068;
}
.featured-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  font-size: 0.68rem;
  color: #fbbf24;
  margin-top: 0.15rem;
}
.category-name {
  color: #94a3b8;
  font-size: 0.82rem;
}
.price-main {
  font-weight: 600;
  color: #e2e8f0;
}
.price-compare {
  font-size: 0.75rem;
  color: #4a5068;
  text-decoration: line-through;
  margin-top: 0.1rem;
}
.stock-num {
  font-weight: 600;
}
.stock-low {
  color: #f87171;
}
.stock-label {
  font-size: 0.68rem;
  color: #f87171;
  margin-top: 0.1rem;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.3rem 0.8rem;
  border-radius: 50px;
  font-size: 0.75rem;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
}
.status-active {
  background: rgba(16, 185, 129, 0.12);
  color: #34d399;
}
.status-active .status-dot {
  background: #34d399;
}
.status-inactive {
  background: rgba(100, 116, 139, 0.12);
  color: #64748b;
}
.status-inactive .status-dot {
  background: #64748b;
}
.status-dot {
  width: 7px;
  height: 7px;
  border-radius: 50%;
}

.action-btns {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 0.4rem;
}
.action-btn {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  border: 1px solid #2a2f45;
  background: #1e2235;
  color: #6b7280;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.78rem;
  transition: all 0.18s;
}
.action-edit:hover {
  border-color: #6366f1;
  color: #818cf8;
  background: rgba(99, 102, 241, 0.1);
}
.action-delete:hover {
  border-color: #ef4444;
  color: #f87171;
  background: rgba(239, 68, 68, 0.1);
}

/* Modal */
.modal-form {
  display: flex;
  flex-direction: column;
  gap: 1.1rem;
}
.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}
.form-row-3 {
  grid-template-columns: 1fr 1fr 1fr;
}
.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
}
.form-group label {
  font-size: 0.82rem;
  font-weight: 500;
  color: #94a3b8;
}
.required {
  color: #f87171;
}
.form-group input,
.form-group select,
.form-group textarea {
  background: #1e2235;
  border: 1px solid #2a2f45;
  border-radius: 10px;
  padding: 0.65rem 1rem;
  color: #e2e8f0;
  font-size: 0.875rem;
  outline: none;
  transition: border-color 0.2s;
  font-family: inherit;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #c8773a;
  box-shadow: 0 0 0 3px rgba(200, 119, 58, 0.1);
}
.form-group textarea {
  resize: none;
}
.form-error {
  font-size: 0.75rem;
  color: #f87171;
}
.file-input {
  color: #6b7280;
}
.img-preview img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 10px;
  border: 1px solid #2a2f45;
  margin-top: 0.5rem;
}
.imgs-preview {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
  margin-top: 0.5rem;
}
.imgs-preview img {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 8px;
  border: 1px solid #2a2f45;
}

.toggle-row {
  display: flex;
  gap: 2rem;
}
.toggle-label {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  cursor: pointer;
  font-size: 0.875rem;
  color: #94a3b8;
}
.toggle-wrap {
  position: relative;
}
.toggle-input {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}
.toggle-track {
  width: 42px;
  height: 24px;
  background: #2a2f45;
  border-radius: 50px;
  transition: background 0.2s;
  cursor: pointer;
}
.toggle-track::after {
  content: "";
  position: absolute;
  top: 3px;
  left: 3px;
  width: 18px;
  height: 18px;
  background: white;
  border-radius: 50%;
  transition: transform 0.2s;
}
.toggle-input:checked + .toggle-track {
  background: #c8773a;
}
.toggle-input:checked + .toggle-track::after {
  transform: translateX(18px);
}

.form-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  padding-top: 1rem;
  border-top: 1px solid #1f2330;
  margin-top: 0.25rem;
}
.btn-cancel {
  padding: 0.6rem 1.2rem;
  background: transparent;
  border: 1px solid #2a2f45;
  border-radius: 10px;
  color: #6b7280;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.18s;
}
.btn-cancel:hover {
  border-color: #4a5068;
  color: #cbd5e1;
}
.btn-submit {
  padding: 0.6rem 1.4rem;
  background: linear-gradient(135deg, #c8773a, #e8945a);
  border: none;
  border-radius: 10px;
  color: white;
  font-size: 0.875rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.btn-submit:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 4px 16px rgba(200, 119, 58, 0.3);
}
.btn-submit:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.confirm-modal {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  gap: 1rem;
}
.confirm-icon {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.4rem;
}
.confirm-danger {
  background: rgba(239, 68, 68, 0.12);
  color: #f87171;
}
.confirm-text {
  font-size: 0.875rem;
  color: #94a3b8;
  line-height: 1.6;
}
.confirm-text strong {
  color: #e2e8f0;
}
.confirm-btns {
  display: flex;
  gap: 0.75rem;
}
.btn-danger {
  padding: 0.6rem 1.4rem;
  background: #ef4444;
  border: none;
  border-radius: 10px;
  color: white;
  font-size: 0.875rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.18s;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.btn-danger:hover:not(:disabled) {
  background: #dc2626;
}
.btn-danger:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Upload Zone */
.upload-zone {
  border: 2px dashed #2a2f45;
  border-radius: 12px;
  cursor: pointer;
  transition: border-color 0.2s, background 0.2s;
  overflow: hidden;
  min-height: 110px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.upload-zone:hover {
  border-color: #c8773a;
  background: rgba(200,119,58,0.04);
}
.upload-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.4rem;
  padding: 1.5rem;
  text-align: center;
  pointer-events: none;
}
.upload-placeholder i {
  font-size: 1.8rem;
  color: #3a4060;
}
.upload-placeholder p {
  font-size: 0.82rem;
  color: #6b7280;
}
.upload-placeholder span {
  font-size: 0.72rem;
  color: #3a4060;
}
.upload-preview {
  position: relative;
  width: 100%;
  height: 110px;
}
.upload-preview img {
  width: 100%;
  height: 110px;
  object-fit: cover;
  display: block;
}
.upload-overlay {
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.45);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.4rem;
  color: white;
  font-size: 0.82rem;
  font-weight: 600;
  opacity: 0;
  transition: opacity 0.2s;
}
.upload-zone:hover .upload-overlay { opacity: 1; }
.upload-zone.multi { min-height: 80px; }
.imgs-preview {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
  padding: 0.75rem;
  width: 100%;
}
.imgs-preview img {
  width: 64px;
  height: 64px;
  object-fit: cover;
  border-radius: 8px;
  border: 1px solid #2a2f45;
}
.add-more {
  width: 64px;
  height: 64px;
  border-radius: 8px;
  border: 2px dashed #2a2f45;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #3a4060;
  font-size: 1rem;
}
</style>
