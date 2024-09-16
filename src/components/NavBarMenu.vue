<template>
  <nav>
    <ul>
      <li><router-link to="/">Inicio</router-link></li>
      <li><router-link to="/eventos">Eventos</router-link></li>
      <li><router-link to="/galeria">Galería</router-link></li>
      <li><router-link to="/contacto">Contacto</router-link></li>
      <li><router-link to="/registro">Registrarse</router-link></li>
      <li><router-link to="/login">Iniciar Sesión</router-link></li>
    </ul>
  </nav>
</template>
<script>
export default {
  name: "NavBarMenu",
};
</script>
<style scoped>
/* Estilos para la navegación */
</style>
