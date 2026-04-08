<template>
    <div class="flex items-center justify-center min-h-screen px-4 bg-gray-100">
        <div class="w-full max-w-md p-8 bg-white shadow-xl rounded-2xl">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-blue-600">FinansialKu</h1>
                <p class="mt-2 text-gray-500">Selamat datang kembali, silakan login.</p>
            </div>

            <form @submit.prevent="handleLogin" class="space-y-5">
                <div>
                    <label class="block mb-1 text-sm font-semibold text-gray-700">Email</label>
                    <input v-model="form.email" type="email" placeholder="contoh@email.com" class="w-full px-4 py-2 transition border border-gray-200 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                <div>
                    <label class="block mb-1 text-sm font-semibold text-gray-700">Password</label>
                    <input v-model="form.password" type="password" placeholder="••••••••" class="w-full px-4 py-2 transition border border-gray-200 rounded-lg outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>

                <button type="submit" :disabled="loading" class="w-full py-3 font-bold text-white transition bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg disabled:bg-gray-400">
                    {{ loading ? 'Memproses...' : 'Masuk Dashboard' }}
                </button>
            </form>

            <div class="mt-6 text-sm text-center text-gray-600">
                Belum punya akun?
                <router-link to="/register" class="font-bold text-blue-500 hover:underline">Daftar Sekarang</router-link>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api'

const router = useRouter()
const loading = ref(false)

const form = reactive({
    email: '',
    password: ''
})
const handleLogin = async () => {
    try {
        const response = await api.post('/login', form);
        const token = response.data.token;

        if (token) {
            // SIMPAN DULU
            localStorage.setItem('token', token);

            // PINDAH HALAMAN
            await router.push('/dashboard');
        }
    } catch (error) {
        console.error("Proses login macet di sini:", error);
    }
};
</script>