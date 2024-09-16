<template>
  <div>
    <h1>Registro de Usuario</h1>
    <input v-model="username" placeholder="Nombre de usuario" />
    <input v-model="password" type="password" placeholder="Contraseña" />
    <button @click="register">Registrar</button>
    <p v-if="message">{{ message }}</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      username: "",
      password: "",
      message: "",
    };
  },
  methods: {
    async register() {
      try {
        const response = await fetch(
          "http://localhost/laguemes-web/index.php/api/usuarios",
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
          alert("Registro exitoso");
          window.location.href = "/login"; // Redirigir al login tras registro exitoso
        } else {
          alert("Error al registrar el usuario");
        }
      } catch (error) {
        console.error("Error:", error);
        alert("Error al conectar con el servidor");
      }
    },
  },
};
</script>
