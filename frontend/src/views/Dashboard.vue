<template>
    <div v-if="loading">
        <SkeletonTable />
    </div>

    <div v-else class="min-h-screen bg-[#f8fafc]">

        <nav class="sticky top-0 z-30 border-b border-gray-100 bg-white/80 backdrop-blur-md">
            <div class="max-w-6xl px-4 mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 bg-blue-600 shadow-lg rounded-xl shadow-blue-200">
                            <span class="text-xl font-black text-white">F</span>
                        </div>
                        <h1 class="hidden text-xl font-black tracking-tighter text-gray-900 sm:block">FINANCE.CO</h1>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="hidden text-right sm:block">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none">Status: Premium</p>
                            <p class="text-sm font-bold text-gray-800 capitalize">{{ user.name }}</p>
                        </div>
                        <button @click="handleLogout" class="p-2 text-gray-400 transition-colors rounded-lg hover:text-red-500 hover:bg-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-6xl p-4 mx-auto md:p-8">

            <div class="mb-8">
                <h1 class="text-2xl font-black tracking-tight text-gray-900 md:text-4xl">
                    Ringkasan <span class="text-blue-600">Keuangan</span>
                </h1>
                <p class="text-sm font-medium text-gray-500 md:text-base">Halo, <span class="font-bold text-gray-900 capitalize">{{ user.name }}</span>! Pantau arus kasmu hari ini. 👋</p>
            </div>

            <div class="grid grid-cols-1 gap-4 mb-10 md:gap-6 md:grid-cols-3">
                <div class="relative p-6 overflow-hidden transition-shadow bg-white border border-gray-100 shadow-sm rounded-3xl group hover:shadow-md">
                    <div class="absolute top-0 right-0 p-4 transition-transform opacity-10 group-hover:scale-110">
                        <svg class="w-12 h-12 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                            <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Total Saldo</p>
                    <h2 class="text-2xl font-black text-gray-800 truncate md:text-3xl">{{ formatRupiah(summary.balance) }}</h2>
                    <div class="w-full h-1 mt-4 overflow-hidden rounded-full bg-blue-50">
                        <div class="w-full h-full bg-blue-500"></div>
                    </div>
                </div>

                <div class="relative p-6 overflow-hidden transition-shadow bg-white border border-gray-100 shadow-sm rounded-3xl group hover:shadow-md">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Pemasukan</p>
                    <h2 class="text-2xl font-black text-green-600 truncate md:text-3xl">{{ formatRupiah(summary.total_income) }}</h2>
                    <div class="w-full h-1 mt-4 overflow-hidden rounded-full bg-green-50">
                        <div class="w-2/3 h-full bg-green-500"></div>
                    </div>
                </div>

                <div class="relative p-6 overflow-hidden transition-shadow bg-white border border-gray-100 shadow-sm rounded-3xl group hover:shadow-md">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Pengeluaran</p>
                    <h2 class="text-2xl font-black text-red-600 truncate md:text-3xl">{{ formatRupiah(summary.total_expense) }}</h2>
                    <div class="w-full h-1 mt-4 overflow-hidden rounded-full bg-red-50">
                        <div class="w-1/3 h-full bg-red-500"></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 mb-10 lg:grid-cols-2">
                <div class="p-6 bg-white border shadow-sm border-gray-50 rounded-3xl">
                    <h3 class="flex items-center gap-2 mb-6 font-black text-gray-800">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span> Distribusi Pengeluaran
                    </h3>
                    <div v-if="allTransactionsForChart.length > 0">
                        <apexchart type="donut" height="320" :options="donutOptions" :series="chartSeries" />
                    </div>
                    <div v-else class="py-20 font-medium text-center text-gray-300">Belum ada data grafik</div>
                </div>

                <div class="p-6 bg-white border shadow-sm border-gray-50 rounded-3xl">
                    <h3 class="flex items-center gap-2 mb-6 font-black text-gray-800">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span> Tren 7 Hari Terakhir
                    </h3>
                    <div v-if="allTransactionsForChart.length > 0">
                        <apexchart type="area" height="320" :options="lineOptions" :series="trendSeries" />
                    </div>
                    <div v-else class="py-20 font-medium text-center text-gray-300">Belum ada data tren</div>
                </div>
            </div>

            <div class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-3xl">
                <div class="gap-4 p-6 space-y-4 border-b border-gray-50 md:space-y-0 md:flex md:items-center md:justify-between">
                    <h3 class="text-lg font-black text-gray-800">Transaksi Terakhir</h3>

                    <div class="flex flex-col flex-1 max-w-2xl gap-3 md:flex-row">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input id="search_transactions" v-model="searchQuery" type="text" placeholder="Cari apapun..." class="w-full py-2.5 pl-10 pr-4 text-sm bg-gray-50 border-none rounded-xl focus:ring-2 focus:ring-blue-500/10 transition-all outline-none" />
                        </div>
                        <div class="flex gap-2">
                            <button @click="openAddCategory" class="flex-1 md:flex-none px-4 py-2.5 text-xs font-bold text-gray-600 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                + Kategori
                            </button>
                            <button @click="openAddTransaction" class="flex-1 md:flex-none px-4 py-2.5 text-xs font-bold text-white bg-blue-600 rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
                                + Transaksi
                            </button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-600px md:min-w-full">
                        <thead class="text-[10px] font-black text-gray-400 uppercase tracking-widest bg-gray-50/50">
                            <tr>
                                <th class="px-6 py-4">Deskripsi</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Tipe</th>
                                <th class="px-6 py-4 text-right">Jumlah</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-if="filteredTransactions.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <p class="italic text-gray-400">Tidak ada transaksi ditemukan.</p>
                                </td>
                            </tr>

                            <tr v-for="trx in filteredTransactions" :key="trx.id" class="transition-colors group hover:bg-blue-50/30">
                                <td class="px-6 py-5">
                                    <p class="font-bold leading-tight text-gray-800">{{ trx.description }}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ new Date(trx.created_at).toLocaleDateString() }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-3 py-1 text-[10px] font-bold text-blue-600 uppercase bg-blue-50 rounded-lg">
                                        {{ trx.category?.name || 'Umum' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <span :class="trx.type === 'income' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600'" class="px-3 py-1 text-[10px] font-black uppercase rounded-lg">
                                        {{ trx.type }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <p :class="trx.type === 'income' ? 'text-green-600' : 'text-gray-800'" class="font-black">
                                        {{ trx.type === 'income' ? '+' : '-' }} Rp{{ Number(trx.amount).toLocaleString() }}
                                    </p>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex justify-center gap-1">
                                        <button @click="openEditTransaction(trx)" class="p-2 text-blue-400 transition-all rounded-lg hover:text-blue-600 hover:bg-blue-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        <button @click="deleteTransaction(trx.id)" class="p-2 text-red-400 transition-all rounded-lg hover:text-red-600 hover:bg-red-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col items-center justify-between gap-4 px-6 py-4 bg-gray-50/50 md:flex-row">
                    <p class="text-xs font-bold tracking-widest text-gray-400 uppercase">
                        Hal <span class="text-gray-800">{{ pagination.current_page }}</span> / {{ pagination.last_page }}
                    </p>
                    <div class="flex gap-2">
                        <button @click="changePage(pagination.current_page - 1)" :disabled="!pagination.prev_page_url" class="px-4 py-2 text-xs font-bold transition-all bg-white border border-gray-200 rounded-xl hover:bg-gray-50 disabled:opacity-30">
                            Sebelumnya
                        </button>
                        <button @click="changePage(pagination.current_page + 1)" :disabled="!pagination.next_page_url" class="px-4 py-2 text-xs font-bold transition-all bg-white border border-gray-200 rounded-xl hover:bg-gray-50 disabled:opacity-30">
                            Berikutnya
                        </button>
                    </div>
                </div>
            </div>
        </main>
        <TransactionModal :isOpen="isModalOpen" :categories="categories" :editData="editingTransaction" @close="isModalOpen = false" @save="handleSaveTransaction" />
        <CategoryModal :isOpen="isCategoryModalOpen" :categories="categories" @close="isCategoryModalOpen = false" @save="handleSaveCategory" @delete="handleDeleteCategory" />
    </div>
</template>
<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2'
import SkeletonTable from '../components/SkeletonTable.vue';
import { transactionService } from '../services/transactionService';
import TransactionModal from '../components/TransactionModal.vue';
import CategoryModal from '../components/CategoryModal.vue';
import api from '../api';
// ui 
const isMenuOpen = ref(false); // State untuk kontrol dropdown

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
};

// Fungsi penutup otomatis setelah klik menu (UX yang bagus)
const closeMenu = () => {
    isMenuOpen.value = false;
};

const router = useRouter();
let searchTimeout = null;
const summary = ref({
    balance: 0,
    total_income: 0,
    total_expense: 0
});
const allTransactionsForChart = ref([]);
const transactions = ref([]);
const categories = ref([]);
const isModalOpen = ref(false);
const isCategoryModalOpen = ref(false);
const isFetching = ref(true);
const loading = isFetching
const searchQuery = ref('');
const editingTransaction = ref(null);
const user = ref({ name: '' });

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true,
});

