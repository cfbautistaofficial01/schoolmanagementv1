<template>
  <div class="flex min-h-0 flex-col">
    <!-- Compact hero -->
    <div class="mb-6 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-500 px-6 py-4 text-white shadow-sm">
      <div class="flex items-center justify-between gap-3">
        <div>
          <h1 class="text-2xl font-bold leading-tight">Welcome back, Admin!</h1>
          <p class="text-emerald-50/90 text-sm mt-0.5">Here’s a summary of your institution’s activity.</p>
        </div>
        <!-- quick timeframe control -->
        <div class="hidden sm:flex items-center gap-2">
          <label class="sr-only" for="range">Range</label>
          <select id="range"
                  class="rounded-lg bg-white/10 px-3 py-2 text-sm ring-1 ring-white/25 backdrop-blur hover:bg-white/15">
            <option>Last 30 days</option>
            <option>Last 90 days</option>
            <option>This year</option>
          </select>
          <button class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-3 py-2 text-sm ring-1 ring-white/25 backdrop-blur hover:bg-white/15">
            <Download class="h-4 w-4" /> Export
          </button>
        </div>
      </div>
    </div>

    <!-- Loading Skeletons -->
    <div v-if="isLoading" class="animate-pulse">
        <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 xl:gap-6">
            <div v-for="i in 4" :key="i" class="bg-white rounded-xl h-28 border border-gray-200"></div>
        </div>
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 min-h-0">
            <div class="xl:col-span-8 bg-white rounded-2xl h-96 border border-gray-200"></div>
            <div class="xl:col-span-4 bg-white rounded-2xl h-96 border border-gray-200"></div>
        </div>
    </div>

    <!-- KPIs and Main Grid -->
    <div v-else>
        <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 xl:gap-6">
            <div class="bg-white rounded-xl shadow-sm p-4 border border-emerald-50">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-[11px] uppercase tracking-wide text-gray-500">Total Trainees</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 leading-none">{{ stats.totalTrainees }}</p>
                    <span v-if="stats.traineesChange" class="mt-1 inline-flex items-center gap-1 text-xs text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                      ▲ {{ stats.traineesChange }}% <span class="text-gray-500">vs last month</span>
                    </span>
                  </div>
                  <div class="p-3 rounded-lg bg-emerald-50"><Users class="w-6 h-6 text-emerald-600" /></div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border border-emerald-50">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-[11px] uppercase tracking-wide text-gray-500">Active Qualifications</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 leading-none">{{ stats.activeQualifications }}</p>
                    <span class="mt-1 inline-flex items-center gap-1 text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">
                      ● steady
                    </span>
                  </div>
                  <div class="p-3 rounded-lg bg-emerald-50"><BookCopy class="w-6 h-6 text-emerald-600" /></div>
                </div>
            </div>
             <div class="bg-white rounded-xl shadow-sm p-4 border border-emerald-50">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-[11px] uppercase tracking-wide text-gray-500">Pending Payments</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 leading-none">{{ stats.pendingPayments }}</p>
                    <span class="mt-1 inline-flex items-center gap-1 text-xs text-amber-700 bg-amber-50 px-2 py-0.5 rounded">
                      needs attention
                    </span>
                  </div>
                  <div class="p-3 rounded-lg bg-emerald-50"><CreditCard class="w-6 h-6 text-emerald-600" /></div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-4 border border-emerald-50">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-[11px] uppercase tracking-wide text-gray-500">Completed Trainings</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 leading-none">{{ stats.completedTrainings }}</p>
                    <span v-if="stats.completedChange" class="mt-1 inline-flex items-center gap-1 text-xs text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded">
                      ▲ {{ stats.completedChange }}% <span class="text-gray-500">this month</span>
                    </span>
                  </div>
                  <div class="p-3 rounded-lg bg-emerald-50"><ClipboardCheck class="w-6 h-6 text-emerald-600" /></div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 min-h-0">
            <section class="xl:col-span-8 min-h-0 flex flex-col">
                <div class="bg-white rounded-2xl shadow-sm border border-emerald-50 flex-1 min-h-0 flex flex-col">
                    <div class="px-5 py-4"><h3 class="text-base font-semibold text-gray-800">Course Popularity</h3></div>
                    <div class="px-5 pb-5 flex-1 min-h-0">
                        <div class="h-[clamp(16rem,40vh,28rem)]">
                            <BarChart :chart-data="coursePopularityChartData" />
                        </div>
                    </div>
                </div>
            </section>
            <aside class="xl:col-span-4 min-h-0 flex flex-col gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-emerald-50 p-5">
                    <h3 class="text-base font-semibold text-gray-800 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 gap-3">
                        <button class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-sm">
                            <UserPlus class="w-5 h-5 mr-2" /> Register New Trainee
                        </button>
                        <button @click="isModalOpen = true" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-medium text-emerald-700 bg-white border border-emerald-200 hover:bg-emerald-50">
                          <BookPlus class="w-5 h-5 mr-2" /> Add Qualification
                        </button>
                    </div>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-emerald-50 flex-1 min-h-0 flex flex-col">
                    <div class="px-5 py-4"><h3 class="text-base font-semibold text-gray-800">Recent Activity</h3></div>
                    <ul class="px-5 pb-5 space-y-4 overflow-y-auto flex-1 min-h-0">
                        <li v-for="activity in recentActivity" :key="activity.id" class="flex items-center">
                            <div class="bg-gray-100 p-2 rounded-xl mr-3"><User class="w-5 h-5 text-gray-500" /></div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ activity.first_name }} {{ activity.last_name }} registered.</p>
                                <p class="text-xs text-gray-400">{{ activity.time_ago }}</p>
                            </div>
                        </li>
                        <li v-if="!recentActivity.length" class="text-center text-sm text-gray-400 py-4">No recent activity.</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
  </div>
  <Modal :show="isModalOpen" @close="isModalOpen = false" title="Add New Qualification" subtitle="Fill in the details for the new TESDA qualification.">
      <AddQualificationForm @close="isModalOpen = false" @qualification-added="handleQualificationAdded" />
  </Modal>

  <Toast :show="toast.show" :message="toast.message" :type="toast.type" />
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { Users, BookCopy, CreditCard, ClipboardCheck, UserPlus, BookPlus, User, Download, FileText } from 'lucide-vue-next';
import AddQualificationForm from '@/components/AddQualificationForm.vue';
import Modal from '@/components/Modal.vue'; // Import Modal
import BarChart from '@/components/BarChart.vue';
import Toast from '@/components/Toast.vue';

