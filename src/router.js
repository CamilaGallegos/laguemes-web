import { createRouter, createWebHistory } from 'vue-router';
import HomeSection from './views/HomeSection.vue';
import EventSection from './views/EventSection.vue';
import GallerySection from './views/GallerySection.vue';
import ContactSection from './views/ContactSection.vue';
import RegisterSection from './views/RegisterSection.vue';
import LoginSection from './views/LoginSection.vue';

const routes = [
  { path: '/', component: HomeSection },
  { path: '/eventos', component: EventSection },
  { path: '/galeria', component: GallerySection },
  { path: '/contacto', component: ContactSection },
  { path: '/registro', component: RegisterSection },
  { path: '/login', component: LoginSection },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;