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
            placeholder="Search categories..."
            @input="debouncedSearch"
          />
        </div>
        <select
          v-model="statusFilter"
          @change="fetchCategories"
          class="filter-select"
        >
          <option value="">All Status</option>
          <option value="1">Active</option>
          <option value="0">Inactive</option>
        </select>
      </div>
      <button @click="openCreateModal" class="btn-primary">
        <i class="fas fa-plus"></i> Add Category
      </button>
    </div>

    <!-- Table Card -->
    <div class="table-card">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Category</th>
              <th>Slug</th>
              <th>Products</th>
              <th>Status</th>
              <th class="th-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="5" class="td-center">
                <i class="fas fa-spinner fa-spin spinner"></i>
              </td>
            </tr>
            <tr v-else-if="!categories.length">
              <td colspan="5" class="td-center td-empty">
                <i class="fas fa-folder-open empty-icon"></i>
                <p>No categories found</p>
              </td>
            </tr>
            <tr v-for="category in categories" :key="category.id" v-else>
              <td>
                <div class="cat-info">
                  <div class="cat-thumb">
                    <img
                      v-if="category.image"
                      :src="'http://localhost:8000/storage/' + category.image"
                      :alt="category.name"
                    />
                    <i v-else class="fas fa-image"></i>
                  </div>
                  <div>
                    <p class="cat-name">{{ category.name }}</p>
                    <p class="cat-desc">
                      {{ category.description || "No description" }}
                    </p>
                  </div>
                </div>
              </td>
              <td>
                <code class="slug-code">{{ category.slug }}</code>
              </td>
              <td>
                <span class="products-count">{{
                  category.products_count || 0
                }}</span>
              </td>
              <td>
                <button
                  @click="toggleStatus(category)"
                  class="status-badge"
                  :class="category.status ? 'status-active' : 'status-inactive'"
                >
                  <span class="status-dot"></span>
                  {{ category.status ? "Active" : "Inactive" }}
                </button>
              </td>
              <td class="td-right">
                <div class="action-btns">
                  <button
                    @click="openEditModal(category)"
                    class="action-btn action-edit"
                    title="Edit"
                  >
                    <i class="fas fa-pen"></i>
                  </button>
                  <button
                    @click="confirmDelete(category)"
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

    <Pagination :pagination="pagination" @change="fetchCategories" />

    <!-- Create / Edit Modal -->
    <Modal
      :show="showModal"
      :title="editingCategory ? 'Edit Category' : 'Create Category'"
      @close="closeModal"
    >
      <form @submit.prevent="submitForm" class="modal-form">
        <div class="form-group">
          <label>Name <span class="required">*</span></label>
          <input
            v-model="form.name"
            type="text"
            required
            placeholder="Category name"
          />
          <p v-if="errors.name" class="form-error">{{ errors.name[0] }}</p>
        </div>
        <div class="form-group">
          <label>Slug</label>
          <input
            v-model="form.slug"
            type="text"
            placeholder="auto-generated-from-name"
          />
          <p class="form-hint">Auto-generated from name if left empty</p>
        </div>
        <div class="form-group">
          <label>Description</label>
          <textarea
            v-model="form.description"
            rows="3"
            placeholder="Category description"
          ></textarea>
        </div>
        <div class="form-group">
          <label>Image</label>
          <input
            type="file"
            accept="image/*"
            @change="handleImageUpload"
            class="file-input"
          />
          <div
            v-if="imagePreview || editingCategory?.image"
            class="img-preview"
          >
            <img
              :src="
                imagePreview ||
                'http://localhost:8000/storage/' + editingCategory?.image
              "
            />
          </div>
        </div>
        <div class="form-group toggle-group">
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
        </div>
        <div class="form-footer">
          <button type="button" @click="closeModal" class="btn-cancel">
            Cancel
          </button>
          <button type="submit" :disabled="submitting" class="btn-submit">
            <i v-if="submitting" class="fas fa-spinner fa-spin"></i>
            {{ editingCategory ? "Update" : "Create" }} Category
          </button>
        </div>
      </form>
    </Modal>

    <!-- Delete Modal -->
    <Modal
      :show="showDeleteModal"
      title="Delete Category"
      size="sm"
      @close="showDeleteModal = false"
    >
      <div class="confirm-modal">
        <div class="confirm-icon confirm-danger">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <p class="confirm-text">
          Are you sure you want to delete
          <strong>{{ deletingCategory?.name }}</strong
          >? This will also delete all associated products.
        </p>
        <div class="confirm-btns">
          <button @click="showDeleteModal = false" class="btn-cancel">
            Cancel
          </button>
          <button
            @click="deleteCategory"
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

