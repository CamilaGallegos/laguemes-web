import { createRouter, createWebHistory } from 'vue-router'
import Inicio from '../components/Inicio.vue'
import Eventos from '../components/Eventos.vue'
import Galeria from '../components/Galeria.vue'
import Contactos from '../components/Contactos.vue'

const routes = [
  { path: '/', name: 'Inicio', component: Inicio },
  { path: '/eventos', name: 'Eventos', component: Eventos },
  { path: '/galeria', name: 'Galeria', component: Galeria },
  { path: '/contactos', name: 'Contactos', component: Contactos }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router