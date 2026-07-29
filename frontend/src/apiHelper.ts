export class ApiError extends Error {
  // не важно
}

// @ts-ignore
export const requestJson = async <T>(path: string, init: RequestInit = {}): Promise<T> => {
  // fetch, разбор JSON, ApiError на неуспешный статус (не важно как)
}

// @ts-ignore
export const requestVoid = async (path: string, init: RequestInit = {}): Promise<void> => {
  // fetch, ApiError на неуспешный статус (не важно как)
}
