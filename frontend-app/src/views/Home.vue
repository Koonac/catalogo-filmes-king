<script setup>
import InputText from "../components/Input/Text.vue";
import Button from "../components/Button.vue";
import CardMovie from "../components/CardMovie.vue";
import { Icon } from "@iconify/vue";
</script>

<template>
  <div class="flex flex-col gap-4">
    <!-- Título intuitivo para o usuário -->
    <h1 class="text-2xl font-bold text-center text-gray-700 drop-shadow-lg">
      Pesquise por um filme
    </h1>

    <!-- Campo de pesquisa e botão de pesquisa -->
    <div class="flex gap-4">
      <InputText placeholder="A fantastica fábrica de chocolate" />
      <Button color="primary" variant="outline">
        <Icon class="w-5 h-5 mr-2" icon="mdi:search" />
        Pesquisar
      </Button>
    </div>

    <!-- Lista de filmes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <CardMovie
        image="https://image.tmdb.org/t/p/w500/3nqxXZ7QixMeWoQD10cw6FZf277.jpg"
        title="A fantastica fábrica de chocolate"
        rating="8.5"
        hoverText="A fantastica fábrica de chocolate é um filme de 2005 dirigido por Tim Burton e escrito por John August."
      />
      <CardMovie
        image="https://image.tmdb.org/t/p/w500/3nqxXZ7QixMeWoQD10cw6FZf277.jpg"
        title="A fantastica fábrica de chocolate"
        rating="8.5"
        hoverText="A fantastica fábrica de chocolate é um filme de 2005 dirigido por Tim Burton e escrito por John August."
      />
      <CardMovie
        image="https://image.tmdb.org/t/p/w500/3nqxXZ7QixMeWoQD10cw6FZf277.jpg"
        title="A fantastica fábrica de chocolate"
        rating="8.5"
        hoverText="A fantastica fábrica de chocolate é um filme de 2005 dirigido por Tim Burton e escrito por John August."
      />
      <CardMovie
        image="https://image.tmdb.org/t/p/w500/3nqxXZ7QixMeWoQD10cw6FZf277.jpg"
        title="A fantastica fábrica de chocolate"
        rating="8.5"
        hoverText="A fantastica fábrica de chocolate é um filme de 2005 dirigido por Tim Burton e escrito por John August."
      />
    </div>
  </div>
</template>
