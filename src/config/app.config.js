export const appConfig = Object.freeze({
  API_URL: import.meta.env.VITE_API_URL || "http://127.0.0.1:8000/api/",
  APP_BASE_URL: import.meta.env.VITE_BASE_URL || "http://localhost:5173/",
  IMG_URL: import.meta.env.VITE_IMG_URL || "http://127.0.0.1:8000/images/",
});

