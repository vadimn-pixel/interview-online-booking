<template>
  <section class="ob-step">
    <h2 class="ob-step__title">
      Запись подтверждена
    </h2>

    <dl class="ob-details">
      <dt>Начало</dt>

      <dd>{{ booking.starts_at_company_tz }}</dd>

      <dt>Окончание</dt>

      <dd>{{ booking.ends_at_company_tz }}</dd>
    </dl>
  </section>
</template>

<script setup lang="ts">
  import type {Booking} from '../types'

  defineProps<{
    booking: Booking
  }>()
</script>
