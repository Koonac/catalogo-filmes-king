<script setup>
import axios from "axios";
import { ref } from "vue";

let message = ref("");

const test = async () => {
  const response = await axios.get("http://localhost:8000/api/test");
  message.value = response.data;
};
</script>

<template>
  <button @click="test" class="bg-blue-500 text-white p-2 rounded-md">Test API</button>
  <h2>Retorno message api</h2>
  <p>{{ message }}</p>
</template>

<style scoped>

</style>