const pagination = ref({
    current_page: 1,
    last_page: 1,
    prev_page_url: null,
    next_page_url: null
});
// filtered
watch(searchQuery, (newVal) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadAllData(newVal);
    }, 500); // Tunggu 0.5 detik setelah user berhenti mengetik
});
const formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
};
// --- 1. FUNGSI PEMBUKA MODAL (YANG TADI HILANG) ---
const openAddTransaction = () => {
    editingTransaction.value = null; // Reset data edit
    isModalOpen.value = true;
};

const openEditTransaction = (trx) => {
    editingTransaction.value = trx; // Isi data yang mau diedit
    isModalOpen.value = true;
};

const openAddCategory = () => {
    isCategoryModalOpen.value = true;
};

// --- 2. LOGIKA SEARCH ---
const filteredTransactions = computed(() => {
    // 1. Cek apakah transactions benar-benar array
    if (!transactions.value || !Array.isArray(transactions.value)) {
        return [];
    }

    // 2. Jika tidak ada pencarian, kembalikan semua
    if (!searchQuery.value) {
        return transactions.value;
    }

    const query = searchQuery.value.toLowerCase();

    return transactions.value.filter(t => {
        // 3. Gunakan optional chaining untuk properti di dalam objek
        const description = t?.description?.toLowerCase() || "";
        const categoryName = t?.category?.name?.toLowerCase() || "";
        return description.includes(query) || categoryName.includes(query);
    });
});

