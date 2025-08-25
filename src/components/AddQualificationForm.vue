<template>
  <form @submit.prevent="submitForm">
    <div class="space-y-4">

      <!-- Qualification Name -->
      <div>
        <label for="qualification_name" class="block text-sm font-medium text-gray-700">Qualification Name</label>
        <input v-model="form.qualification_name" type="text" id="qualification_name" placeholder="e.g., Web Development" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" required>
      </div>

      <!-- Category with AI Suggestion -->
      <div class="grid grid-cols-3 gap-4">
        <div class="col-span-2">
          <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
          <input v-model="form.category" type="text" id="category" placeholder="e.g., ICT" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-transparent">Suggest</label>
          <button @click="getCategorySuggestion" type="button" :disabled="isSuggesting || !form.qualification_name" class="mt-1 w-full inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium text-emerald-700 bg-white border border-emerald-200 hover:bg-emerald-50 disabled:opacity-50 disabled:cursor-not-allowed">
            <Sparkles v-if="!isSuggesting" class="w-4 h-4 mr-2" />
            <Loader2 v-else class="w-4 h-4 mr-2 animate-spin" />
            Suggest
          </button>
        </div>
      </div>

      <!-- Regulation Type & Level -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label for="regulation_type" class="block text-sm font-medium text-gray-700">Regulation Type</label>
          <select v-model="form.regulation_type" id="regulation_type" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            <option value="TR">TR (Training Regulations)</option>
            <option value="CS">CS (Competency Standards)</option>
          </select>
        </div>
        <div>
          <label for="level" class="block text-sm font-medium text-gray-700">Level</label>
          <select v-model="form.level" id="level" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            <option disabled value="">Select a level</option>
            <option v-for="level in availableLevels" :key="level" :value="level">{{ level }}</option>
          </select>
        </div>
      </div>

      <!-- Description -->
      <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea v-model="form.description" id="description" rows="3" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
      </div>

    </div>
    <div v-if="errorMessage" class="mt-4 text-sm text-red-600">{{ errorMessage }}</div>
    <div class="mt-6 flex justify-end gap-3">
      <button type="button" @click="$emit('close')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
      <button type="submit" :disabled="isSaving" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-emerald-600 border border-transparent rounded-lg hover:bg-emerald-700 disabled:opacity-50">
        <span v-if="isSaving"><Loader2 class="w-4 h-4 mr-2 animate-spin" />Saving...</span>
        <span v-else>Save Qualification</span>
      </button>
    </div>
  </form>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import axios from 'axios';
import { Loader2, Sparkles } from 'lucide-vue-next';

const props = defineProps({
  qualificationData: {
    type: Object,
    default: null
  }
});

const emit = defineEmits(['close', 'qualification-added']);

const form = ref({});
const errorMessage = ref('');
const isSaving = ref(false);
const isSuggesting = ref(false);

const trLevels = ['NC I', 'NC II', 'NC III', 'NC IV'];
const csLevels = ['Level I', 'Level II', 'Level III', 'Level IV', 'Level V'];

const availableLevels = computed(() => {
  return form.value.regulation_type === 'TR' ? trLevels : csLevels;
});

watch(() => form.value.regulation_type, (newVal, oldVal) => {
  if (newVal !== oldVal) {
    form.value.level = '';
  }
});

const getCategorySuggestion = async () => {
  if (!form.value.qualification_name) return;
  isSuggesting.value = true;
  try {
    const response = await axios.get(`/api/qualifications/suggest-category?name=${encodeURIComponent(form.value.qualification_name)}`);
    form.value.category = response.data.category;
  } catch (error) {
    console.error("Failed to get category suggestion:", error);
  } finally {
    isSuggesting.value = false;
  }
};

const resetForm = () => {
  form.value = {
    qualification_name: props.qualificationData?.qualification_name || '',
    level: props.qualificationData?.level || '',
    category: props.qualificationData?.category || '',
    regulation_type: props.qualificationData?.regulation_type || 'TR',
    description: props.qualificationData?.description || '',
  };
};

const submitForm = async () => {
  errorMessage.value = '';
  isSaving.value = true;
  try {
    if (props.qualificationData?.id) {
      await axios.put(`/api/qualifications/${props.qualificationData.id}`, form.value);
    } else {
      await axios.post('/api/qualifications', form.value);
    }
    emit('qualification-added');
    emit('close');
  } catch (error) {
    errorMessage.value = 'Failed to save qualification. Please try again.';
    console.error(error);
  } finally {
    isSaving.value = false;
  }
};

onMounted(resetForm);
</script>