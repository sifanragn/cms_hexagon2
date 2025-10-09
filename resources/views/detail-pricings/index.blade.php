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
                            <td class="px-6 py-4 font-semibold text-gray-800">
                                {{ is_array($item->name) ? implode(', ', $item->name) : $item->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($item->deskripsi, 60) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ Str::limit($item->keuntungan, 60) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->type }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statuses = is_array($item->status) ? $item->status : [$item->status];
                                @endphp
                                @foreach ($statuses as $s)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full mr-1
                                        {{ $s == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($s) }}
                                    </span>
                                @endforeach
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

                    <!-- NAME ARRAY -->
                    <div>
                        <label class="block font-medium mb-1">Name</label>
                        <div id="add-name-wrapper" class="space-y-2">
                            <div class="flex gap-2">
                                <input type="text" name="name[]" class="w-full border p-2 rounded" placeholder="Masukkan nama" required>
                                <button type="button" onclick="removeField(this)" class="text-red-500 px-3">Remove</button>
                            </div>
                        </div>
                        <button type="button" onclick="addTextField('add-name-wrapper', 'name[]')" class="text-blue-600 text-sm mt-2">+ Add Name</button>
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Type</label>
                        <input type="text" name="type" class="w-full border p-2 rounded" placeholder="Contoh: basic, premium, enterprise" required>
                    </div>

                    <!-- STATUS ARRAY -->
                    <div>
                        <label class="block font-medium mb-1">Status</label>
                        <div id="add-status-wrapper" class="space-y-2">
                            <div class="flex gap-2">
                                <input type="text" name="status[]" class="w-full border p-2 rounded" placeholder="Contoh: active, inactive" required>
                                <button type="button" onclick="removeField(this)" class="text-red-500 px-3">Remove</button>
                            </div>
                        </div>
                        <button type="button" onclick="addTextField('add-status-wrapper', 'status[]')" class="text-blue-600 text-sm mt-2">+ Add Status</button>
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="w-full border p-2 rounded" placeholder="Deskripsi detail pricing"></textarea>
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Deskripsi 2</label>
                        <textarea name="deskripsi2" rows="3" class="w-full border p-2 rounded" placeholder="Deskripsi tambahan"></textarea>
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Keuntungan</label>
                        <textarea name="keuntungan" rows="3" class="w-full border p-2 rounded" placeholder="Keuntungan dari pricing ini"></textarea>
                    </div>
                </div>
                <div class="text-right mt-6 space-x-2">
                    <button type="button" onclick="closeModal('addModal')"
                        class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
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

                    <!-- NAME ARRAY -->
                    <div>
                        <label class="block font-medium mb-1">Name</label>
                        <div id="edit-name-wrapper" class="space-y-2"></div>
                        <button type="button" onclick="addTextField('edit-name-wrapper', 'name[]')" class="text-blue-600 text-sm mt-2">+ Add Name</button>
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Type</label>
                        <input type="text" name="type" id="edit_type" class="w-full border p-2 rounded" required>
                    </div>

                    <!-- STATUS ARRAY -->
                    <div>
                        <label class="block font-medium mb-1">Status</label>
                        <div id="edit-status-wrapper" class="space-y-2"></div>
                        <button type="button" onclick="addTextField('edit-status-wrapper', 'status[]')" class="text-blue-600 text-sm mt-2">+ Add Status</button>
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Deskripsi</label>
                        <textarea name="deskripsi" id="edit_deskripsi" rows="3" class="w-full border p-2 rounded"></textarea>
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Deskripsi 2</label>
                        <textarea name="deskripsi2" id="edit_deskripsi2" rows="3" class="w-full border p-2 rounded"></textarea>
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Keuntungan</label>
                        <textarea name="keuntungan" id="edit_keuntungan" rows="3" class="w-full border p-2 rounded"></textarea>
                    </div>
                </div>
                <div class="text-right mt-6 space-x-2">
                    <button type="button" onclick="closeModal('editModal')"
                        class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============ DETAIL MODAL ============ -->
    <div id="detailModal" class="modal fixed inset-0 z-50 bg-black/40 justify-center items-center">
        <div class="bg-white max-w-md w-full rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 overflow-y-auto max-h-[70vh]">
                <h3 id="detailName" class="text-lg font-bold mb-3"></h3>
                <div class="space-y-3">
                    <div><p class="font-semibold">Pricing:</p><p id="detailPricing" class="text-gray-800"></p></div>
                    <div><p class="font-semibold">Type:</p><p id="detailType" class="text-gray-800"></p></div>
                    <div><p class="font-semibold">Status:</p><div id="detailStatus"></div></div>
                    <div><p class="font-semibold">Deskripsi:</p><p id="detailDeskripsi" class="text-gray-800 whitespace-pre-line"></p></div>
                    <div><p class="font-semibold">Deskripsi 2:</p><p id="detailDeskripsi2" class="text-gray-800 whitespace-pre-line"></p></div>
                    <div><p class="font-semibold">Keuntungan:</p><p id="detailKeuntungan" class="text-gray-800 whitespace-pre-line"></p></div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-200">
                <button onclick="closeModal('detailModal')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow-sm font-medium">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ============ SCRIPT ============ -->
    <script>
        function filterByType(type) {
            if (type) window.location.href = `/detail-pricings/${encodeURIComponent(type)}`;
            else window.location.href = `/detail-pricings`;
        }

        function openAddModal() {
            document.getElementById('addModal').classList.add('open');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('open');
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data akan hilang permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(res => {
                if (res.isConfirmed) document.getElementById('delete-form-' + id).submit();
            });
        }

        // Fungsi universal untuk tambah field text
        function addTextField(wrapperId, inputName) {
            const wrapper = document.getElementById(wrapperId);
            const div = document.createElement('div');
            div.classList.add('flex', 'gap-2', 'mb-2');
            div.innerHTML = `
                <input type="text" name="${inputName}" class="w-full border p-2 rounded" placeholder="Masukkan data" required>
                <button type="button" onclick="removeField(this)" class="text-red-500 px-3">Remove</button>
            `;
            wrapper.appendChild(div);
        }

        // Fungsi untuk hapus field
        function removeField(button) {
            button.parentElement.remove();
        }

        // EDIT MODAL
        function openEditModal(id) {
            const form = document.getElementById('editForm');
            form.action = `/detail-pricings/${id}`;

            fetch(`/detail-pricings/edit/${id}`)
                .then(r => {
                    if (!r.ok) throw new Error('Network response was not ok');
                    return r.json();
                })
                .then(d => {
                    console.log('Data received:', d);

                    document.getElementById('edit_pricing').value = d.id_pricings || '';
                    document.getElementById('edit_type').value = d.type || '';
                    document.getElementById('edit_deskripsi').value = d.deskripsi || '';
                    document.getElementById('edit_deskripsi2').value = d.deskripsi2 || '';
                    document.getElementById('edit_keuntungan').value = d.keuntungan || '';

                    // Handle Name Array
                    const names = Array.isArray(d.name) ? d.name : (d.name ? [d.name] : ['']);
                    const nameWrapper = document.getElementById('edit-name-wrapper');
                    nameWrapper.innerHTML = '';

                    names.forEach(n => {
                        const div = document.createElement('div');
                        div.classList.add('flex', 'gap-2', 'mb-2');
                        div.innerHTML = `
                            <input type="text" name="name[]" class="w-full border p-2 rounded" value="${n || ''}" placeholder="Masukkan nama" required>
                            <button type="button" onclick="removeField(this)" class="text-red-500 px-3">Remove</button>
                        `;
                        nameWrapper.appendChild(div);
                    });

                    // Handle Status Array
                    const statuses = Array.isArray(d.status) ? d.status : (d.status ? [d.status] : ['']);
                    const statusWrapper = document.getElementById('edit-status-wrapper');
                    statusWrapper.innerHTML = '';

                    statuses.forEach(s => {
                        const div = document.createElement('div');
                        div.classList.add('flex', 'gap-2', 'mb-2');
                        div.innerHTML = `
                            <input type="text" name="status[]" class="w-full border p-2 rounded" value="${s || ''}" placeholder="Contoh: active, inactive" required>
                            <button type="button" onclick="removeField(this)" class="text-red-500 px-3">Remove</button>
                        `;
                        statusWrapper.appendChild(div);
                    });

                    document.getElementById('editModal').classList.add('open');
                })
                .catch(err => {
                    console.error('Error:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat data: ' + err.message
                    });
                });
        }

        // DETAIL MODAL
        function openDetailModal(id) {
            fetch(`/detail-pricings/show/${id}`)
                .then(r => {
                    if (!r.ok) throw new Error('Network response was not ok');
                    return r.json();
                })
                .then(d => {
                    console.log('Detail data:', d);

                    // Handle name
                    const displayName = Array.isArray(d.name) ? d.name.join(', ') : (d.name || '-');
                    document.getElementById('detailName').textContent = displayName;

                    document.getElementById('detailPricing').textContent = d.pricing ? d.pricing.nama : 'Tidak Ada';
                    document.getElementById('detailType').textContent = d.type || '-';
                    document.getElementById('detailDeskripsi').textContent = d.deskripsi || 'Tidak ada deskripsi';
                    document.getElementById('detailDeskripsi2').textContent = d.deskripsi2 || 'Tidak ada deskripsi 2';
                    document.getElementById('detailKeuntungan').textContent = d.keuntungan || 'Tidak ada keuntungan';

                    // Handle status
                    const statusEl = document.getElementById('detailStatus');
                    const statuses = Array.isArray(d.status) ? d.status : (d.status ? [d.status] : []);

                    if (statuses.length > 0) {
                        statusEl.innerHTML = statuses.map(s =>
                            `<span class="inline-block px-2 py-1 text-xs font-medium rounded-full mr-1 mb-1 ${s === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${s}</span>`
                        ).join('');
                    } else {
                        statusEl.innerHTML = '<span class="text-gray-500">-</span>';
                    }

                    document.getElementById('detailModal').classList.add('open');
                })
                .catch(err => {
                    console.error('Error:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat detail: ' + err.message
                    });
                });
        }
    </script>

    <style>
        .modal {
            display: none;
        }
        .modal.open {
            display: flex;
        }
    </style>
@endsection