const categories = ref([]);
const pagination = ref({});
const loading = ref(false);
const submitting = ref(false);
const deleting = ref(false);
const search = ref("");
const statusFilter = ref("");
const page = ref(1);
const showModal = ref(false);
const showDeleteModal = ref(false);
const editingCategory = ref(null);
const deletingCategory = ref(null);
const form = ref({ name: "", slug: "", description: "", status: true });
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
    const data = await apiGet(`/admin/categories?${params}`);
    categories.value = data.data;
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
  editingCategory.value = null;
  form.value = { name: "", slug: "", description: "", status: true };
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
    description: category.description || "",
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

function handleImageUpload(e) {
  const file = e.target.files[0];
  if (file) {
    imageFile.value = file;
    imagePreview.value = URL.createObjectURL(file);
  }
}

async function submitForm() {
  submitting.value = true;
  errors.value = {};
  try {
    const fd = new FormData();
    fd.append("name", form.value.name);
    fd.append("slug", form.value.slug);
    fd.append("description", form.value.description);
    fd.append("status", form.value.status ? "1" : "0");
    if (imageFile.value) fd.append("image", imageFile.value);
    const url = editingCategory.value
      ? `/admin/categories/${editingCategory.value.id}`
      : "/admin/categories";
    const res = editingCategory.value
      ? await apiPut(url, fd)
      : await apiPost(url, fd);
    const data = await res.json();
    if (!res.ok) {
      if (data.errors) errors.value = data.errors;
      throw new Error(data.message);
    }
    closeModal();
    await fetchCategories();
    showToast(editingCategory.value ? "Category updated" : "Category created");
  } catch (e) {
    console.error(e);
  } finally {
    submitting.value = false;
  }
}

async function toggleStatus(category) {
  try {
    const res = await apiPut(`/admin/categories/${category.id}`, {
      name: category.name,
      status: !category.status,
    });
    if (res.ok) {
      category.status = !category.status;
      showToast("Status updated");
    }
  } catch (e) {
    console.error(e);
  }
}

function confirmDelete(category) {
  deletingCategory.value = category;
  showDeleteModal.value = true;
}

async function deleteCategory() {
  deleting.value = true;
  try {
    const res = await apiDelete(
      `/admin/categories/${deletingCategory.value.id}`,
    );
    if (!res.ok) throw new Error();
    showDeleteModal.value = false;
    await fetchCategories();
    showToast("Category deleted");
  } catch (e) {
    console.error(e);
  } finally {
    deleting.value = false;
  }
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

onMounted(() => fetchCategories());
</script>

<style scoped>
.page {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

/* Toolbar */
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
  color: #ccced7;
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
  width: 240px;
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

/* Table */
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

/* Cat info */
.cat-info {
  display: flex;
  align-items: center;
  gap: 0.85rem;
}
.cat-thumb {
  width: 44px;
  height: 44px;
  border-radius: 10px;
  background: #1e2235;
  overflow: hidden;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #3a4060;
  font-size: 1rem;
}
.cat-thumb img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.cat-name {
  font-weight: 600;
  color: #e2e8f0;
  margin-bottom: 0.15rem;
}
.cat-desc {
  font-size: 0.75rem;
  color: #4a5068;
  max-width: 200px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.slug-code {
  background: #1e2235;
  color: #6b7280;
  font-size: 0.75rem;
  padding: 0.2rem 0.6rem;
  border-radius: 6px;
  font-family: monospace;
}
.products-count {
  color: #94a3b8;
}

/* Status badge */
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

/* Actions */
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

/* Modal form */
.modal-form {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
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
.form-hint {
  font-size: 0.72rem;
  color: #3a4060;
}

.file-input {
  color: #6b7280;
}
.img-preview {
  margin-top: 0.5rem;
}
.img-preview img {
  width: 72px;
  height: 72px;
  object-fit: cover;
  border-radius: 10px;
  border: 1px solid #2a2f45;
}

/* Toggle */
.toggle-group {
  flex-direction: row;
  align-items: center;
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

/* Form footer */
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

/* Confirm modal */
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
</style>

<style>
.admin-toast {
  position: fixed;
  bottom: 1.5rem;
  right: 1.5rem;
  background: #10b981;
  color: white;
  padding: 0.75rem 1.25rem;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
  display: flex;
  align-items: center;
  gap: 0.6rem;
  font-size: 0.875rem;
  font-weight: 500;
  z-index: 9999;
  transition: opacity 0.3s;
}
</style>
