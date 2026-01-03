<template>
  <nav
    class="sticky top-0 z-1000 py-4 shadow-md bg-linear-to-r from-[rgb(120,45,200)] to-[rgb(72,27,120)]"
  >
    <div
      class="container mx-auto px-8 flex items-center max-w-8xl md:flex-row flex-col md:gap-0 gap-4 relative"
    >
      <div class="md:absolute md:left-8">
        <RouterLink
          to="/"
          class="text-white no-underline hover:opacity-90 transition-opacity duration-300"
        >
          <h2
            class="m-0 text-2xl md:text-2xl font-semibold text-white drop-shadow-lg"
          >
            Catálogo de Filmes
          </h2>
        </RouterLink>
      </div>
      <ul
        class="flex list-none m-0 p-0 gap-4 md:gap-8 items-center w-full md:w-auto justify-center md:mx-auto"
      >
        <li>
          <RouterLink
            to="/"
            class="text-white no-underline font-medium px-4 py-2 rounded-md transition-all duration-300 hover:bg-white/15 hover:-translate-y-0.5"
            active-class="bg-white/25 font-semibold"
          >
            Início
          </RouterLink>
        </li>
        <li>
          <RouterLink
            to="/favoritos"
            class="text-white no-underline font-medium px-4 py-2 rounded-md transition-all duration-300 hover:bg-white/15 hover:-translate-y-0.5"
            active-class="bg-white/25 font-semibold"
          >
            Favoritos
          </RouterLink>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script setup>
import { RouterLink } from "vue-router";
</script>
