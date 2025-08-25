<template>
  <div class="min-h-screen grid grid-cols-1 md:grid-cols-2 bg-gray-50">
    <!-- Brand / Left Panel -->
    <section class="relative hidden md:flex items-center justify-center overflow-hidden bg-gradient-to-br from-emerald-600 via-emerald-500 to-teal-500">
      <!-- Soft glow shapes -->
      <div class="pointer-events-none absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/15 blur-3xl"></div>
      <div class="pointer-events-none absolute -bottom-24 -right-16 h-64 w-64 rounded-full bg-teal-300/20 blur-3xl"></div>

      <div class="relative z-10 flex max-w-xl flex-col items-center px-10 text-center text-white">
        <img src="/images/logo.png" alt="GESTAAC Inc. Logo" class="mb-5 h-28 w-28 object-contain drop-shadow" />
        <h1 class="text-2xl md:text-3xl font-bold leading-snug">
          Great Enthusiasts of Skills Training Academy and Assessment Center Inc.
        </h1>
        <p class="mt-1 text-emerald-50/90 text-lg">(GESTAAC Inc.)</p>

        <div class="mt-8 grid grid-cols-1 gap-3 w-full">
          <div class="rounded-2xl bg-white/10 px-4 py-3 backdrop-blur ring-1 ring-white/15 text-left">
            <p class="text-sm leading-6">
              <span class="font-semibold">Admin Portal</span> · Secure access to trainees, qualifications, and payments.
            </p>
          </div>
          <div class="rounded-2xl bg-white/10 px-4 py-3 backdrop-blur ring-1 ring-white/15 text-left">
            <p class="text-sm leading-6">
              Streamlined, modern, and designed for fast daily operations.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Form / Right Panel -->
    <section class="flex items-center justify-center px-6 py-10 md:px-10">
      <div class="w-full max-w-md">
        <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">
          <div class="px-8 py-8">
            <div class="mb-6 text-center">
              <h2 class="text-2xl font-bold text-gray-900">Admin Portal Login</h2>
              <p class="mt-1 text-sm text-gray-500">Use your assigned credentials to sign in.</p>
            </div>

            <!-- Error banner -->
            <div
              v-if="errorMessage"
              class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700"
            >
              {{ errorMessage }}
            </div>

            <form @submit.prevent="handleLogin" class="space-y-4">
              <!-- User ID -->
              <div>
                <label for="user_id_code" class="mb-1 block text-sm font-medium text-gray-700">User ID</label>
                <div class="relative">
                  <User class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                  <input
                    id="user_id_code"
                    v-model.trim="userIdCode"
                    type="text"
                    autocomplete="username"
                    required
                    class="block w-full rounded-lg border border-gray-300 bg-white pl-9 pr-3 py-2 text-gray-900
                           placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    placeholder="Enter your user ID"
                  />
                </div>
              </div>

              <!-- Password -->
              <div>
                <label for="password" class="mb-1 block text-sm font-medium text-gray-700">Password</label>
                <div class="relative">
                  <Lock class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" />
                  <input
                    id="password"
                    :type="showPassword ? 'text' : 'password'"
                    v-model="password"
                    autocomplete="current-password"
                    required
                    class="block w-full rounded-lg border border-gray-300 bg-white pl-9 pr-10 py-2 text-gray-900
                           placeholder:text-gray-400 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    placeholder="Enter your password"
                  />
                  <button
                    type="button"
                    class="absolute right-2 top-1/2 -translate-y-1/2 rounded-md p-1 text-gray-500 hover:bg-gray-50"
                    :aria-label="showPassword ? 'Hide password' : 'Show password'"
                    @click="showPassword = !showPassword"
                  >
                    <Eye v-if="!showPassword" class="h-4 w-4" />
                    <EyeOff v-else class="h-4 w-4" />
                  </button>
                </div>
              </div>

              <!-- Options -->
              <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-700">
                  <input
                    type="checkbox"
                    v-model="remember"
                    class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                  />
                  Remember me
                </label>
                <a href="#" class="text-sm font-medium text-emerald-700 hover:text-emerald-800">Forgot password?</a>
              </div>

              <!-- Submit -->
              <button
                type="submit"
                :disabled="isLoading"
                class="inline-flex w-full items-center justify-center rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600
                       px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors
                       hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-500
                       disabled:cursor-not-allowed disabled:opacity-70"
              >
                <Loader2 v-if="isLoading" class="mr-2 h-4 w-4 animate-spin" />
                <span>{{ isLoading ? 'Signing in…' : 'Sign In' }}</span>
              </button>
            </form>
          </div>

          <!-- Footer -->
          <div class="rounded-b-2xl bg-gray-50 px-8 py-4 text-center text-xs text-gray-500">
            © {{ new Date().getFullYear() }} GESTAAC Inc. · Authorized access only
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { User, Lock, Eye, EyeOff, Loader2 } from 'lucide-vue-next'

const userIdCode = ref('')
const password = ref('')
const showPassword = ref(false)
const remember = ref(false)
const errorMessage = ref('')
const isLoading = ref(false)
const router = useRouter()

// Relative path hits your Vite proxy → http://vocational-school-api.test
const API_URL = '/api/login'

const handleLogin = async () => {
  errorMessage.value = ''
  isLoading.value = true
  try {
    const response = await axios.post(API_URL, {
      user_id_code: userIdCode.value,
      password: password.value,
      remember: remember.value ? 1 : 0,
    })

    if (response.data?.status === 'success') {
      // You can store token/session here if your API returns it.
      router.push({ name: 'dashboard' })
      return
    }

    errorMessage.value = response.data?.message || 'Login failed. Please check your credentials.'
  } catch (err) {
    errorMessage.value =
      err?.response?.data?.message ||
      err?.message ||
      'Login failed. Please check your credentials.'
    console.error('Login error:', err)
  } finally {
    isLoading.value = false
  }
}
</script>