const isLoading = ref(true);
const stats = ref({
    totalTrainees: 0,
    traineesChange: 0,
    activeQualifications: 0,
    pendingPayments: 0,
    completedTrainings: 0,
    completedChange: 0,
});
const recentActivity = ref([]);
const coursePopularity = ref({ labels: [], data: [] });

// MODAL STATE
const isModalOpen = ref(false);

// TOAST STATE
const toast = ref({
  show: false,
  message: '',
  type: 'success',
});

// This computed property formats the data correctly for the BarChart component
const coursePopularityChartData = computed(() => ({
  labels: coursePopularity.value.labels,
  datasets: [{
    label: 'Enrolled Trainees',
    backgroundColor: '#4ade80',
    borderColor: '#22c55e',
    borderWidth: 1,
    borderRadius: 4,
    data: coursePopularity.value.data,
  }],
}));

const fetchDashboardData = async () => {
    try {
        // Corrected the API endpoint URL to fetch all data at once
        const response = await axios.get('/api/dashboard');
        
        // Populating all the new data refs from the single API response
        stats.value = response.data.stats;
        recentActivity.value = response.data.recentActivity;
        coursePopularity.value = response.data.coursePopularity;
    } catch (error) {
        console.error("Failed to fetch dashboard data:", error);
    } finally {
        isLoading.value = false;
    }
};

// This function will be called to show the toast
const showToast = (message, type = 'success') => {
  toast.value.message = message;
  toast.value.type = type;
  toast.value.show = true;
  setTimeout(() => {
    toast.value.show = false;
  }, 3000); // Hide after 3 seconds
};

const handleQualificationAdded = async () => {
  await fetchDashboardData(); // Wait for the data to refresh
  showToast('Qualification added successfully!'); // Then show the toast
};

onMounted(() => {
    fetchDashboardData();
});
</script>