const trendSeries = computed(() => {
    const data = allTransactionsForChart.value;
    if (!data || data.length === 0) return [{ name: 'Data', data: [] }];

    const days = [...Array(7)].map((_, i) => {
        const d = new Date();
        d.setDate(d.getDate() - i);
        return d.toISOString().split('T')[0];
    }).reverse();

    const incomeData = days.map(date => data
        .filter(t => t.type === 'income' && (t.date || t.created_at || "").includes(date))
        .reduce((sum, t) => sum + Number(t.amount || 0), 0));

    const expenseData = days.map(date => data
        .filter(t => t.type === 'expense' && (t.date || t.created_at || "").includes(date))
        .reduce((sum, t) => sum + Number(t.amount || 0), 0));

    return [
        { name: 'Pemasukan', data: incomeData },
        { name: 'Pengeluaran', data: expenseData }
    ];
});
const chartData = computed(() => {
    const data = allTransactionsForChart.value;
    if (!data || data.length === 0) return { series: [], labels: [] };

    const expenses = data.filter(t => t.type === 'expense');
    const categoryTotals = {};

    expenses.forEach(t => {
        // Pastikan kategori selalu ada namanya (fallback ke 'Lainnya')
        const catName = t.category?.name || 'Lainnya';
        categoryTotals[catName] = (categoryTotals[catName] || 0) + Number(t.amount);
    });

    return {
        series: Object.values(categoryTotals), // Contoh: [1000, 2000]
        labels: Object.keys(categoryTotals)    // Contoh: ['Makan', 'Transport']
    };
});

