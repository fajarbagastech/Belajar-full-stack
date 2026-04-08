import api from "../api";

export const transactionRepository = {
	getAll(search = "", page = 1) {
		return api.get("/transactions", {
			params: { search, page },
		});
	},
	getAllRaw() {
		return api.get("/transactions/all-data"); // Buat route baru di Laravel yang tidak pakai paginate()
	},
	getSummary() {
		return api.get("/summary");
	},
	create(data) {
		return api.post("/transactions", data);
	},
	update(id, data) {
		return api.patch(`/transactions/${id}`, data);
	},
	delete(id) {
		return api.delete(`/transactions/${id}`);
	},
};

export const categoryRepository = {
	getAll() {
		return api.get("/categories");
	},
	create(data) {
		return api.post("/categories", data);
	},
	update(id, data) {
		return api.put(`/categories/${id}`, data);
	},
	delete(id) {
		return api.delete(`/categories/${id}`);
	},
};
