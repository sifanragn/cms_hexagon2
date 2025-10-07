@extends('layouts.app')

@section('content')
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Pricing</h2>
            <button onclick="openAddModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                + Add New Pricing
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Diskon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($pricings as $item)
                        <tr>
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $item->nama }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $item->diskon ? $item->diskon . '%' : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                Rp {{ number_format($item->total, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
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
                                    action="{{ route('pricing.destroy', $item->id) }}" method="POST"
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
            <h2 class="text-xl font-bold mb-4">Add New Layanan</h2>
            <form action="{{ route('pricing.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium mb-1">Nama</label>
                            <input type="text" name="nama" class="w-full border p-2 rounded" required>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Title</label>
                            <input type="text" name="title" class="w-full border p-2 rounded" required>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="block font-medium mb-1">Price (Rp)</label>
                            <input type="number" name="price" min="0" class="w-full border p-2 rounded" required>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Diskon (%)</label>
                            <input type="number" name="diskon" min="0" max="100" class="w-full border p-2 rounded">
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Status</label>
                            <input type="text" name="status" class="w-full border p-2 rounded" required>
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="w-full border p-2 rounded" placeholder="Optional description"></textarea>
                    </div>
                </div>

                <div class="text-right mt-6">
                    <button type="button" onclick="closeModal('addModal')"
                        class="bg-red-400 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============ EDIT MODAL ============ -->
    <div id="editModal" class="modal fixed inset-0 z-50 bg-black/40 justify-center items-center">
        <div class="bg-white max-w-2xl w-full rounded-lg shadow p-8 overflow-y-auto max-h-[90vh]">
            <h2 class="text-xl font-bold mb-4">Edit Pricing</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium mb-1">Nama</label>
                            <input type="text" name="nama" id="edit_nama" class="w-full border p-2 rounded" required>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Title</label>
                            <input type="text" name="title" id="edit_title" class="w-full border p-2 rounded" required>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-3 gap-4">
                        <div>
                            <label class="block font-medium mb-1">Price (Rp)</label>
                            <input type="number" name="price" id="edit_price" min="0" class="w-full border p-2 rounded" required>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Diskon (%)</label>
                            <input type="number" name="diskon" id="edit_diskon" min="0" max="100" class="w-full border p-2 rounded">
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Status</label>
                            <input type="text" name="status" id="edit_status" class="w-full border p-2 rounded" required>
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium mb-1">Deskripsi</label>
                        <textarea name="deskripsi" id="edit_deskripsi" rows="4" class="w-full border p-2 rounded" placeholder="Optional description"></textarea>
                    </div>
                </div>

                <div class="text-right mt-6">
                    <button type="button" onclick="closeModal('editModal')"
                        class="bg-red-400 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============ DETAIL MODAL ============ -->
    <div id="detailModal" class="modal fixed inset-0 z-50 bg-black/40 justify-center items-center">
        <div class="bg-white max-w-lg w-full rounded-lg shadow p-6 overflow-y-auto max-h-[80vh]">
            <h3 id="detailNama" class="text-xl font-bold mb-2"></h3>
            <h4 id="detailTitle" class="text-lg text-gray-700 mb-4"></h4>

            <div class="space-y-3">
                <div>
                    <span class="font-semibold">Price:</span>
                    <span id="detailPrice" class="text-lg font-bold text-green-600"></span>
                </div>

                <div>
                    <span class="font-semibold">Diskon:</span>
                    <span id="detailDiskon"></span>
                </div>

                <div>
                    <span class="font-semibold">Total:</span>
                    <span id="detailTotal"></span>
                </div>

                <div>
                    <span class="font-semibold">Status:</span>
                    <span id="detailStatus" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"></span>
                </div>

                <div id="detailDescriptionSection">
                    <p class="font-semibold">Deskripsi:</p>
                    <p id="detailDeskripsi" class="text-gray-800 whitespace-pre-line mt-1"></p>
                </div>
            </div>

            <div class="text-right mt-6">
                <button onclick="closeModal('detailModal')"
                    class="bg-blue-400 text-white px-4 py-2 rounded">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ============ SCRIPT ============ -->
    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.add('open');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('open');
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data pricing akan hilang permanen!',
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
            fetch(`/pricing/${id}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(r => r.json())
                .then(d => {
                    const form = document.getElementById('editForm');
                    form.action = `/pricing/${id}`;

                    document.getElementById('edit_nama').value = d.nama;
                    document.getElementById('edit_title').value = d.title;
                    document.getElementById('edit_price').value = d.price;
                    document.getElementById('edit_diskon').value = d.diskon || '';
                    document.getElementById('edit_status').value = d.status;
                    document.getElementById('edit_deskripsi').value = d.deskripsi || '';

                    document.getElementById('editModal').classList.add('open');
                })
                .catch(err => {
                    console.error('Error fetching pricing data:', err);
                    Swal.fire('Error', 'Gagal mengambil data pricing', 'error');
                });
        }

        function openDetailModal(id) {
            fetch(`/pricing/${id}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
                .then(r => r.json())
                .then(d => {
                    document.getElementById('detailNama').textContent = d.nama;
                    document.getElementById('detailTitle').textContent = d.title;
                    document.getElementById('detailPrice').textContent =
                        'Rp ' + new Intl.NumberFormat('id-ID').format(d.price);
                    document.getElementById('detailDiskon').textContent =
                        d.diskon ? d.diskon + '%' : 'Tidak ada diskon';
                    document.getElementById('detailTotal').textContent =
                        'Rp ' + new Intl.NumberFormat('id-ID').format(d.total);

                    const statusElement = document.getElementById('detailStatus');
                    statusElement.textContent = d.status.charAt(0).toUpperCase() + d.status.slice(1);
                    statusElement.className =
                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' +
                        (d.status === 'active'
                            ? 'bg-green-100 text-green-800'
                            : 'bg-red-100 text-red-800');

                    const descSection = document.getElementById('detailDescriptionSection');
                    const descElement = document.getElementById('detailDeskripsi');
                    if (d.deskripsi && d.deskripsi.trim()) {
                        descElement.textContent = d.deskripsi;
                        descSection.style.display = 'block';
                    } else {
                        descSection.style.display = 'none';
                    }

                    document.getElementById('detailModal').classList.add('open');
                })
                .catch(err => {
                    console.error('Error fetching pricing data:', err);
                    Swal.fire('Error', 'Gagal mengambil data pricing', 'error');
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
