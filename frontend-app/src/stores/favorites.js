import { defineStore } from "pinia";
import api from "../config/axios";

export const useFavoriteStore = defineStore("favorites", {
  state: () => ({
    favorites: [],
    currentPage: 1,
    perPage: 15,
    totalPages: 1,
    loading: false,
    error: null,
  }),

  getters: {
    favoritesCount: (state) => state.favorites.length,
    isFavorite: (state) => (movieId) => {
      return state.favorites.some((fav) => fav.tmdb_id === movieId);
    },
  },

  actions: {
    async fetchFavorites() {
      this.loading = true;
      this.error = null;

      try {
        const response = await api.get("/favorites/list");

        if (response.data.status === "success") {
          this.favorites = response.data.data || [];
          this.currentPage = response.data.pagination.current_page;
          this.perPage = response.data.pagination.per_page;
          this.totalPages = response.data.pagination.total_pages;
        } else {
          throw new Error(response.data.message || "Erro ao buscar favoritos");
        }
      } catch (error) {
        this.error =
          error.response?.data?.message ||
          error.message ||
          "Erro ao buscar favoritos";
        console.error("Erro ao buscar favoritos:", error);
      } finally {
        this.loading = false;
      }
    },

    async addFavorite(tmdbId) {
      this.loading = true;
      this.error = null;

      try {
        const response = await api.post("/favorites/add-tmdb", {
          tmdb_id: tmdbId,
        });

        if (response.data.status === "success") {
          return response.data.data;
        } else {
          throw new Error(
            response.data.message || "Erro ao adicionar favorito"
          );
        }
      } catch (error) {
        this.error =
          error.response?.data?.message ||
          error.message ||
          "Erro ao adicionar favorito";
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async removeFavorite(id) {
      this.error = null;

      try {
        const response = await api.delete("/favorites/remove", {
          params: { id: id },
        });

        if (response.data.status === "success") {
          if (this.favorites.length > 0) {
            this.favorites = this.favorites.filter(
              (favorite) => favorite.id !== id
            );
          }
          return true;
        } else {
          throw new Error(response.data.message || "Erro ao remover favorito");
        }
      } catch (error) {
        this.error =
          error.response?.data?.message ||
          error.message ||
          "Erro ao remover favorito";
        throw error;
      }
    },
  },
});
