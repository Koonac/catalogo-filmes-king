import { defineStore } from "pinia";
import api from "../config/axios";

export const useMoviesStore = defineStore("movies", {
  state: () => ({
    movies: [],
    searchQuery: "",
    loading: false,
    error: null,
    currentPage: 1,
    totalPages: 1,
  }),

  getters: {
    hasMovies: (state) => !state.loading && state.movies.length > 0,
  },

  actions: {
    async searchMovies(searchQuery, page = 1) {
      if (!searchQuery || searchQuery.trim() === "") {
        this.movies = [];
        return;
      }

      this.loading = true;
      this.error = null;
      this.searchQuery = searchQuery;

      try {
        const response = await api.get("/tmdb/search-movie", {
          params: {
            movie: searchQuery,
            page: page,
          },
        });

        if (response.status === 200 && response.data.status === "success") {
          this.movies = response.data.data.results;
          this.totalPages = response.data.data.total_pages;
          this.currentPage = response.data.data.page;
        } else {
          throw new Error(response.data.message || "Erro ao buscar filmes");
        }
      } catch (error) {
        this.error = error.response?.data?.message || "Erro ao buscar filmes";
        this.movies = [];
        console.error(error);
      } finally {
        this.loading = false;
      }
    },

    async getMovieDetails(id) {
      this.loading = true;
      this.error = null;

      try {
        const response = await api.get("/tmdb/details-movie", {
          params: {
            id: id,
            language: "pt-BR",
          },
        });

        if (response.data.status === "success") {
          return response.data.data;
        } else {
          throw new Error(
            response.data.message || "Erro ao buscar detalhes do filme"
          );
        }
      } catch (error) {
        this.error =
          error.response?.data?.message ||
          error.message ||
          "Erro ao buscar detalhes";
        throw error;
      } finally {
        this.loading = false;
      }
    },
  },
});
