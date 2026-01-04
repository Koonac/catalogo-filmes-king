import { defineStore } from "pinia";

export const useGenresStore = defineStore("genres", {
  state: () => ({
    genres: [
      {
        value: 28,
        label: "Ação",
      },
      {
        value: 12,
        label: "Aventura",
      },
      {
        value: 16,
        label: "Animação",
      },
      {
        value: 35,
        label: "Comédia",
      },
      {
        value: 80,
        label: "Crime",
      },
      {
        value: 99,
        label: "Documentário",
      },
      {
        value: 18,
        label: "Drama",
      },
      {
        value: 10751,
        label: "Família",
      },
      {
        value: 14,
        label: "Fantasia",
      },
      {
        value: 36,
        label: "História",
      },
      {
        value: 27,
        label: "Terror",
      },
      {
        value: 10402,
        label: "Música",
      },
      {
        value: 9648,
        label: "Mistério",
      },
      {
        value: 10749,
        label: "Romance",
      },
      {
        value: 878,
        label: "Ficção científica",
      },
      {
        value: 10770,
        label: "Cinema TV",
      },
      {
        value: 53,
        label: "Thriller",
      },
      {
        value: 10752,
        label: "Guerra",
      },
      {
        value: 37,
        label: "Faroeste",
      },
    ],
  }),

  getters: {
    getGenres: (state) => state.genres,
  },
});
