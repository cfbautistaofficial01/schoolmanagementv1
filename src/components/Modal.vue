<template>
  <teleport to="body">
    <transition
      enter-active-class="transition ease-out duration-200 transform"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition ease-in duration-200 transform"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div v-show="show" class="fixed inset-0 z-50 bg-black/40 backdrop-blur-sm" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
          <transition
            enter-active-class="transition ease-out duration-300 transform"
            enter-from-class="opacity-0 translate-y-10 scale-95"
            enter-to-class="opacity-100 translate-y-0 scale-100"
            leave-active-class="transition ease-in duration-200 transform"
            leave-from-class="opacity-100 translate-y-0 scale-100"
            leave-to-class="opacity-0 translate-y-10 scale-95"
          >
            <div v-if="show" class="relative w-full max-w-lg bg-white rounded-2xl shadow-xl flex flex-col" @click.stop>
              <!-- Header -->
              <div class="flex items-start justify-between p-5 border-b border-gray-200 rounded-t-2xl">
                <div class="flex items-center gap-4">
                  <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center bg-emerald-100 rounded-full">
                    <!-- Slot for a custom icon -->
                    <slot name="icon">
                      <FilePlus2 class="w-6 h-6 text-emerald-600" />
                    </slot>
                  </div>
                  <div>
                    <h3 id="modal-title" class="text-lg font-semibold text-gray-900">{{ title }}</h3>
                    <p v-if="subtitle" class="text-sm text-gray-500 mt-1">{{ subtitle }}</p>
                  </div>
                </div>
                <button @click="$emit('close')" class="p-1 rounded-full hover:bg-gray-100" aria-label="Close modal">
                  <X class="w-5 h-5 text-gray-500" />
                </button>
              </div>
              
              <!-- Body -->
              <div class="p-6 flex-1">
                <slot></slot>
              </div>

            </div>
          </transition>
        </div>
      </div>
    </transition>
  </teleport>
</template>

<script setup>
import { X, FilePlus2 } from 'lucide-vue-next';
import { onMounted, onUnmounted } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  title: {
    type: String,
    required: true,
  },
  subtitle: {
    type: String,
    default: '',
  },
});

const emit = defineEmits(['close']);

// Close modal on escape key press
const handleEsc = (e) => {
  if (e.key === 'Escape' && props.show) {
    emit('close');
  }
};

onMounted(() => {
  document.addEventListener('keydown', handleEsc);
});

onUnmounted(() => {
  document.removeEventListener('keydown', handleEsc);
});
</script>