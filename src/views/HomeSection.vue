<template>
  <div>
    <h1>Inicio</h1>
    <p v-if="isLoggedIn">Bienvenido de nuevo, {{ username }}.</p>
    <p v-else>Bienvenido a La Güemes.</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isLoggedIn: false,
      username: "",
    };
  },
  methods: {
    async checkAuth() {
      const response = await fetch(
        "http://localhost/laguemes-web/index.php/api/usuarios/auth"
      );
      const data = await response.json();
      this.isLoggedIn = data.authenticated;
      this.username = data.username;
    },
  },
  mounted() {
    if (localStorage.getItem("isAuthenticated") === "true") {
      this.isAuthenticated = true; // Restaurar el estado de autenticación
    }
  },
};
</script>
