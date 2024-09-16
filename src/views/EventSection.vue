<template>
  <div>
    <h1>Eventos</h1>
    <button v-if="isLoggedIn" @click="showCreateEventForm = true">
      Crear Evento
    </button>

    <div v-if="showCreateEventForm">
      <h2>Crear Nuevo Evento</h2>
      <input v-model="newEvent.name" placeholder="Nombre del Evento" />
      <input v-model="newEvent.description" placeholder="Descripción" />
      <input v-model="newEvent.date" type="date" placeholder="Fecha" />
      <button @click="createEvent">Guardar Evento</button>
      <button @click="showCreateEventForm = false">Cancelar</button>
    </div>

    <div v-if="showEditEventForm">
      <h2>Editar Evento</h2>
      <input v-model="newEvent.name" placeholder="Nombre del Evento" />
      <input v-model="newEvent.description" placeholder="Descripción" />
      <input v-model="newEvent.date" type="date" placeholder="Fecha" />
      <button @click="updateEvent">Actualizar Evento</button>
      <button @click="cancelEdit">Cancelar</button>
    </div>

    <ul>
      <li v-for="event in events" :key="event.id">
        <strong>{{ event.name }}</strong
        >: {{ event.description }} ({{ event.date }})
        <div v-if="isLoggedIn">
          <button @click="editEvent(event)">Editar</button>
          <button @click="deleteEvent(event.id)">Eliminar</button>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      isLoggedIn: false,
      events: [],
      showCreateEventForm: false,
      showEditEventForm: false,
      newEvent: {
        id: null,
        name: "",
        description: "",
        date: "",
      },
    };
  },
  methods: {
    async fetchEvents() {
      const response = await fetch(
        "http://localhost/laguemes-web/index.php/api/eventos"
      );
      const data = await response.json();
      this.events = data;
    },
    async checkAuth() {
      this.isLoggedIn = localStorage.getItem("isAuthenticated") === "true";
    },
    async createEvent() {
      if (
        !this.newEvent.name ||
        !this.newEvent.description ||
        !this.newEvent.date
      ) {
        alert("Todos los campos son obligatorios.");
        return;
      }

      const response = await fetch(
        "http://localhost/laguemes-web/index.php/api/eventos",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(this.newEvent),
        }
      );

      const result = await response.json();
      if (result.success) {
        this.fetchEvents();
        this.resetForm();
        this.showCreateEventForm = false;
      } else {
        alert("Error al crear el evento");
      }
    },
    async deleteEvent(id) {
      await fetch(`http://localhost/laguemes-web/index.php/api/eventos/${id}`, {
        method: "DELETE",
      });
      this.fetchEvents();
    },
    editEvent(event) {
      this.newEvent = { ...event };
      this.newEvent.date = this.newEvent.date.split(" ")[0];
      this.showEditEventForm = true;
      this.showCreateEventForm = false;
    },
    async updateEvent() {
      if (
        !this.newEvent.name ||
        !this.newEvent.description ||
        !this.newEvent.date
      ) {
        alert("Todos los campos son obligatorios.");
        return;
      }

      const response = await fetch(
        `http://localhost/laguemes-web/index.php/api/eventos/${this.newEvent.id}`,
        {
          method: "PUT",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(this.newEvent),
        }
      );

      const result = await response.json();
      if (result.success) {
        this.fetchEvents();
        this.resetForm();
        this.showEditEventForm = false;
      } else {
        alert("Error al actualizar el evento");
      }
    },
    cancelEdit() {
      this.resetForm();
      this.showEditEventForm = false;
    },
    resetForm() {
      this.newEvent = { id: null, name: "", description: "", date: "" };
    },
  },
  async mounted() {
    await this.checkAuth();
    this.fetchEvents();
  },
};
</script>
