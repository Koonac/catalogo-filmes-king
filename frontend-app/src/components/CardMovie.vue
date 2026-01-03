<script setup>
import { Icon } from "@iconify/vue";
import Button from "./Button.vue";
defineProps({
  image: {
    type: String,
    required: true,
  },
  title: {
    type: String,
    required: true,
  },
  rating: {
    type: [Number, String],
    required: true,
  },
  year: {
    type: [Number, String],
    required: true,
  },
  hoverText: {
    type: String,
    default: "",
  },
  isFavorite: {
    type: Boolean,
    default: false,
  },
});

defineEmits(["addFavorite", "removeFavorite", "moreInfo"]);
</script>

<template>
  <div
    class="group relative w-full rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300 cursor-pointer"
  >
    <!-- Container da imagem com overlay no hover -->
    <div class="relative w-full aspect-2/3 overflow-hidden">
      <img
        :src="image"
        :alt="title"
        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
      />
      <!-- Botão de favoritar -->
      <button
        @click.stop="isFavorite ? $emit('removeFavorite') : $emit('addFavorite')"
        class="absolute top-2 right-2 p-2 bg-black/50 hover:bg-black/70 rounded-full transition-all duration-200 z-10 backdrop-blur-sm group/favorite cursor-pointer"
      >
        <Icon
          v-if="!isFavorite"
          icon="mage:heart"
          class="w-6 h-6 text-white group-hover/favorite:hidden"
        />
        <Icon
          v-else
          icon="mage:heart-fill"
          class="w-6 h-6 text-red-500 group-hover/favorite:hidden"
        />
        <Icon
          icon="mage:heart-fill"
          class="w-6 h-6 text-red-500 hidden group-hover/favorite:block"
        />
      </button>
      <!-- Overlay escuro no hover -->
      <div
        class="absolute inset-0 bg-black/0 group-hover:bg-black/70 transition-all duration-300 flex items-center justify-center"
      >
        <div class="flex flex-col justify-between gap-8">
          <h3
            class="text-white/90 text-center px-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-lg md:text-xl font-bold"
          >
            {{ title }}
          </h3>
          <p
            v-if="hoverText"
            class="text-white/85 text-center px-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-sm md:text-base"
          >
            {{
              hoverText.length > 310
                ? hoverText.substring(0, 310) + "..."
                : hoverText
            }}
          </p>
          <p
            class="flex justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300"
          >
            <Button color="primary" variant="solid" @click="$emit('moreInfo')">
              Saiba mais
            </Button>
          </p>
        </div>
      </div>
    </div>

    <!-- Informações do filme -->
    <div class="p-4 bg-white">
      <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
        {{ title.length > 30 ? title.substring(0, 35) + "..." : title }}
      </h3>
      <div class="flex justify-between items-center gap-2">
        <div class="flex items-center gap-2">
          <span class="text-yellow-500 font-bold">{{ rating }}</span>
          <span class="text-gray-500 text-sm">/ 10</span>
        </div>
        <div class="flex items-center gap-2">
          <span class="text-gray-500 text-sm">Ano: {{ year }}</span>
        </div>
      </div>
    </div>
  </div>
</template>
