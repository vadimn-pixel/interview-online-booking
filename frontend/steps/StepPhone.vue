<template>
  <section class="ob-step">
    <h2 class="ob-step__title">
      Введите номер телефона
    </h2>

    <input
      v-model="phone"
      class="ob-input"
      type="tel"
      placeholder="+7 999 123-45-67"
    >

    <button
      :disabled="isLoading || phone.trim() === ''"
      class="ob-button"
      type="button"
      @click="$emit('request:code')"
    >
      Получить SMS
    </button>
  </section>
</template>

<script setup lang="ts">
  defineProps<{
    isLoading: boolean
  }>()

  defineEmits<{
    'request:code': []
  }>()

  const phone = defineModel<string>('phone', {required: true})
</script>
