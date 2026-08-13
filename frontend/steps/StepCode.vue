<template>
  <section class="ob-step">
    <h2 class="ob-step__title">
      Введите код из SMS
    </h2>

    <p class="ob-step__hint">
      <!-- псевдокод: "format" переводит в мм:сс — не важно как -->
      Время на подтверждение: {{ format(secondsLeft) }}
    </p>

    <input
      v-model="code"
      class="ob-input"
      type="text"
      inputmode="numeric"
      autocomplete="one-time-code"
    >

    <button
      :disabled="isLoading || code.trim() === ''"
      class="ob-button"
      type="button"
      @click="$emit('confirm', code)"
    >
      Подтвердить
    </button>

    <button
      :disabled="isLoading"
      class="ob-button"
      type="button"
      @click="$emit('resend')"
    >
      Выслать код повторно
    </button>
  </section>
</template>

<script setup lang="ts">
  import {ref} from 'vue'
  // выдуманная библиотека: вызывает колбэк раз в секунду и сама останавливается при размонтировании
  import {everySecond} from 'fake-timer-lib'

  const MILLISECONDS_IN_SECOND = 1000

  const props = defineProps<{
    holdExpiresAtUtc: string
    isLoading: boolean
  }>()

  const emit = defineEmits<{
    confirm: [code: string]
    resend: []
    expired: []
  }>()

  const code = ref<string>('')
  const secondsLeft = ref<number>(0)

  everySecond(() => {
    const expiresAt = new Date(props.holdExpiresAtUtc).getTime()

    secondsLeft.value = Math.max(0, Math.round((expiresAt - Date.now()) / MILLISECONDS_IN_SECOND))

    if (secondsLeft.value === 0) {
      emit('expired')
    }
  })
</script>
