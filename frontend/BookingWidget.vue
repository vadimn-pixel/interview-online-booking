<template>
  <div class="ob-widget">
    <h1 class="ob-title">
      Онлайн-запись
    </h1>

    <p
      v-if="errorMessage"
      class="ob-error"
    >
      {{ errorMessage }}
    </p>

    <step-service
      v-if="step === StepDict.SERVICE"
      :services="services"
      @select="selectService"
    />

    <step-employee
      v-else-if="step === StepDict.EMPLOYEE"
      :employees="employees"
      @select="selectEmployee"
    />

    <step-date-time
      v-else-if="step === StepDict.DATE_TIME"
      :available-slots="availableSlots"
      :is-loading="isLoading"
      @load:slots="loadSlots"
      @select="selectSlot"
    />

    <step-phone
      v-else-if="step === StepDict.PHONE"
      v-model:phone="phone"
      :is-loading="isLoading"
      @request:code="createHoldAndGetCode"
    />

    <step-code
      v-else-if="step === StepDict.CODE && booking"
      :hold-expires-at-utc="booking.hold_expires_at_utc"
      :is-loading="isLoading"
      @confirm="confirmCode"
      @resend="createHoldAndGetCode"
      @expired="deleteExpiredHold"
    />

    <step-done
      v-else-if="booking"
      :booking="booking"
    />
  </div>
</template>

<script setup lang="ts">
  import {onMounted, ref, shallowRef} from 'vue'
  import {ApiError, request} from './apiHelper'
  import StepCode from './steps/StepCode.vue'
  import StepDateTime from './steps/StepDateTime.vue'
  import StepDone from './steps/StepDone.vue'
  import StepEmployee from './steps/StepEmployee.vue'
  import StepPhone from './steps/StepPhone.vue'
  import StepService from './steps/StepService.vue'
  import {StepDict} from './types'
  import type {AvailabilityResponse, Booking, CreateHoldPayload, Employee, EmployeesResponse, Service, ServicesResponse, Step} from './types'

  const props = defineProps<{
    companyId: number
  }>()

  const services = shallowRef<Service[]>([])
  const employees = shallowRef<Employee[]>([])
  const availableSlots = shallowRef<string[]>([])

  const step = ref<Step>(StepDict.SERVICE)
  const selectedService = ref<Service | null>(null)
  const selectedEmployee = ref<Employee | null>(null)
  const selectedDate = ref<string | null>(null)
  const selectedSlot = ref<string | null>(null)
  const phone = ref<string>('')
  const booking = ref<Booking | null>(null)

  const isLoading = ref<boolean>(false)
  const errorMessage = ref<string | null>(null)

  const run = async (operation: () => Promise<void>): Promise<void> => {
    if (isLoading.value) return

    try {
      errorMessage.value = null
      isLoading.value = true
      await operation()
    } catch (error: unknown) {
      errorMessage.value = error instanceof ApiError ? error.message : 'Не удалось выполнить запрос'
    } finally {
      isLoading.value = false
    }
  }

  const loadServices = (): Promise<void> => run(async () => {
    const query = new URLSearchParams({company_id: String(props.companyId)})
    const response = await request<ServicesResponse>(`/steps/services?${query.toString()}`)

    services.value = response.services
  })

  const selectService = (service: Service): Promise<void> => run(async () => {
    const query = new URLSearchParams({service_id: String(service.id)})
    const path = `/steps/employees?${query.toString()}`

    selectedService.value = service
    employees.value = (await request<EmployeesResponse>(path)).employees
    step.value = StepDict.EMPLOYEE
  })

  const selectEmployee = (employee: Employee): void => {
    selectedEmployee.value = employee
    step.value = StepDict.DATE_TIME
  }

  const loadSlots = (date: string): Promise<void> => run(async () => {
    const query = new URLSearchParams({
      company_id: String(props.companyId),
      service_id: String(selectedService.value!.id),
      employee_id: String(selectedEmployee.value!.id),
      date,
    })

    const response = await request<AvailabilityResponse>(`/steps/date-time?${query.toString()}`)

    selectedDate.value = response.date
    availableSlots.value = response.slots
  })

  const selectSlot = (slot: string): void => {
    selectedSlot.value = slot
    step.value = StepDict.PHONE
  }

  const createHoldAndGetCode = (): Promise<void> => run(async () => {
    const payload: CreateHoldPayload = {
      company_id: props.companyId,
      service_id: selectedService.value!.id,
      employee_id: selectedEmployee.value!.id,
      price: selectedService.value!.price,
      starts_at_company_tz: `${selectedDate.value} ${selectedSlot.value}:00`,
      phone: phone.value,
    }

    booking.value = await request<Booking>('/steps/create-hold-and-get-code', {
      method: 'POST',
      body: JSON.stringify(payload),
    })

    step.value = StepDict.CODE
  })

  const deleteExpiredHold = (): Promise<void> => run(async () => {
    const query = new URLSearchParams({booking_id: String(booking.value!.booking_id)})

    await request<void>(`/steps/delete-expired-hold?${query.toString()}`, {method: 'DELETE'})

    booking.value = null
    availableSlots.value = []
    selectedDate.value = null
    selectedSlot.value = null
    step.value = StepDict.SERVICE
    errorMessage.value = 'Время на подтверждение истекло, начните запись заново'
  })

  const confirmCode = (code: string): Promise<void> => run(async () => {
    booking.value = await request<Booking>('/steps/confirm-code', {
      method: 'POST',
      body: JSON.stringify({booking_id: booking.value!.booking_id, code}),
    })

    step.value = StepDict.DONE
  })

  onMounted(loadServices)
</script>
