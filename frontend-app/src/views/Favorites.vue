<script setup>
import Button from "../components/Button.vue";
import CardMovie from "../components/CardMovie.vue";
import InputText from "../components/Input/Text.vue";
import InputSelect from "../components/Input/Select.vue";
import Pagination from "../components/Pagination.vue";
import { useFavoriteStore } from "../stores/favorites";
import { useGenresStore } from "../stores/genres";
import { Icon } from "@iconify/vue";
import { onMounted, ref } from "vue";

// INICIALIZANDO VARIÁVEIS
const favoriteStore = useFavoriteStore();
const genresStore = useGenresStore();
const selectedGenres = ref([]);
const searchQuery = ref("");

// FUNÇÃO PARA PESQUISAR FILMES FAVORITOS
const handleSearchFavoritesMovies = async (page = 1) => {
  const filters = {
    search: searchQuery.value,
    genres: selectedGenres.value,
  };

  await favoriteStore.fetchFavorites(filters, page);
};

// FUNÇÃO PARA MUDAR DE PÁGINA
const handlePageChange = async (page) => {
  await handleSearchFavoritesMovies(page);
  // Scroll para o topo da lista de filmes
  window.scrollTo({ top: 0, behavior: "smooth" });
};

// FUNÇÃO PARA REMOVER FILME FAVORITO
const handleRemoveFavorite = async (id) => {
  await favoriteStore.removeFavorite(id);
};

// FUNÇÃO PARA CARREGAR FILMES FAVORITOS
onMounted(async () => {
  await handleSearchFavoritesMovies(1);
});
</script>

<template>
  <div class="flex flex-col gap-4">
    <!-- Título intuitivo para o usuário -->
    <h1 class="text-2xl font-bold text-center text-gray-700 drop-shadow-lg">
      Meus filmes favoritos
    </h1>

    <div class="flex gap-2">
      <div class="flex gap-2 w-3/4 items-end">
        <InputText
          label="Pesquisar por filme"
          placeholder="A fantastica fábrica de chocolate"
          v-model="searchQuery"
          @keyup.enter="handleSearchFavoritesMovies(1)"
        />
        <Button
          color="primary"
          variant="outline"
          @click="handleSearchFavoritesMovies(1)"
          :loading="favoriteStore.loading"
        >
          <Icon
            v-show="!favoriteStore.loading"
            class="w-5 h-5 mr-2"
            icon="mdi:search"
          />
          Pesquisar
        </Button>
      </div>
      <div class="w-1/4">
        <InputSelect
          v-model="selectedGenres"
          :options="genresStore.getGenres"
          label="Filtrar por gêneros"
          placeholder="Selecione os gêneros"
          clearable
          multiple
          @update:modelValue="handleSearchFavoritesMovies(1)"
        />
      </div>
    </div>

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
		  :genres="favorite.genres.map(genre => genre.name).join(', ')"
          :hoverText="favorite.overview"
          @removeFavorite="handleRemoveFavorite(favorite.id)"
          isFavorite
        />
      </div>
      <!-- Componente de Paginação -->
      <Pagination
        :current-page="favoriteStore.currentPage"
        :total-pages="favoriteStore.totalPages"
        @page-change="handlePageChange"
      />
    </div>
  </div>
</template>
