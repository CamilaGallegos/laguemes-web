<template>
  <div id="app">
    <NavBarMenu />
    <router-view />
    <FooterComponent />
  </div>
</template>

<script>
import NavBarMenu from "./components/NavBarMenu.vue";
import FooterComponent from "./components/FooterComponent.vue";

export default {
  name: "App",
  components: {
    NavBarMenu,
    FooterComponent,
  },
};
</script>

<style>
nav {
  display: flex;
  gap: 10px;
  background-color: #003850;
  padding: 10px;
}
nav a {
  color: #ffffff;
  text-decoration: none;
}
</style>
