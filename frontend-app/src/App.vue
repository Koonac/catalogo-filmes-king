<script setup>
import Navbar from "./components/Navbar.vue";
</script>

<template>
  <Navbar />
  <main class="container mx-auto px-8 py-8 max-w-8xl">
    <RouterView />
  </main>
</template>

<style scoped></style>
