<!-- src/layouts/AdminLayout.vue -->
<template>
  <div class="flex h-screen bg-gray-50 font-sans">
    <!-- Mobile Menu Button -->
    <button
      @click="sidebarOpen = !sidebarOpen"
      class="md:hidden fixed top-4 left-4 z-40 p-2 bg-white rounded-lg shadow"
      :aria-expanded="sidebarOpen ? 'true' : 'false'"
      aria-controls="admin-sidebar"
    >
      <Menu class="w-6 h-6" />
    </button>

    <!-- Mobile overlay -->
    <div
      v-if="sidebarOpen"
      class="fixed inset-0 bg-black/30 z-30 md:hidden"
      @click="sidebarOpen = false"
    />

    <!-- Sidebar (solid white + gray borders) -->
    <aside
      id="admin-sidebar"
      :class="[
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
        isCollapsed ? 'w-20 md:w-20' : 'w-72 md:w-72'
      ]"
      class="bg-white border-r border-gray-200 text-gray-700
             flex-shrink-0 fixed inset-y-0 left-0 z-40 md:relative md:translate-x-0
             transition-all duration-300 ease-in-out flex flex-col shadow-sm"
    >
      <!-- Brand / Collapse -->
      <div class="p-4 border-b border-gray-200 flex items-center gap-3">
        <img src="/images/logo.png" alt="GESTAAC logo" class="w-10 h-10 rounded-md" />
        <div class="min-w-0" v-show="!isCollapsed">
          <h1 class="text-base font-semibold text-gray-900 truncate">GESTAAC Inc.</h1>
          <p class="text-[11px] text-gray-500 truncate">Admin Portal</p>
        </div>

      <button
          class="ml-auto hidden md:inline-flex p-2 rounded-lg hover:bg-gray-50"
          @click="toggleCollapse"
          :title="isCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
        >
          <ChevronsRight v-if="isCollapsed" class="w-5 h-5" />
          <ChevronsLeft v-else class="w-5 h-5" />
        </button>
      </div>

      <!-- Nav -->
      <nav class="mt-3 px-2 space-y-1 overflow-y-auto">
        <div class="px-2 py-2 text-[11px] font-semibold tracking-wide text-gray-500" v-show="!isCollapsed">
          Main
        </div>

        <!-- Use custom RouterLink to render <a> directly so flex works 100% -->
        <RouterLink
          v-for="item in nav"
          :key="item.to"
          :to="item.to"
          custom
          v-slot="{ isActive, href, navigate }"
        >
          <a
            :href="href"
            :title="isCollapsed ? item.label : undefined"
            @click="navigate(); closeSidebarOnMobile()"
            :class="[
              'group flex items-center gap-3 w-full px-3 py-2 rounded-xl transition-colors duration-200',
              isActive
                ? 'bg-emerald-100/60 text-emerald-800 ring-1 ring-emerald-200'
                : 'text-gray-700 hover:bg-emerald-50 hover:text-emerald-800'
            ]"
          >
            <component :is="item.icon" class="w-5 h-5 shrink-0" />
            <span class="truncate" v-show="!isCollapsed">{{ item.label }}</span>
          </a>
        </RouterLink>

        <div class="px-2 pt-5 pb-2 text-[11px] font-semibold tracking-wide text-gray-500" v-show="!isCollapsed">
          Management
        </div>

        <RouterLink to="/reports" custom v-slot="{ isActive, href, navigate }">
          <a
            :href="href"
            :title="isCollapsed ? 'Reports' : undefined"
            @click="navigate(); closeSidebarOnMobile()"
            :class="[
              'group flex items-center gap-3 w-full px-3 py-2 rounded-xl transition-colors duration-200',
              isActive
                ? 'bg-emerald-100/60 text-emerald-800 ring-1 ring-emerald-200'
                : 'text-gray-700 hover:bg-emerald-50 hover:text-emerald-800'
            ]"
          >
            <BarChart3 class="w-5 h-5 shrink-0" />
            <span class="truncate" v-show="!isCollapsed">Reports</span>
          </a>
        </RouterLink>
      </nav>

      <!-- Sidebar footer -->
      <div class="mt-auto border-t border-gray-200 p-3">
        <button
          class="w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition"
          @click="toggleTheme"
        >
          <Sun v-if="!isDark" class="w-5 h-5" />
          <Moon v-else class="w-5 h-5" />
          <span class="text-sm" v-show="!isCollapsed">{{ isDark ? 'Dark mode' : 'Light mode' }}</span>
        </button>
      </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header -->
      <header class="sticky top-0 z-30 bg-white border-b border-gray-200">
        <div class="h-16 px-4 flex items-center gap-4">
          <div class="md:hidden w-8"></div>

          <div class="min-w-0">
            <h2 class="text-xl font-semibold text-gray-800 leading-tight truncate">
              {{ routeTitle }}
            </h2>
            <nav class="text-xs text-gray-500 truncate" aria-label="Breadcrumb">
              <ol class="flex items-center gap-1">
                <li v-for="(m,i) in crumbs" :key="i" class="flex items-center gap-1">
                  <span class="truncate capitalize">{{ m }}</span>
                  <span v-if="i < crumbs.length - 1" class="text-gray-300">/</span>
                </li>
              </ol>
            </nav>
          </div>

          <!-- Search -->
          <div class="flex-1 flex justify-center">
            <div class="relative w-full max-w-xl">
              <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
              <input
                v-model="q"
                type="search"
                placeholder="Search trainees, payments, qualifications…"
                class="w-full pl-9 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-xl
                       focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-gray-800 placeholder:text-gray-400"
              />
            </div>
          </div>

          <!-- Actions -->
          <div class="flex items-center gap-2">
            <button class="p-2 rounded-lg hover:bg-gray-50 relative" aria-label="Notifications">
              <Bell class="w-5 h-5 text-gray-600" />
              <span class="absolute -top-0.5 -right-0.5 inline-flex h-2 w-2 rounded-full bg-red-500"></span>
            </button>

            <div class="relative">
              <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-2">
                <span class="bg-emerald-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-semibold">A</span>
                <span class="hidden md:inline text-gray-700">Admin User</span>
                <ChevronDown class="w-4 h-4 text-gray-500" />
              </button>

              <div v-if="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 h-full w-full z-10"></div>

              <div
                v-if="dropdownOpen"
                class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-1 z-20 border border-gray-100"
              >
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Organization</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Settings</a>
                <div class="my-1 border-t border-gray-100"></div>
                <a @click.prevent="handleLogout" href="#" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 cursor-pointer">Logout</a>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Content -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 bg-gray-50">
        <div class="mx-auto w-full max-w-7xl">
          <router-view />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, RouterLink ,  useRouter } from 'vue-router'
