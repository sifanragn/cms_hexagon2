@extends('layouts.app')

@section('content')
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-gray-800">Detail Pricing</h2>

                <!-- 🔹 Filter by Type -->
                <form action="" method="GET" id="filterForm">
                    <select name="type" id="typeFilter" class="border rounded p-2 text-sm"
                        onchange="filterByType(this.value)">
                        <option value="">Semua Type</option>
                        @foreach ($types as $t)
                            <option value="{{ $t }}" {{ isset($selectedType) && $selectedType == $t ? 'selected' : '' }}>
                                {{ ucfirst($t) }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                + Add New Detail Pricing
            </button>
        </div>

        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            </script>
        @endif

        <!-- TABEL -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pricing</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keuntungan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($details as $item)
                        <tr>
                            <td class="px-6 py-4 font-semibold text-gray-800">
                                {{ $item->pricing ? $item->pricing->nama : 'Tidak Ada' }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $item->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($item->deskripsi, 60) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($item->keuntungan, 60) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->type }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full
                                    {{ $item->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex gap-3">
                                    <button onclick="openDetailModal({{ $item->id }})"
                                        class="text-gray-600 hover:text-blue-800" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="openEditModal({{ $item->id }})"
                                        class="text-blue-600 hover:text-blue-800" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button onclick="confirmDelete({{ $item->id }})"
                                        class="text-red-500 hover:text-red-700" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $item->id }}"
                                    action="{{ route('detail-pricings.destroy', $item->id) }}" method="POST"
                                    class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============ ADD MODAL ============ -->
    <div id="addModal" class="modal fixed inset-0 z-50 bg-black/40 justify-center items-center">
        <div class="bg-white max-w-2xl w-full rounded-lg shadow p-8 overflow-y-auto max-h-[90vh]">
            <h2 class="text-xl font-bold mb-4">Add New Detail Pricing</h2>
            <form action="{{ route('detail-pricings.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block font-medium mb-1">Pricing</label>
                        <select name="id_pricings" class="w-full border p-2 rounded">
                            <option value="">Pilih Pricing</option>
                            @foreach ($pricings as $pricing)
                                <option value="{{ $pricing->id }}">{{ $pricing->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Name</label>
                        <input type="text" name="name" class="w-full border p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Type</label>
                        <input type="text" name="type" class="w-full border p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Status</label>
                        <input type="text" name="status" class="w-full border p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="w-full border p-2 rounded"></textarea>
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Keuntungan</label>
                        <textarea name="keuntungan" rows="3" class="w-full border p-2 rounded"></textarea>
                    </div>
                </div>
                <div class="text-right mt-4">
                    <button type="button" onclick="closeModal('addModal')"
                        class="bg-red-400 text-white px-4 py-2 rounded">Cancel</button>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============ EDIT MODAL ============ -->
    <div id="editModal" class="modal fixed inset-0 z-50 bg-black/40 justify-center items-center">
        <div class="bg-white max-w-2xl w-full rounded-lg shadow p-8 overflow-y-auto max-h-[90vh]">
            <h2 class="text-xl font-bold mb-4">Edit Detail Pricing</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block font-medium mb-1">Pricing</label>
                        <select name="id_pricings" id="edit_pricing" class="w-full border p-2 rounded">
                            <option value="">Pilih Pricing</option>
                            @foreach ($pricings as $pricing)
                                <option value="{{ $pricing->id }}">{{ $pricing->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Name</label>
                        <input type="text" name="name" id="edit_name" class="w-full border p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Type</label>
                        <input type="text" name="type" id="edit_type" class="w-full border p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Status</label>
                        <input type="text" name="status" id="edit_status" class="w-full border p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Deskripsi</label>
                        <textarea name="deskripsi" id="edit_deskripsi" rows="3" class="w-full border p-2 rounded"></textarea>
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Keuntungan</label>
                        <textarea name="keuntungan" id="edit_keuntungan" rows="3" class="w-full border p-2 rounded"></textarea>
                    </div>
                </div>
                <div class="text-right mt-4">
                    <button type="button" onclick="closeModal('editModal')"
                        class="bg-red-400 text-white px-4 py-2 rounded">Cancel</button>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============ DETAIL MODAL ============ -->
    <div id="detailModal" class="modal fixed inset-0 z-50 bg-black/40 justify-center items-center">
        <div class="bg-white max-w-md w-full rounded-lg shadow p-6 overflow-y-auto max-h-[80vh]">
            <h3 id="detailName" class="text-lg font-bold mb-3"></h3>
            <div class="space-y-3">
                <div><p class="font-semibold">Pricing:</p><p id="detailPricing" class="text-gray-800"></p></div>
                <div><p class="font-semibold">Type:</p><p id="detailType" class="text-gray-800"></p></div>
                <div><p class="font-semibold">Status:</p><span id="detailStatus" class="px-2 py-1 text-xs font-medium rounded-full"></span></div>
                <div><p class="font-semibold">Deskripsi:</p><p id="detailDeskripsi" class="text-gray-800 whitespace-pre-line"></p></div>
                <div><p class="font-semibold">Keuntungan:</p><p id="detailKeuntungan" class="text-gray-800 whitespace-pre-line"></p></div>
            </div>
            <div class="text-right mt-4">
                <button onclick="closeModal('detailModal')" class="bg-blue-400 text-white px-4 py-2 rounded">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ============ SCRIPT ============ -->
    <script>
        function filterByType(type) {
            if (type) {
                window.location.href = `/detail-pricings/${encodeURIComponent(type)}`;
            } else {
                window.location.href = `/detail-pricings`;
            }
        }

        function openAddModal() { document.getElementById('addModal').classList.add('open'); }
        function closeModal(id) { document.getElementById(id).classList.remove('open'); }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data akan hilang permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!'
            }).then(res => {
                if (res.isConfirmed) document.getElementById('delete-form-' + id).submit();
            });
        }

        function openEditModal(id) {
            const form = document.getElementById('editForm');
            form.action = `/detail-pricings/${id}`;
            fetch(`/detail-pricings/${id}/edit`)
                .then(r => r.json())
                .then(d => {
                    document.getElementById('edit_pricing').value = d.id_pricings || '';
                    document.getElementById('edit_name').value = d.name;
                    document.getElementById('edit_type').value = d.type;
                    document.getElementById('edit_status').value = d.status;
                    document.getElementById('edit_deskripsi').value = d.deskripsi || '';
                    document.getElementById('edit_keuntungan').value = d.keuntungan || '';
                });
            document.getElementById('editModal').classList.add('open');
        }

        function openDetailModal(id) {
            fetch(`/detail-pricings/${id}`)
                .then(r => r.json())
                .then(d => {
                    document.getElementById('detailName').textContent = d.name;
                    document.getElementById('detailPricing').textContent = d.pricing ? d.pricing.nama : 'Tidak Ada';
                    document.getElementById('detailType').textContent = d.type || '-';
                    document.getElementById('detailDeskripsi').textContent = d.deskripsi || 'Tidak ada deskripsi';
                    document.getElementById('detailKeuntungan').textContent = d.keuntungan || 'Tidak ada keuntungan';
                    const statusEl = document.getElementById('detailStatus');
                    statusEl.textContent = d.status.charAt(0).toUpperCase() + d.status.slice(1);
                    statusEl.className = `px-2 py-1 text-xs font-medium rounded-full ${
                        d.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                    }`;
                    document.getElementById('detailModal').classList.add('open');
                });
        }
    </script>

    <style>
        .modal { display: none; }
        .modal.open { display: flex; }
    </style>
@endsection
