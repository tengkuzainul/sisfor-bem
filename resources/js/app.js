import './bootstrap';

// SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// Toast helper using SweetAlert2 (ringan, tanpa jQuery)
window.Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

// Simple-DataTables — lazy-loaded only when needed
// Use: await loadDataTable() → returns DataTable class
window.loadDataTable = async function () {
    if (window._DataTableClass) return window._DataTableClass;
    // Vite will code-split this into a separate chunk
    await import('simple-datatables/dist/style.css');
    const { DataTable } = await import('simple-datatables');
    window._DataTableClass = DataTable;
    return DataTable;
};

// ============================================
// Server-Side DataTable (Alpine.js + Axios)
// Reusable component for AJAX-powered tables
// ============================================
window.dataTable = function (config = {}) {
    return {
        // Reactive state
        data: [],
        meta: { current_page: 1, last_page: 1, per_page: 10, total: 0, from: 0, to: 0 },
        search: '',
        perPage: config.perPage || 10,
        sortBy: config.sortBy || 'id',
        sortDir: config.sortDir || 'asc',
        loading: true,
        error: false,
        url: config.url || '',
        statusFilter: config.statusFilter || '',
        _controller: null,

        // Lifecycle
        init() {
            this.fetchData();
        },

        // Core: Fetch paginated data from server
        async fetchData() {
            if (this._controller) this._controller.abort();
            this._controller = new AbortController();
            this.loading = true;
            this.error = false;
            try {
                const { data } = await axios.get(this.url, {
                    params: {
                        page: this.meta.current_page,
                        per_page: this.perPage,
                        search: this.search,
                        sort_by: this.sortBy,
                        sort_dir: this.sortDir,
                        status: this.statusFilter || undefined,
                    },
                    signal: this._controller.signal,
                });
                this.data = data.data;
                this.meta = {
                    current_page: data.current_page,
                    last_page: data.last_page,
                    per_page: data.per_page,
                    total: data.total,
                    from: data.from ?? 0,
                    to: data.to ?? 0,
                };
            } catch (e) {
                if (e.name !== 'CanceledError' && e.code !== 'ERR_CANCELED') {
                    this.error = true;
                    console.error('DataTable fetch error:', e);
                }
            } finally {
                this.loading = false;
            }
        },

        // Trigger search (reset to page 1)
        doSearch() {
            this.meta.current_page = 1;
            this.fetchData();
        },

        // Change rows per page
        changePerPage() {
            this.meta.current_page = 1;
            this.fetchData();
        },

        // Sort by column
        sort(column) {
            if (this.sortBy === column) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortBy = column;
                this.sortDir = 'asc';
            }
            this.meta.current_page = 1;
            this.fetchData();
        },

        // Pagination helpers
        goToPage(page) {
            if (typeof page !== 'number' || page === this.meta.current_page) return;
            if (page >= 1 && page <= this.meta.last_page) {
                this.meta.current_page = page;
                this.fetchData();
            }
        },
        nextPage() { this.goToPage(this.meta.current_page + 1); },
        prevPage() { this.goToPage(this.meta.current_page - 1); },

        // Compute visible page numbers with ellipsis
        get pages() {
            const c = this.meta.current_page, l = this.meta.last_page;
            if (l <= 7) return Array.from({ length: l }, (_, i) => i + 1);
            const p = [1];
            if (c > 3) p.push('…');
            for (let i = Math.max(2, c - 1); i <= Math.min(l - 1, c + 1); i++) p.push(i);
            if (c < l - 2) p.push('…');
            p.push(l);
            return p;
        },

        // Delete item with SweetAlert2 confirmation
        async deleteItem(url) {
            const result = await Swal.fire({
                title: 'Yakin hapus?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
            });
            if (!result.isConfirmed) return;
            try {
                const { data } = await axios.delete(url);
                Toast.fire({ icon: 'success', title: data.message || 'Data berhasil dihapus.' });
                if (this.data.length === 1 && this.meta.current_page > 1) this.meta.current_page--;
                this.fetchData();
            } catch (e) {
                Toast.fire({ icon: 'error', title: e.response?.data?.message || 'Gagal menghapus data.' });
            }
        },

        // PATCH action with optional confirmation (e.g. activate/deactivate)
        async patchAction(url, confirmMsg = null) {
            if (confirmMsg) {
                const result = await Swal.fire({
                    title: confirmMsg,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, lanjutkan',
                    cancelButtonText: 'Batal',
                });
                if (!result.isConfirmed) return;
            }
            try {
                const { data } = await axios.patch(url);
                Toast.fire({ icon: 'success', title: data.message || 'Berhasil.' });
                this.fetchData();
            } catch (e) {
                Toast.fire({ icon: 'error', title: e.response?.data?.message || 'Gagal.' });
            }
        },

        // Format ISO date string to "d Mon YYYY"
        formatDate(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            const m = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
            return d.getDate() + ' ' + m[d.getMonth()] + ' ' + d.getFullYear();
        },
    };
};

// ============================================
// Alpine.js - MUST be initialized AFTER all
// Alpine data/components are registered above
// ============================================
import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import collapse from '@alpinejs/collapse';

Alpine.plugin(focus);
Alpine.plugin(collapse);

// Register dataTable as Alpine.data so x-data="dataTable({...})" works
Alpine.data('dataTable', (config = {}) => window.dataTable(config));

window.Alpine = Alpine;
Alpine.start();
