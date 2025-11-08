@include('manajemenStockBarang.partials.editform')
@include('manajemenStockBarang.partials.addmodal')

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Stok Barang</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">📦 Manajemen Stok Barang</h2>
            <button id="btnAdd" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Barang
            </button>
        </div>

        {{-- Search & Filter --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form id="searchForm" class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari nama barang...">
                    </div>
                    <div class="col-md-3">
                        <select id="filterCategory" class="form-select">
                            <option value="">Semua Kategori</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="sortOption" class="form-select">
                            <option value="">Urutkan</option>
                            <option value="name_asc">Nama (A-Z)</option>
                            <option value="stock_low">Stok Terendah</option>
                            <option value="stock_high">Stok Tertinggi</option>
                        </select>
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="submit" class="btn btn-outline-secondary w-100">Cari</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover align-middle" id="productTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Unit</th>
                            <th>Diperbarui</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="7" class="text-center text-muted">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const API_BASE = "http://127.0.0.1:8000/manajemenStock";
        const tableBody = document.getElementById("tableBody");
        const filterCategory = document.getElementById("filterCategory");
        const searchInput = document.getElementById("searchInput");
        const sortOption = document.getElementById("sortOption");

        let allProducts = []; // store all products fetched

        // Fetch all products from backend
        async function fetchProducts() {
            try {
                const res = await fetch(`${API_BASE}/getallproduct`);
                const json = await res.json();

                if (!json.dataProduct || json.dataProduct.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-muted">Tidak ada produk ditemukan.</td></tr>`;
                    return;
                }

                // Save all products locally
                allProducts = json.dataProduct;

                // Populate category dropdown (unique categories)
                const categories = [...new Set(allProducts.map(p => p.category).filter(Boolean))];
                filterCategory.innerHTML = `<option value="">Semua Kategori</option>`;
                categories.forEach(c => {
                    filterCategory.innerHTML += `<option value="${c}">${c}</option>`;
                });

                renderTable(allProducts);
            } catch (err) {
                console.error(err);
                tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Gagal memuat data produk.</td></tr>`;
            }
        }

        // Render table
        function renderTable(data) {
            tableBody.innerHTML = "";
            if (!data.length) {
                tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-muted">Tidak ada produk ditemukan.</td></tr>`;
                return;
            }

            data.forEach((p, index) => {
                tableBody.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${p.name}</td>
                    <td>${p.category ?? '-'}</td>
                    <td>
                        <span class="badge ${p.stock <= 10 ? 'bg-danger' : p.stock < 50 ? 'bg-warning text-dark' : 'bg-success'}">
                            ${p.stock}
                        </span>
                    </td>
                    <td>${p.unit}</td>
                    <td>${new Date(p.updated_at).toLocaleDateString('id-ID')}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-warning me-1"
                            onclick="editProduct(${p.id}, '${p.name}', '${p.category ?? ''}', ${p.stock}, '${p.unit ?? ''}')">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteProduct(${p.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>`;
            });
        }

        // Apply filters (search + category + sorting)
        function applyFilters() {
            const search = searchInput.value.toLowerCase();
            const selectedCategory = filterCategory.value;
            const sort = sortOption.value;

            let filtered = allProducts.filter(p =>
                p.name.toLowerCase().includes(search) &&
                (selectedCategory === "" || p.category === selectedCategory)
            );

            if (sort === "name_asc") {
                filtered.sort((a, b) => a.name.localeCompare(b.name));
            } else if (sort === "stock_low") {
                filtered.sort((a, b) => a.stock - b.stock);
            } else if (sort === "stock_high") {
                filtered.sort((a, b) => b.stock - a.stock);
            }

            renderTable(filtered);
        }

        // 🔥 Real-time filter listeners
        searchInput.addEventListener("input", applyFilters);
        filterCategory.addEventListener("change", applyFilters);
        sortOption.addEventListener("change", applyFilters);

        // Delete product
        async function deleteProduct(id) {
            if (!confirm("Apakah Anda yakin ingin menghapus produk ini?")) return;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            try {
                const res = await fetch(`${API_BASE}/deleteProduct/${id}`, {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token
                    },
                });
                const json = await res.json();
                alert(json.message || "Produk dihapus");
                fetchProducts();
            } catch (err) {
                console.error(err);
                alert("Gagal menghapus produk.");
            }
        }

        // Show modal & fill form
        function editProduct(id, name, category, stock, unit) {
            document.getElementById("editId").value = id;
            document.getElementById("editName").value = name;
            document.getElementById("editCategory").value = category;
            document.getElementById("editStock").value = stock;
            document.getElementById("editUnit").value = unit;

            const editModal = new bootstrap.Modal(document.getElementById("editModal"));
            editModal.show();
        }

        // Handle Edit form
        document.getElementById("editForm").addEventListener("submit", async (e) => {
            e.preventDefault();

            const id = document.getElementById("editId").value;
            const name = document.getElementById("editName").value;
            const category = document.getElementById("editCategory").value;
            const stock = document.getElementById("editStock").value;
            const unit = document.getElementById("editUnit").value;

            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const res = await fetch(`${API_BASE}/updateProduct/${id}`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify({ name, category, stock, unit }),
                });

                const json = await res.json();
                alert(json.message);
                bootstrap.Modal.getInstance(document.getElementById("editModal")).hide();
                fetchProducts();
            } catch (err) {
                console.error(err);
                alert("Gagal memperbarui produk.");
            }
        });

        // Show Add Product modal
        document.getElementById("btnAdd").addEventListener("click", () => {
            const addModal = new bootstrap.Modal(document.getElementById("addModal"));
            addModal.show();
        });

        // Handle Add Product form
        document.getElementById("addModal").addEventListener("submit", async (e) => {
            e.preventDefault();

            const supplier_id = document.getElementById("addSupplierId").value;
            const name = document.getElementById("addName").value;
            const category = document.getElementById("addCategory").value;
            const stock = document.getElementById("addStock").value;
            const unit = document.getElementById("addUnit").value;

            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const res = await fetch(`${API_BASE}/addProduct`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify({ supplier_id, name, category, stock, unit }),
                });

                const json = await res.json();
                alert(json.message);
                bootstrap.Modal.getInstance(document.getElementById("addModal")).hide();
                document.getElementById("addForm").reset();
                fetchProducts();
            } catch (err) {
                console.error(err);
                alert("Gagal menambahkan produk.");
            }
        });

        document.addEventListener("DOMContentLoaded", fetchProducts);
    </script>
</body>

</html>