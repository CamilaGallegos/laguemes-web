<template>
  <div>
    <h1>Iniciar Sesión</h1>
    <input v-model="username" placeholder="Nombre de usuario" />
    <input v-model="password" type="password" placeholder="Contraseña" />
    <button @click="login">Iniciar Sesión</button>

    <div v-if="isAuthenticated">
      <p>Bienvenido, {{ username }}</p>
      <button @click="logout">Cerrar Sesión</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      username: "",
      password: "",
      isAuthenticated: false,
    };
  },
  methods: {
    async login() {
      try {
        const response = await fetch(
          "http://localhost/laguemes-web/index.php/api/login",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              username: this.username,
              password: this.password,
            }),
          }
        );
        const result = await response.json();
        if (result.success) {
          this.isAuthenticated = true;
          localStorage.setItem("isAuthenticated", "true"); 
          alert("Inicio de sesión exitoso");
        } else {
          alert("Error al iniciar sesión. Verifica tus credenciales.");
        }
      } catch (error) {
        console.error("Error:", error);
        alert("Error al conectar con el servidor");
      }
    },
    logout() {
      this.isAuthenticated = false;
      localStorage.setItem("isAuthenticated", "false");
      this.username = "";
      this.password = "";
      alert("Sesión cerrada exitosamente.");
    },
    async checkAuth() {
      this.isAuthenticated = localStorage.getItem("isAuthenticated") === "true";
    },
  },
  async mounted() {
    await this.checkAuth();
  },
};
</script>
