<template>
  <div>
    <h1>Contacto</h1>
    <form @submit.prevent="sendContact">
      <input v-model="newContact.name" placeholder="Nombre" required />
      <input v-model="newContact.email" placeholder="Email" required />
      <textarea
        v-model="newContact.message"
        placeholder="Mensaje"
        required
      ></textarea>
      <button type="submit">Enviar Mensaje</button>
    </form>

    <div v-if="isLoggedIn">
      <h2>Mensajes Recibidos</h2>
      <ul>
        <li v-for="contact in contacts" :key="contact.id">
          <strong>{{ contact.name }}</strong> ({{ contact.email }}):<br />
          {{ contact.message }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isLoggedIn: false,
      newContact: {
        name: "",
        email: "",
        message: "",
      },
      contacts: [],
    };
  },
  methods: {
    async sendContact() {
      try {
        const response = await fetch(
          "http://localhost/laguemes-web/index.php/api/contacto",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(this.newContact),
          }
        );

        const result = await response.json();
        if (result.success) {
          alert("Mensaje enviado exitosamente");
          this.newContact = { name: "", email: "", message: "" };
        } else {
          alert("Error al enviar el mensaje");
        }
      } catch (error) {
        console.error("Error:", error);
        alert("Error al conectar con el servidor");
      }
    },
    async fetchContacts() {
      const response = await fetch(
        "http://localhost/laguemes-web/index.php/api/contacto"
      );
      const data = await response.json();
      this.contacts = data;
    },
    async checkAuth() {
      this.isLoggedIn = localStorage.getItem("isAuthenticated") === "true";
    },
  },
  async mounted() {
    await this.checkAuth();
  },
};
</script>
