export class ApiError extends Error {
  // не важно
}

// @ts-ignore
export const request = async <T>(path: string, init: RequestInit = {}): Promise<T> => {
    // не важно
}
