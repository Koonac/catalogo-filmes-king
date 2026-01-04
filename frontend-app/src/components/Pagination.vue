<script setup>
import { computed } from "vue";
import { Icon } from "@iconify/vue";

const props = defineProps({
  currentPage: {
    type: Number,
    required: true,
    default: 1,
  },
  totalPages: {
    type: Number,
    required: true,
    default: 1,
  },
  maxVisiblePages: {
    type: Number,
    default: 5,
  },
});

const emit = defineEmits(["page-change"]);

const visiblePages = computed(() => {
  const pages = [];
  const { currentPage, totalPages, maxVisiblePages } = props;

  if (totalPages <= maxVisiblePages) {
    // Se o total de páginas for menor que o máximo visível, mostra todas
    for (let i = 1; i <= totalPages; i++) {
      pages.push(i);
    }
  } else {
    // Calcula o range de páginas visíveis
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

    // Ajusta o início se estiver muito próximo do final
    if (endPage - startPage < maxVisiblePages - 1) {
      startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    // Adiciona primeira página se não estiver no range
    if (startPage > 1) {
      pages.push(1);
      if (startPage > 2) {
        pages.push("ellipsis-start");
      }
    }

    // Adiciona páginas do range
    for (let i = startPage; i <= endPage; i++) {
      pages.push(i);
    }

    // Adiciona última página se não estiver no range
    if (endPage < totalPages) {
      if (endPage < totalPages - 1) {
        pages.push("ellipsis-end");
      }
      pages.push(totalPages);
    }
  }

  return pages;
});

const canGoPrevious = computed(() => props.currentPage > 1);
const canGoNext = computed(() => props.currentPage < props.totalPages);

const goToPage = (page) => {
  if (page >= 1 && page <= props.totalPages && page !== props.currentPage) {
    emit("page-change", page);
  }
};

const goToPrevious = () => {
  if (canGoPrevious.value) {
    goToPage(props.currentPage - 1);
  }
};

const goToNext = () => {
  if (canGoNext.value) {
    goToPage(props.currentPage + 1);
  }
};
</script>

<template>
  <div
    v-if="totalPages > 1"
    class="flex items-center justify-center gap-2 mt-6"
  >
    <!-- Botão Anterior -->
    <button
      @click="goToPrevious"
      :disabled="!canGoPrevious"
      class="flex items-center justify-center w-10 h-10 rounded-md border-2 border-gray-300 text-gray-700 bg-white hover:bg-gray-50 hover:border-[rgb(120,45,200)] hover:text-[rgb(120,45,200)] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:border-gray-300 disabled:hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[rgb(120,45,200)]"
      aria-label="Página anterior"
    >
      <Icon icon="mdi:chevron-left" class="w-5 h-5" />
    </button>

    <!-- Números das páginas -->
    <div class="flex items-center gap-1">
      <button
        v-for="page in visiblePages"
        :key="page"
        @click="typeof page === 'number' && goToPage(page)"
        :disabled="typeof page === 'string'"
        :class="[
          'flex items-center justify-center min-w-10 h-10 px-3 rounded-md font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[rgb(120,45,200)]',
          typeof page === 'number'
            ? page === currentPage
              ? 'bg-gradient-to-r from-[rgb(120,45,200)] to-[rgb(72,27,120)] text-white shadow-md'
              : 'border-2 border-gray-300 text-gray-700 bg-white hover:bg-gray-50 hover:border-[rgb(120,45,200)] hover:text-[rgb(120,45,200)] cursor-pointer'
            : 'text-gray-500 cursor-default pointer-events-none',
        ]"
        :aria-label="typeof page === 'number' ? `Ir para página ${page}` : ''"
        :aria-current="page === currentPage ? 'page' : undefined"
      >
        <span v-if="typeof page === 'number'">{{ page }}</span>
        <Icon
          v-else-if="page === 'ellipsis-start' || page === 'ellipsis-end'"
          icon="mdi:dots-horizontal"
          class="w-5 h-5"
        />
      </button>
    </div>

    <!-- Botão Próximo -->
    <button
      @click="goToNext"
      :disabled="!canGoNext"
      class="flex items-center justify-center w-10 h-10 rounded-md border-2 border-gray-300 text-gray-700 bg-white hover:bg-gray-50 hover:border-[rgb(120,45,200)] hover:text-[rgb(120,45,200)] transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:border-gray-300 disabled:hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[rgb(120,45,200)]"
      aria-label="Próxima página"
    >
      <Icon icon="mdi:chevron-right" class="w-5 h-5" />
    </button>
  </div>
</template>
