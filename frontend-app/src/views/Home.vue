<script setup>
import InputText from "../components/Input/Text.vue";
import Button from "../components/Button.vue";
import CardMovie from "../components/CardMovie.vue";
import { useMoviesStore } from "../stores/movies";
import { useFavoriteStore } from "../stores/favorites";
import { Icon } from "@iconify/vue";
import { ref } from "vue";

// INICIALIZANDO VARIÁVEIS
const moviesStore = useMoviesStore();
const favoriteStore = useFavoriteStore();
const searchQuery = ref("");

// FUNÇÃO PARA PESQUISAR FILMES
const handleSearchMovies = async () => {
  await moviesStore.searchMovies(searchQuery.value);
};

// FUNÇÃO PARA ADICIONAR FILME FAVORITO
const handleAddFavorite = async (tmdbId) => {
  await favoriteStore.addFavorite(tmdbId);
};
</script>

<template>
  <div class="flex flex-col gap-4">
    <!-- Título intuitivo para o usuário -->
    <h1 class="text-2xl font-bold text-center text-gray-700 drop-shadow-lg">
      Pesquise por um filme
    </h1>

    <!-- Campo de pesquisa e botão de pesquisa -->
    <div class="flex gap-4">
      <InputText
        placeholder="A fantastica fábrica de chocolate"
        v-model="searchQuery"
        @keyup.enter="handleSearchMovies"
      />
      <Button
        color="primary"
        variant="outline"
        @click="handleSearchMovies"
        :loading="moviesStore.loading"
      >
        <Icon
          v-show="!moviesStore.loading"
          class="w-5 h-5 mr-2"
          icon="mdi:search"
        />
        Pesquisar
      </Button>
    </div>

    <!-- Lista de filmes -->
    <div v-if="moviesStore.loading">
      <div class="flex justify-center items-center h-full">
        <Icon class="w-5 h-5 mr-2 text-gray-500" icon="line-md:loading-loop" />
        <span class="text-gray-500 text-center text-lg"> Carregando... </span>
      </div>
    </div>
    <div v-else-if="moviesStore.movies.length === 0">
      <div class="flex justify-center items-center h-full">
        <span class="text-gray-500 text-center text-lg">
          Nenhum filme encontrado.
        </span>
      </div>
    </div>
    <div v-else>
      <div
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"
      >
        <CardMovie
          v-for="movie in moviesStore.movies"
          :key="movie.id"
          :image="`https://image.tmdb.org/t/p/w500/${movie.poster_path}`"
          :title="movie.title"
          :rating="movie.vote_average.toFixed(1)"
          :year="movie.release_date.split('-')[0]"
          :hoverText="movie.overview"
          @addFavorite="handleAddFavorite(movie.id)"
        />
      </div>
    </div>
  </div>
</template>
