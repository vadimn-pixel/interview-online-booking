export interface Service {
  id: number
  name: string
  price: string
  duration_formatted: string
  employees_count: number
}

export interface ServicesResponse {
  services: Service[]
}

export interface Employee {
  id: number
  name: string
}

export interface EmployeesResponse {
  employees: Employee[]
}

export interface AvailabilityResponse {
  date: string
  slots: string[]
}

export interface Booking {
  booking_id: number
  starts_at_company_tz: string
  ends_at_company_tz: string
  hold_expires_at_utc: string
}

export interface CreateHoldPayload {
  company_id: number
  service_id: number
  employee_id: number
  price: string
  starts_at_company_tz: string
  phone: string
}

export const StepDict = {
  SERVICE: 'SERVICE',
  EMPLOYEE: 'EMPLOYEE',
  DATE_TIME: 'DATE_TIME',
  PHONE: 'PHONE',
  CODE: 'CODE',
  DONE: 'DONE',
} as const

export type Step = typeof StepDict[keyof typeof StepDict]
