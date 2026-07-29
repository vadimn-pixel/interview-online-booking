<template>
  <section class="ob-step">
    <h2 class="ob-step__title">
      Выберите услугу
    </h2>

    <button
      v-for="service in services"
      :key="service.id"
      class="ob-button ob-button--row"
      type="button"
      @click="$emit('select', service)"
    >
      <span>{{ service.name }}</span>

      <span>{{ service.duration_formatted }} · {{ service.price }} · мастеров: {{ service.employees_count }}</span>
    </button>
  </section>
</template>

<script setup lang="ts">
  import type {Service} from '../types'

  defineProps<{
    services: Service[]
  }>()

  defineEmits<{
    select: [service: Service]
  }>()
</script>
