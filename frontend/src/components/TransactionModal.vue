<script setup>
import { ref, watch, defineProps, defineEmits } from 'vue';

const props = defineProps(['isOpen', 'categories', 'editData']);
const emit = defineEmits(['close', 'save']);

const form = ref({
    description: '',
    amount: '',
    type: 'expense',
    category_id: ''
});

// Helper: Format tampilan ke Rupiah (Titik-titik)
const formatDisplay = (val) => {
    if (!val) return "";
    let str = val.toString().replace(/\D/g, "");
    return str.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};

// Handle Input: Simpan angka murni ke state, tapi tampilkan titik di UI
const handleAmountInput = (e) => {
    let rawValue = e.target.value.replace(/\D/g, "");
    form.value.amount = rawValue;
};

watch(() => props.isOpen, (newVal) => {
    if (newVal) {
        if (props.editData) {
            form.value = {
                description: props.editData.description || '',
                amount: props.editData.amount?.toString() || '',
                type: props.editData.type || 'expense',
                category_id: props.editData.category_id || ''
            };
        } else {
            form.value = { description: '', amount: '', type: 'expense', category_id: '' };
        }
    }
}, { immediate: true });

const handleSubmit = () => {
    if (!form.value.amount || !form.value.category_id || !form.value.description) {
        alert("Mohon lengkapi semua data!");
        return;
    }

    const payload = {
        ...form.value,
        amount: Number(form.value.amount)
    };

    emit('save', payload);
};
</script>

<template>
    <Transition name="fade">
        <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center p-4 z-60 bg-gray-900/60 backdrop-blur-sm">
            <div class="w-full max-w-md overflow-hidden bg-white shadow-2xl rounded-3xl animate-slide-up">

                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                    <h2 class="flex items-center gap-2 text-xl font-bold text-gray-800">
                        <span>{{ editData ? '📝' : '💰' }}</span>
                        {{ editData ? 'Edit Transaksi' : 'Tambah Transaksi' }}
                    </h2>
                    <button @click="$emit('close')" class="p-2 text-gray-400 transition-colors rounded-full hover:bg-gray-200 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-5">
                    <div>
                        <label class="block mb-2 text-sm font-bold text-gray-700">Keterangan</label>
                        <input v-model="form.description" type="text" placeholder="Misal: Beli Semen / Gaji" class="w-full px-4 py-3 transition-all border border-gray-200 outline-none bg-gray-50 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:bg-white" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nominal</label>
                        <input v-model="form.amount" type="number" min="1" required placeholder="Contoh: 50000" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700">Tipe</label>
                            <select v-model="form.type" :class="form.type === 'income' ? 'text-green-600 border-green-100 bg-green-50' : 'text-red-600 bg-red-50'" class="w-full px-3 py-3 font-bold transition-all border outline-none appearance-none cursor-pointer rounded-2xl focus:ring-2 focus:ring-blue-500">
                                <option value="income">📈 Pemasukan</option>
                                <option value="expense">📉 Pengeluaran</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-bold text-gray-700">Kategori</label>
                            <select v-model="form.category_id" class="w-full px-3 py-3 transition-all border border-gray-200 outline-none appearance-none cursor-pointer bg-gray-50 rounded-2xl focus:ring-2 focus:ring-blue-500">
                                <option value="" disabled>Pilih Kategori</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                    {{ cat.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 p-6 border-t border-gray-100 bg-gray-50/50">
                    <button @click="$emit('close')" class="flex-1 px-4 py-3 font-bold text-gray-500 transition-all rounded-2xl hover:bg-gray-200">
                        Batal
                    </button>
                    <button @click="handleSubmit" class="px-6 py-3 font-bold text-white transition-all bg-blue-600 flex-2 rounded-2xl hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-200 active:scale-95">
                        {{ editData ? 'Simpan Perubahan' : 'Simpan Transaksi' }}
                    </button>
                </div>
            </div>
        </div>
    </Transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

@keyframes slide-up {
    from {
        transform: translateY(20px);
        opacity: 0;
    }

    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.animate-slide-up {
    animation: slide-up 0.4s ease-out;
}
</style>