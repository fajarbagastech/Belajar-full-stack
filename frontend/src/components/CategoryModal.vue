<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="w-full max-w-md p-6 bg-white shadow-xl rounded-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">Manajemen Kategori</h3>
                <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <div class="flex gap-2 mb-6">
                <input v-model="categoryName" type="text" placeholder="Nama kategori..." class="flex-1 px-3 py-2 text-sm border rounded-lg outline-none focus:ring-2 focus:ring-blue-500" />
                <button @click="submitCategory" class="px-4 py-2 text-sm font-bold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    {{ editingId ? 'Simpan' : 'Tambah' }}
                </button>
            </div>

            <div class="overflow-y-auto max-h-60">
                <div v-for="cat in categories" :key="cat.id" class="flex items-center justify-between p-3 border-b border-gray-100 rounded-lg hover:bg-gray-50 last:border-0">
                    <span class="text-sm text-gray-700">{{ cat.name }}</span>
                    <div class="flex gap-1">
                        <button @click="startEdit(cat)" class="p-1.5 text-blue-500 hover:bg-blue-50 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>
                        <button @click="$emit('delete', cat.id)" class="p-1.5 text-red-500 hover:bg-red-50 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps(['isOpen', 'categories']);
const emit = defineEmits(['close', 'save', 'delete']);

const categoryName = ref('');
const editingId = ref(null);

const startEdit = (cat) => {
    categoryName.value = cat.name;
    editingId.value = cat.id;
};

const submitCategory = () => {
    if (!categoryName.value.trim()) return;

    // Kirim data ke Dashboard.vue
    emit('save', {
        id: editingId.value,
        name: categoryName.value
    });

    // Reset form setelah kirim
    categoryName.value = '';
    editingId.value = null;
};
</script>