import { createRouter, createWebHistory } from "vue-router";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import Dashboard from "../views/Dashboard.vue";

// 1. Definisikan routes dulu
const routes = [
	{
		path: "/",
		name: "login",
		component: () => import("../views/Login.vue"),
		meta: { requiresAuth: false },
	},
	{
		path: "/register",
		name: "register",
		component: () => import("../views/Register.vue"),
		meta: { requiresAuth: false },
	},
	{
		path: "/dashboard",
		name: "dashboard",
		component: () => import("../views/Dashboard.vue"),
		meta: { requiresAuth: true },
	},
];

// 2. Buat instance router
const router = createRouter({
	history: createWebHistory(),
	routes,
});

router.beforeEach((to, from, next) => {
	const token = localStorage.getItem("token");
	const isAuthenticated = !!token;

	// 1. Kasus: Mau ke halaman yang WAJIB LOGIN (Dashboard, dll)
	if (to.meta.requiresAuth && !isAuthenticated) {
		console.warn("Akses ditolak! Kamu harus login dulu.");
		return next({ name: "login" });
	}

	// 2. Kasus: SUDAH LOGIN, tapi coba buka Login atau Register
	// Kita tendang balik ke Dashboard karena dia sudah punya sesi
	if (isAuthenticated && (to.name === "login" || to.name === "register")) {
		return next({ name: "dashboard" });
	}

	// 3. Kasus: Selain itu (Buka Register pas belum login, atau buka halaman publik)
	// SILAKAN LEWAT!
	next();
});
export default router;
