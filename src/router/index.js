import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import DashboardView from '../views/DashboardView.vue'
import AdminLayout from '../layouts/AdminLayout.vue'
import QualificationsView from '../views/QualificationsView.vue'

// Create placeholder components for other pages
const createPlaceholderComponent = (name) => ({
  template: `<div class="bg-white p-6 rounded-lg shadow-md"><h1 class="text-2xl font-bold">${name} Page</h1><p>Content for the ${name.toLowerCase()} page will go here.</p></div>`,
  name: `${name}View`
});

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', name: 'login', component: LoginView },
    {
      path: '/admin',
      component: AdminLayout,
      children: [
        { path: '/dashboard', name: 'dashboard', component: DashboardView },
        { path: '/trainees', name: 'trainees', component: createPlaceholderComponent('Trainees') },
        // REPLACE THE PLACEHOLDER WITH THE REAL COMPONENT
        { path: '/qualifications', name: 'qualifications', component: QualificationsView },
        { path: '/payments', name: 'payments', component: createPlaceholderComponent('Payments') },
        { path: '/reports', name: 'reports', component: createPlaceholderComponent('Reports') },
      ]
    }
  ]
})

export default router
