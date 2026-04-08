import { transactionRepository, categoryRepository } from "../repositories/transactionRepository";

export const transactionService = {
	async fetchCategories() {
		try {
			const response = await categoryRepository.getAll();
			return response.data.data;
		} catch (error) {
			console.error("Error di Service fetchCategories:", error);
			throw error;
		}
	},

	// Fungsi Simpan Kategori
	async storeCategory(name) {
		try {
			return await categoryRepository.create({ name });
		} catch (error) {
			throw error;
		}
	},
	async updateCategory(id, name) {
		return await categoryRepository.update(id, { name });
	},
	async deleteCategory(id) {
		return await categoryRepository.delete(id);
	},

	// Fungsi Dashboard (Transaksi & Summary) - DIPERBAIKI
	// Di transactionService.js
	async fetchDashboardData(search = "", page = 1) {
		const [transactions, summary] = await Promise.all([transactionRepository.getAll(search, page), transactionRepository.getSummary()]);

		return {
			// Cukup ambil .data dari axios response, jangan dipotong lagi di sini
			transactions: transactions.data,
			summary: summary.data,
		};
	},

	// transaksi
	async storeTransaction(payload) {
		return await transactionRepository.create(payload);
	},
	async updateTransaction(id, data) {
		return await transactionRepository.update(id, data);
	},
	// Tambahkan deleteTransaction yang tadi kita buat logic-nya
	async deleteTransaction(id) {
		return await transactionRepository.delete(id);
	},
};
