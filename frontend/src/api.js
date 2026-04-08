import axios from "axios";

const api = axios.create({
	// Sesuaikan dengan URL API Laravel kamu
	baseURL: "http://localhost:8000/api",
	timeout: 10000, // Maksimal nunggu respons 10 detik
});

/**
 * 1. REQUEST INTERCEPTOR
 * Fungsinya: Sebelum request terkirim ke server,
 * cek LocalStorage, kalau ada token, tempelkan di Header.
 */
api.interceptors.request.use(
	(config) => {
		const token = localStorage.getItem("token");
		if (token) {
			config.headers.Authorization = `Bearer ${token}`;
		}
		return config;
	},
	(error) => {
		return Promise.reject(error);
	},
);

/**
 * 2. RESPONSE INTERCEPTOR
 * Fungsinya: Mencegat respons dari server sebelum sampai ke komponen.
 * Kalau server bilang 401 (Unauthorized), otomatis Logout.
 */
api.interceptors.response.use(
	(response) => {
		// Jika request sukses, langsung teruskan data
		return response;
	},
	(error) => {
		// Jika token mati atau tidak valid (401)
		if (error.response && error.response.status === 401) {
			console.warn("Sesi habis, mengarahkan ke Login...");

			// Bersihkan storage
			localStorage.removeItem("token");
			localStorage.removeItem("user_name");

			// Tendang ke halaman login (refresh bersih)
			window.location.href = "/";
		}
		return Promise.reject(error);
	},
);

export default api;
