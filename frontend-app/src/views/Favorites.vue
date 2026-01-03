<script setup>
import CardMovie from "../components/CardMovie.vue";
import { useFavoriteStore } from "../stores/favorites";
import { Icon } from "@iconify/vue";
import { onMounted } from "vue";

// INICIALIZANDO VARIÁVEIS
const favoriteStore = useFavoriteStore();

// FUNÇÃO PARA CARREGAR FILMES FAVORITOS
onMounted(async () => {
  await favoriteStore.fetchFavorites();
});

// FUNÇÃO PARA REMOVER FILME FAVORITO
const handleRemoveFavorite = async (id) => {
  await favoriteStore.removeFavorite(id);
};
</script>

<template>
  <div class="flex flex-col gap-4">
    <!-- Título intuitivo para o usuário -->
    <h1 class="text-2xl font-bold text-center text-gray-700 drop-shadow-lg">
      Meus filmes favoritos
    </h1>

    <!-- Lista de filmes -->
    <div v-if="favoriteStore.loading">
      <div class="flex justify-center items-center h-full">
        <Icon class="w-5 h-5 mr-2 text-gray-500" icon="line-md:loading-loop" />
        <span class="text-gray-500 text-center text-lg"> Carregando... </span>
      </div>
    </div>
    <div v-else-if="favoriteStore.favorites.length === 0">
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
            v-for="favorite in favoriteStore.favorites"
            :key="favorite.id"
            :image="favorite.poster_path"
            :title="favorite.title"
            :rating="favorite.vote_average.toFixed(1)"
            :year="favorite.release_date.split('-')[0]"
            :hoverText="favorite.overview"
            @removeFavorite="handleRemoveFavorite(favorite.id)"
            isFavorite
          />
       </div>
    </div>
  </div>
</template>
