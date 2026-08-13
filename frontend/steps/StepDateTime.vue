<template>
  <section class="ob-step">
    <h2 class="ob-step__title">
      Выберите дату и время
    </h2>

    <input
      v-model="date"
      class="ob-input"
      type="date"
      @change="$emit('load:slots', date)"
    >

    <p
      v-if="isLoading"
      class="ob-step__hint"
    >
      Загружаем свободное время
    </p>

    <p
      v-else-if="date !== '' && availableSlots.length === 0"
      class="ob-step__hint"
    >
      На эту дату свободного времени нет
    </p>

    <div
      v-else
      class="ob-slots"
    >
      <button
        v-for="slot in availableSlots"
        :key="slot"
        class="ob-button"
        type="button"
        @click="$emit('select', slot)"
      >
        {{ slot }}
      </button>
    </div>
  </section>
</template>

<script setup lang="ts">
  import {ref} from 'vue'

  defineProps<{
    availableSlots: string[]
    isLoading: boolean
  }>()

  defineEmits<{
    'load:slots': [date: string]
    select: [slot: string]
  }>()

  const date = ref<string>('')
</script>