// Gunakan hasil dari chartData di atas
const chartSeries = computed(() => chartData.value.series);
// --- OPTIONS UNTUK DONUT CHART (Kategori Pengeluaran) ---
const donutOptions = computed(() => {
    return {
        labels: chartData.value.labels,
        colors: ['#3B82F6', '#10B981', '#EF4444', '#F59E0B', '#8B5CF6'],
        legend: { position: 'bottom' },
        plotOptions: {
            pie: {
                donut: {
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total Pengeluaran',
                            formatter: (w) => {
                                const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                return 'Rp ' + total.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        },
        tooltip: {
            y: { formatter: (val) => 'Rp ' + val.toLocaleString('id-ID') }
        }
    };
});

const lineOptions = computed(() => {
    return {
        chart: {
            type: 'area',
            toolbar: { show: false },
            zoom: { enabled: false }
        },
        colors: ['#10B981', '#EF4444'], // Hijau & Merah
        stroke: { curve: 'smooth', width: 3 },
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.1 }
        },
        xaxis: {
            categories: [...Array(7)].map((_, i) => {
                const d = new Date();
                d.setDate(d.getDate() - i);
                return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            }).reverse(),
            labels: { style: { colors: '#94a3b8' } }
        },
        yaxis: {
            labels: {
                style: { colors: '#94a3b8' },
                formatter: (val) => 'Rp ' + Math.round(val).toLocaleString('id-ID')
            }
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: (val) => 'Rp ' + val.toLocaleString('id-ID')
            }
        },
        dataLabels: { enabled: false },
        grid: { borderColor: '#f1f5f9' }
    };
});
const loadAllData = async (search = '', page = 1) => {
    isFetching.value = true;
    try {
        const [dashboardRes, categoryData, allDataRes] = await Promise.all([
            transactionService.fetchDashboardData(search, page),
            transactionService.fetchCategories(),
            api.get("/transactions/all-data")
        ]);

        const fetchUser = async () => {
            try {
                const response = await api.get('/user'); // Route bawaan Laravel Sanctum
                user.value = response.data;
                // Opsional: Update localStorage biar sinkron
                localStorage.setItem('user_name', response.data.name);
            } catch (error) {
                console.error("Gagal ambil data user");
            }
        };

        // --- 1. MAPPING SUMMARY ---
        // Berdasarkan log kamu, strukturnya adalah: resData.summary.data
        const resData = dashboardRes.data || dashboardRes;

        if (resData && resData.summary && resData.summary.data) {
            const actualData = resData.summary.data; // Ini tempat angka 2.100.000 berada

            summary.value = {
                balance: Number(actualData.balance || 0),
                total_income: Number(actualData.total_income || 0),
                total_expense: Number(actualData.total_expense || 0)
            };
        }
        // --- 2. MAPPING TABEL ---
        // Berdasarkan log kamu, transactions langsung ada di resData
        if (resData.transactions) {
            const resTable = resData.transactions;
            transactions.value = resTable.data || [];

            if (resTable.current_page) {
                pagination.value = {
                    current_page: resTable.current_page,
                    last_page: resTable.last_page,
                    prev_page_url: resTable.prev_page_url,
                    next_page_url: resTable.next_page_url
                };
            }
        }
        // Mapping Grafik & Kategori
        allTransactionsForChart.value = Array.isArray(allDataRes.data) ? allDataRes.data : (allDataRes.data?.data || []);
        categories.value = categoryData;


    } catch (error) {
        console.error("Gagal sinkronisasi data:", error);
        // Tetap tampilkan pesan error ke user jika gagal total
        const errorMsg = error.response?.data?.message || 'Gagal mengambil data dari server';
        Swal.fire('Error', errorMsg, 'error');
    } finally {
        isFetching.value = false;
    }
};
const fetchUser = async () => {
    try {
        const response = await api.get('/user'); // Route bawaan Laravel Sanctum
        user.value = response.data;
        // Opsional: Update localStorage biar sinkron
        localStorage.setItem('user_name', response.data.name);
    } catch (error) {
        console.error("Gagal ambil data user");
    }
};
const updateProfile = async () => {
    try {
        await api.put('/user/profile', {
            name: user.value.name,
            // Bisa tambah email juga di sini
        });
        Swal.fire('Berhasil!', 'Nama kamu sudah terupdate.', 'success');
    } catch (error) {
        Swal.fire('Gagal', 'Gagal update profil', 'error');
    }
};

