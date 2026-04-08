<template>
    <div class="flex items-center justify-center min-h-screen px-4 bg-gray-100">
        <div class="w-full max-w-md p-8 bg-white shadow-xl rounded-2xl">
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-green-600">FinansialKu</h1>
                <p class="mt-2 text-gray-500">Silakan Registrasi Akun Kamu</p>
            </div>
            <form @submit.prevent="handleRegister" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input v-model="form.name" type="text" class="w-full p-2 mt-1 border rounded-lg outline-none focus:ring-2 focus:ring-green-500" placeholder="Isi Nama Kamu" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input v-model="form.email" type="email" class="w-full p-2 mt-1 border rounded-lg outline-none focus:ring-2 focus:ring-green-500" placeholder="fajar@example.com" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input v-model="form.password" type="password" class="w-full p-2 mt-1 border rounded-lg outline-none focus:ring-2 focus:ring-green-500" placeholder="Min. 8 karakter" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <input v-model="form.password_confirmation" type="password" class="w-full p-2 mt-1 border rounded-lg outline-none focus:ring-2 focus:ring-green-500" placeholder="Ulangi password" required />
                </div>

                <button type="submit" :disabled="loading" class="w-full py-3 font-bold text-white transition bg-green-600 rounded-lg shadow-md hover:bg-green-700 disabled:bg-gray-400">
                    {{ loading ? 'Mendaftarkan...' : 'Daftar & Masuk' }}
                </button>
            </form>

            <p class="mt-4 text-sm text-center text-gray-600">
                Sudah punya akun? <router-link to="/" class="font-bold text-green-600 hover:underline">Login</router-link>
            </p>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../api'
import Swal from 'sweetalert2'

const router = useRouter()
const loading = ref(false)

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '' // Laravel butuh ini untuk validasi 'confirmed'
})

const handleRegister = async () => {
    loading.value = true
    try {
        const response = await api.post('/register', form)

        // Ambil token dari hasil register agar user langsung login
        const token = response.data.access_token || response.data.token || response.data.data?.token

        if (token) {
            localStorage.setItem('token', token)
            api.defaults.headers.common['Authorization'] = `Bearer ${token}`

            await Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Akun berhasil dibuat. Selamat datang!',
                timer: 2000,
                showConfirmButton: false
            })

            router.push('/dashboard')
        }
    } catch (error) {
        console.error(error)
        Swal.fire({
            icon: 'error',
            title: 'Registrasi Gagal',
            text: error.response?.data?.message || 'Pastikan data yang diisi benar.'
        })
    } finally {
        loading.value = false
    }
}
</script>