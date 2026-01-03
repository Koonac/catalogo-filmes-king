<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    :class="buttonClasses"
    @click="$emit('click', $event)"
  >
    <span v-if="loading" class="inline-block mr-2">
      <svg
        class="animate-spin h-4 w-4"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
      >
        <circle
          class="opacity-25"
          cx="12"
          cy="12"
          r="10"
          stroke="currentColor"
          stroke-width="4"
        ></circle>
        <path
          class="opacity-75"
          fill="currentColor"
          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
        ></path>
      </svg>
    </span>
    <slot></slot>
  </button>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  color: {
    type: String,
    default: "primary",
    validator: (value) =>
      ["primary", "green", "red", "white", "black"].includes(value),
  },
  size: {
    type: String,
    default: "md",
    validator: (value) => ["sm", "md", "lg"].includes(value),
  },
  variant: {
    type: String,
    default: "solid",
    validator: (value) => ["solid", "outline"].includes(value),
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  loading: {
    type: Boolean,
    default: false,
  },
  type: {
    type: String,
    default: "button",
  },
  fullWidth: {
    type: Boolean,
    default: false,
  },
});

defineEmits(["click"]);

const buttonClasses = computed(() => {
  const baseClasses =
    "inline-flex items-center justify-center font-medium rounded-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed";

  const sizeClasses = {
    sm: "px-3 py-1.5 text-sm",
    md: "px-4 py-2 text-base",
    lg: "px-6 py-3 text-lg",
  };

  const widthClass = props.fullWidth ? "w-full" : "";

  const colorClasses = getColorClasses(props.color, props.variant);

  return `${baseClasses} ${
    sizeClasses[props.size]
  } ${colorClasses} ${widthClass}`;
});

function getColorClasses(color, variant) {
  const colors = {
    primary: {
      solid:
        "bg-gradient-to-r from-[rgb(120,45,200)] to-[rgb(72,27,120)] text-white hover:from-[rgb(130,55,210)] hover:to-[rgb(82,37,130)] focus:ring-[rgb(120,45,200)] shadow-md hover:shadow-lg",
      outline:
        "border-2 border-[rgb(120,45,200)] text-[rgb(120,45,200)] bg-transparent hover:bg-[rgb(120,45,200)] hover:text-white focus:ring-[rgb(120,45,200)]",
    },
    green: {
      solid:
        "bg-green-600 text-white hover:bg-green-700 focus:ring-green-500 shadow-md hover:shadow-lg",
      outline:
        "border-2 border-green-600 text-green-600 bg-transparent hover:bg-green-600 hover:text-white focus:ring-green-500",
    },
    red: {
      solid:
        "bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 shadow-md hover:shadow-lg",
      outline:
        "border-2 border-red-600 text-red-600 bg-transparent hover:bg-red-600 hover:text-white focus:ring-red-500",
    },
    white: {
      solid:
        "bg-white text-gray-900 border border-gray-300 hover:bg-gray-50 focus:ring-gray-500 shadow-md hover:shadow-lg",
      outline:
        "border-2 border-white text-white bg-transparent hover:bg-white hover:text-gray-900 focus:ring-white",
    },
    black: {
      solid:
        "bg-gray-900 text-white hover:bg-black focus:ring-gray-900 shadow-md hover:shadow-lg",
      outline:
        "border-2 border-gray-900 text-gray-900 bg-transparent hover:bg-gray-900 hover:text-white focus:ring-gray-900",
    },
  };

  return colors[color][variant];
}
</script>