// Fungsi navigasi
const changePage = (page) => {
    if (page >= 1 && page <= pagination.value.last_page) {
        loadAllData(searchQuery.value, page);
    }
};

const handleSaveTransaction = async (data) => {
    try {
        if (editingTransaction.value) {
            await transactionService.updateTransaction(editingTransaction.value.id, data);
        } else {
            await transactionService.storeTransaction(data);
        }
        isModalOpen.value = false;
        await loadAllData();
        Toast.fire({ icon: 'success', title: 'Transaksi berhasil disimpan!' });
    } catch (err) {
        Swal.fire({ icon: 'error', title: 'Oops...', text: 'Gagal: ' + err.message });
    }
};

const deleteTransaction = async (id) => {
    // 1. Konfirmasi dulu ke user
    const result = await Swal.fire({
        title: 'Apakah kamu yakin?',
        text: "Data transaksi ini akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });

    // 2. Jika user klik "Ya"
    if (result.isConfirmed) {
        try {
            await api.delete(`/transactions/${id}`);

            Swal.fire(
                'Terhapus!',
                'Transaksi berhasil dihapus.',
                'success'
            );

            // 3. Refresh data dashboard agar angka saldo & tabel update otomatis
            loadAllData();
        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Gagal menghapus data', 'error');
        }
    }
};
const handleSaveCategory = async (data) => {
    try {
        if (data.id) {
            // JIKA ADA ID: Berarti Update (PUT)
            await transactionService.updateCategory(data.id, { name: data.name });
            Toast.fire({ icon: 'success', title: 'Nama kategori diperbarui!' });
        } else {
            await transactionService.storeCategory(data.name);
            Toast.fire({ icon: 'success', title: 'Kategori baru ditambahkan!' });
        }

        await loadAllData(); // Refresh list kategori dan grafik
    } catch (err) {
        Swal.fire('Gagal', err.response?.data?.message || 'Gagal simpan kategori', 'error');
    }
};

const handleDeleteCategory = async (id) => {
    const result = await Swal.fire({
        title: 'Hapus Kategori?',
        text: "Kategori yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });

    if (result.isConfirmed) {
        try {
            await transactionService.deleteCategory(id);
            await loadAllData();
            Toast.fire({ icon: 'success', title: 'Kategori berhasil dibuang!' });
        } catch (err) {
            // Cek jika error karena relasi database (masih dipakai transaksi)
            const errorMsg = err.response?.status === 500
                ? 'Kategori tidak bisa dihapus karena masih digunakan dalam beberapa transaksi.'
                : 'Gagal menghapus kategori.';

            Swal.fire('Oops!', errorMsg, 'error');
        }
    }
};

const handleLogout = async () => {
    try {
        // 1. Beritahu Laravel untuk mematikan token (Opsional tapi disarankan)
        await api.post('/logout');
    } catch (error) {
        console.error("Server sudah logout atau token kadaluarsa");
    } finally {
        // 2. HAPUS SEMUA DATA DI STORAGE (Wajib)
        localStorage.removeItem('token');
        localStorage.removeItem('user_name'); // Jika ada

        // 3. Tendang user balik ke halaman Login
        router.push('/');

        Swal.fire({
            icon: 'success',
            title: 'Berhasil Keluar',
            showConfirmButton: false,
            timer: 1500
        });
    }
};

onMounted(() => {
    document.title = "Dashboard | Rekap Keuangan";
    fetchUser();
    loadAllData();
});
</script>