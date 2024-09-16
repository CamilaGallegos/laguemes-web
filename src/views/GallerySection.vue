<template>
  <div>
    <h1>Galería</h1>
    <button v-if="isLoggedIn" @click="showCreatePhotoForm = true">
      Añadir Imagen
    </button>

    <div v-if="showCreatePhotoForm">
      <h2>Nueva Imagen</h2>
      <input type="file" @change="handleFileUpload" />
      <input v-model="newPhoto.description" placeholder="Descripción" />
      <button @click="addPhoto">Guardar Imagen</button>
      <button @click="showCreatePhotoForm = false">Cancelar</button>
    </div>

    <ul>
      <li v-for="photo in photos" :key="photo.id">
        <img
          :src="`http://localhost/laguemes-web/public${photo.image_path}`"
          alt="Imagen"
          width="100"
        />
        <p>{{ photo.description }}</p>
        <div v-if="isLoggedIn">
          <button @click="editPhoto(photo)">Editar</button>
          <button @click="deletePhoto(photo.id)">Eliminar</button>
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
      photos: [],
      showCreatePhotoForm: false,
      showEditPhotoForm: false,
      newPhoto: {
        id: null,
        image: null,
        description: "",
      },
    };
  },
  methods: {
    async checkAuth() {
      this.isLoggedIn = localStorage.getItem("isAuthenticated") === "true";
    },
    handleFileUpload(event) {
      const file = event.target.files[0];
      this.newPhoto.image = file;
    },
    async fetchPhotos() {
      const response = await fetch(
        "http://localhost/laguemes-web/index.php/api/galeria"
      );
      const data = await response.json();
      this.photos = data;
    },
    async addPhoto() {
      const formData = new FormData();
      formData.append("image", this.newPhoto.image);
      formData.append("description", this.newPhoto.description);

      try {
        const response = await fetch(
          "http://localhost/laguemes-web/index.php/api/galeria",
          {
            method: "POST",
            body: formData,
          }
        );

        const result = await response.json();
        if (result.success) {
          this.fetchPhotos();
          this.newPhoto = { image: null, description: "" };
          this.showCreatePhotoForm = false;
        } else {
          alert("Error al añadir la imagen");
        }
      } catch (error) {
        console.error("Error:", error);
        alert("Error al conectar con el servidor");
      }
    },
    async deletePhoto(id) {
      await fetch(`http://localhost/laguemes-web/index.php/api/galeria/${id}`, {
        method: "DELETE",
      });
      this.fetchPhotos();
    },
    editPhoto(photo) {
      this.newPhoto = { ...photo };
      this.showEditPhotoForm = true;
    },
    async updatePhoto() {
      const formData = new FormData();
      formData.append("image", this.newPhoto.image);
      formData.append("description", this.newPhoto.description);

      const response = await fetch(
        `http://localhost/laguemes-web/index.php/api/galeria/${this.newPhoto.id}`,
        {
          method: "PUT",
          body: formData,
        }
      );

      const result = await response.json();
      if (result.success) {
        this.fetchPhotos();
        this.resetForm();
        this.showEditPhotoForm = false;
      } else {
        alert("Error al actualizar la imagen");
      }
    },
    cancelEdit() {
      this.resetForm();
      this.showEditPhotoForm = false;
    },
    resetForm() {
      this.newPhoto = { id: null, image: null, description: "" };
    },
  },
  async mounted() {
    await this.checkAuth();
    this.fetchPhotos();
  },
};
</script>
