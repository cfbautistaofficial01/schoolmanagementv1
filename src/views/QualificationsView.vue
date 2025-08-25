<template>
  <div>
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6">
      <div>
        <h1 class="text-2xl font-semibold text-gray-800">Manage Qualifications</h1>
        <p class="text-sm text-gray-500">Add, edit, and archive TESDA qualifications.</p>
      </div>
      <button @click="openAddModal" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-sm">
        <Plus class="w-5 h-5 mr-2" /> Add Qualification
      </button>
    </div>

    <!-- Filters -->
    <div class="mb-4 bg-white p-4 rounded-xl border border-gray-200">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="relative md:col-span-2">
          <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input v-model="filters.search" type="text" placeholder="Search by name, level, or category..." class="w-full pl-9 pr-3 py-2 border-gray-300 rounded-lg">
        </div>
        <div>
          <select v-model="filters.status" class="w-full border-gray-300 rounded-lg">
            <option value="">All Statuses</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-2xl shadow-sm border border-emerald-50">
      <!-- Loading Skeleton -->
      <div v-if="isLoading" class="p-6">
        <div v-for="i in 5" :key="i" class="h-12 bg-gray-100 rounded-md mb-3 animate-pulse"></div>
      </div>

      <!-- Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3">Qualification</th>
              <th scope="col" class="px-6 py-3">Category</th>
              <th scope="col" class="px-6 py-3">Regulation</th>
              <th scope="col" class="px-6 py-3">Status</th>
              <th scope="col" class="px-6 py-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="q in qualifications" :key="q.id" class="bg-white border-b hover:bg-gray-50">
              <td class="px-6 py-4 font-medium text-gray-900">
                {{ q.qualification_name }}
                <div class="text-xs text-gray-500">{{ q.level }}</div>
              </td>
              <td class="px-6 py-4">{{ q.category }}</td>
              <td class="px-6 py-4">{{ q.regulation_type }}</td>
              <td class="px-6 py-4">
                <span :class="q.is_active == 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'" class="px-2 py-1 text-xs font-medium rounded-full">
                  {{ q.is_active == 1 ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <button @click="openEditModal(q)" class="font-medium text-emerald-600 hover:underline mr-4">Edit</button>
                <button @click="openArchiveModal(q)" class="font-medium text-red-600 hover:underline">Archive</button>
              </td>
            </tr>
            <tr v-if="!qualifications.length">
              <td colspan="5" class="text-center py-12">
                <div class="flex flex-col items-center">
                  <FileX2 class="w-12 h-12 text-gray-300 mb-2" />
                  <h3 class="text-lg font-medium text-gray-600">No Qualifications Found</h3>
                  <p class="text-sm text-gray-400">Try adjusting your search or filters.</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div v-if="!isLoading && pagination.totalPages > 1" class="p-4 border-t">
        <Pagination :current-page="pagination.currentPage" :total-pages="pagination.totalPages" @page-changed="handlePageChange" />
      </div>
    </div>

    <!-- Modals and Toast -->
    <Modal :show="isFormModalOpen" @close="closeFormModal" :title="modalTitle" subtitle="Fill in the details for the TESDA qualification.">
      <AddQualificationForm :key="formKey" :qualification-data="selectedQualification" @close="closeFormModal" @qualification-added="handleFormSuccess" />
    </Modal>
    <ConfirmationModal :show="isArchiveModalOpen" @close="isArchiveModalOpen = false" @confirm="confirmArchive" message="Are you sure you want to archive this qualification?" />
    <Toast :show="toast.show" :message="toast.message" :type="toast.type" />
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import axios from 'axios';
import { Plus, Search, FileX2 } from 'lucide-vue-next';
import Modal from '@/components/Modal.vue';
import AddQualificationForm from '@/components/AddQualificationForm.vue';
import ConfirmationModal from '@/components/ConfirmationModal.vue';
import Toast from '@/components/Toast.vue';
import Pagination from '@/components/Pagination.vue';

const qualifications = ref([]);
const isLoading = ref(true);
const isFormModalOpen = ref(false);
const isArchiveModalOpen = ref(false);
const selectedQualification = ref(null);
const formKey = ref(0);

const filters = ref({ search: '', status: '' });
const pagination = ref({ currentPage: 1, totalPages: 1 });
const toast = ref({ show: false, message: '', type: 'success' });

const modalTitle = computed(() => selectedQualification.value ? 'Edit Qualification' : 'Add New Qualification');

const fetchQualifications = async (page = 1) => {
  isLoading.value = true;
  try {
    const params = {
      page,
      perPage: 10,
      search: filters.value.search,
      status: filters.value.status,
    };
    const response = await axios.get('/api/qualifications', { params });
    qualifications.value = response.data.data;
    pagination.value = {
      currentPage: response.data.pagination.currentPage,
      totalPages: response.data.pagination.lastPage,
    };
  } catch (error) {
    console.error("Failed to fetch qualifications:", error);
  } finally {
    isLoading.value = false;
  }
};

watch(filters, () => fetchQualifications(1), { deep: true });

const handlePageChange = (page) => {
  fetchQualifications(page);
};

const showToast = (message, type = 'success') => {
  toast.value = { show: true, message, type };
  setTimeout(() => toast.value.show = false, 3000);
};

const openAddModal = () => {
  selectedQualification.value = null;
  formKey.value++;
  isFormModalOpen.value = true;
};

const openEditModal = (qualification) => {
  selectedQualification.value = { ...qualification };
  formKey.value++;
  isFormModalOpen.value = true;
};

const openArchiveModal = (qualification) => {
  selectedQualification.value = qualification;
  isArchiveModalOpen.value = true;
};

const closeFormModal = () => {
  isFormModalOpen.value = false;
  selectedQualification.value = null;
};

const handleFormSuccess = async () => {
  await fetchQualifications(pagination.value.currentPage);
  showToast('Qualification saved successfully!');
};

const confirmArchive = async () => {
  if (!selectedQualification.value) return;
  try {
    await axios.delete(`/api/qualifications/${selectedQualification.value.id}`);
    await fetchQualifications(pagination.value.currentPage);
    showToast('Qualification archived successfully!');
  } catch (error) {
    showToast('Failed to archive qualification.', 'error');
  } finally {
    isArchiveModalOpen.value = false;
    selectedQualification.value = null;
  }
};

onMounted(() => fetchQualifications());
</script>
