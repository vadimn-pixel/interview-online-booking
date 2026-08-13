<template>
  <section class="ob-step">
    <h2 class="ob-step__title">
      Выберите сотрудника
    </h2>

    <p
      v-if="employees.length === 0"
      class="ob-step__hint"
    >
      Никто из сотрудников не оказывает эту услугу
    </p>

    <button
      v-for="employee in employees"
      :key="employee.id"
      class="ob-button"
      type="button"
      @click="$emit('select', employee)"
    >
      {{ employee.name }}
    </button>
  </section>
</template>

<script setup lang="ts">
  import type {Employee} from '../types'

  defineProps<{
    employees: Employee[]
  }>()

  defineEmits<{
    select: [employee: Employee]
  }>()
</script>