import {
  LayoutDashboard, Users, BookCopy, CreditCard, BarChart3,
  Menu, ChevronDown, ChevronsLeft, ChevronsRight,
  Bell, Search, Sun, Moon
} from 'lucide-vue-next'

const route = useRoute()
const router = useRouter() 


const sidebarOpen = ref(false)
const dropdownOpen = ref(false)
const isCollapsed = ref(false)
const isDark = ref(false)
const q = ref('')

const nav = [
  { to: '/dashboard', label: 'Dashboard', icon: LayoutDashboard },
  { to: '/trainees', label: 'Trainees', icon: Users },
  { to: '/qualifications', label: 'Qualifications', icon: BookCopy },
  { to: '/payments', label: 'Payments', icon: CreditCard },
]

const routeTitle = computed(() => {
  const n = (route.name ?? '').toString()
  return n ? n.charAt(0).toUpperCase() + n.slice(1) : 'Dashboard'
})

const crumbs = computed(() => {
  const parts = route.path.split('/').filter(Boolean)
  return parts.length ? parts : ['dashboard']
})

function closeSidebarOnMobile() {
  if (window.innerWidth < 768) sidebarOpen.value = false
}

// Add this new function
function handleLogout() {
  // In a real app, you would also clear any stored user data (like a token) here
  router.push({ name: 'login' })
}

function toggleCollapse() {
  isCollapsed.value = !isCollapsed.value
  sessionStorage.setItem('gestaac_sidebar_collapsed', isCollapsed.value ? '1' : '0')
}

function toggleTheme() {
  isDark.value = !isDark.value
  document.documentElement.classList.toggle('dark', isDark.value)
  sessionStorage.setItem('gestaac_theme', isDark.value ? 'dark' : 'light')
}

onMounted(() => {
  isCollapsed.value = sessionStorage.getItem('gestaac_sidebar_collapsed') === '1'
  isDark.value = sessionStorage.getItem('gestaac_theme') === 'dark'
  document.documentElement.classList.toggle('dark', isDark.value)
})
</script>

<!-- No scoped CSS needed; all classes applied directly to <a> -->
