    <template>
      <div v-if="totalPages > 1" class="flex items-center justify-between text-sm">
        <button
          @click="changePage(currentPage - 1)"
          :disabled="currentPage === 1"
          class="px-3 py-1 border border-gray-300 rounded-md disabled:opacity-50"
        >
          Previous
        </button>
        <span class="text-gray-600">
          Page {{ currentPage }} of {{ totalPages }}
        </span>
        <button
          @click="changePage(currentPage + 1)"
          :disabled="currentPage === totalPages"
          class="px-3 py-1 border border-gray-300 rounded-md disabled:opacity-50"
        >
          Next
        </button>
      </div>
    </template>

    <script setup>
    defineProps({
      currentPage: { type: Number, required: true },
      totalPages: { type: Number, required: true },
    });

    const emit = defineEmits(['page-changed']);

    const changePage = (page) => {
      emit('page-changed', page);
    };
    </script>