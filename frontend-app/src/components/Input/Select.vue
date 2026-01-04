<script setup>
import { computed } from "vue";
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

const props = defineProps({
  modelValue: {
    type: [String, Number, Object, Array],
    default: null,
  },
  options: {
    type: Array,
    default: () => [],
  },
  label: {
    type: String,
    default: "",
  },
  placeholder: {
    type: String,
    default: "Selecione uma opção",
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  required: {
    type: Boolean,
    default: false,
  },
  error: {
    type: String,
    default: "",
  },
  hint: {
    type: String,
    default: "",
  },
  size: {
    type: String,
    default: "md",
    validator: (value) => ["sm", "md", "lg"].includes(value),
  },
  multiple: {
    type: Boolean,
    default: false,
  },
  clearable: {
    type: Boolean,
    default: false,
  },
  searchable: {
    type: Boolean,
    default: true,
  },
  labelKey: {
    type: String,
    default: "label",
  },
  valueKey: {
    type: String,
    default: "value",
  },
});

const emit = defineEmits(["update:modelValue", "blur", "focus"]);

const selectId = computed(
  () => `select-${Math.random().toString(36).substr(2, 9)}`
);

const selectClasses = computed(() => {
  const sizeClasses = {
    sm: "text-sm",
    md: "text-base",
    lg: "text-lg",
  };

  const classes = [sizeClasses[props.size]];

  if (props.error) {
    classes.push("error");
  }

  return classes.join(" ");
});

const handleInput = (value) => {
  emit("update:modelValue", value);
};

const handleBlur = (event) => {
  emit("blur", event);
};

const handleFocus = (event) => {
  emit("focus", event);
};
</script>

<template>
  <div class="w-full">
    <label
      v-if="label"
      :for="selectId"
      class="block text-sm font-medium text-gray-700 mb-2"
    >
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <v-select
      :id="selectId"
      :model-value="modelValue"
      :options="options"
      :placeholder="placeholder"
      :disabled="disabled"
      :multiple="multiple"
      :clearable="clearable"
      :searchable="searchable"
      :label="labelKey"
      :reduce="
        (option) => (typeof option === 'object' ? option[valueKey] : option)
      "
      :class="selectClasses"
      class="vue-select-custom"
      @update:model-value="handleInput"
      @blur="handleBlur"
      @focus="handleFocus"
    />
    <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    <p v-if="hint && !error" class="mt-1 text-sm text-gray-500">{{ hint }}</p>
  </div>
</template>

<style scoped>
:deep(.vue-select-custom) {
  --vs-border-color: rgb(209, 213, 219);
  --vs-border-radius: 0.375rem;
  --vs-font-size: inherit;
  --vs-line-height: inherit;
}

:deep(.vue-select-custom .vs__dropdown-toggle) {
  border: 1px solid var(--vs-border-color);
  border-radius: var(--vs-border-radius);
  transition: all 0.2s;
  min-height: 1.75rem;
}

:deep(.vue-select-custom .vs__dropdown-toggle:focus-within) {
  outline: none;
  border-color: rgb(120, 45, 200);
  box-shadow: 0 0 0 2px rgba(120, 45, 200, 0.1);
}

:deep(.vue-select-custom.vs--disabled .vs__dropdown-toggle) {
  background-color: rgb(243, 244, 246);
  cursor: not-allowed;
  opacity: 0.6;
}

:deep(.vue-select-custom.vs--open .vs__dropdown-toggle) {
  border-color: rgb(120, 45, 200);
}

:deep(.vue-select-custom .vs__selected-options) {
  padding: 0.25rem;
}

:deep(.vue-select-custom .vs__search) {
  font-size: inherit;
  line-height: inherit;
}

:deep(.vue-select-custom .vs__actions) {
  padding: 0.25rem;
}

:deep(.vue-select-custom .vs__clear) {
  fill: rgb(107, 114, 128);
}

:deep(.vue-select-custom .vs__open-indicator) {
  fill: rgb(107, 114, 128);
}

:deep(.vue-select-custom .vs__dropdown-menu) {
  border: 1px solid var(--vs-border-color);
  border-radius: var(--vs-border-radius);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
    0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

:deep(.vue-select-custom .vs__dropdown-option) {
  padding: 0.5rem 0.75rem;
  transition: background-color 0.15s;
}

:deep(.vue-select-custom .vs__dropdown-option--highlight) {
  background-color: rgb(120, 45, 200);
  color: white;
}

:deep(.vue-select-custom .vs__dropdown-option--selected) {
  background-color: rgba(120, 45, 200, 0.1);
  color: rgb(120, 45, 200);
  font-weight: 500;
}

/* Tamanhos */
:deep(.vue-select-custom.text-sm .vs__dropdown-toggle) {
  min-height: 1.5rem;
  padding: 0.125rem;
}

:deep(.vue-select-custom.text-sm .vs__selected-options),
:deep(.vue-select-custom.text-sm .vs__actions) {
  padding: 0.125rem;
}

:deep(.vue-select-custom.text-lg .vs__dropdown-toggle) {
  min-height: 2rem;
  padding: 0.375rem;
}

:deep(.vue-select-custom.text-lg .vs__selected-options),
:deep(.vue-select-custom.text-lg .vs__actions) {
  padding: 0.375rem;
}

/* Estado de erro */
:deep(.vue-select-custom.error .vs__dropdown-toggle) {
  border-color: rgb(239, 68, 68);
}

:deep(.vue-select-custom.error .vs__dropdown-toggle:focus-within) {
  border-color: rgb(239, 68, 68);
  box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.1);
}
</style>
