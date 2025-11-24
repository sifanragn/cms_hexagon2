@extends('layouts.app')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Our Client</h2>
            <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded shadow"
                onclick="document.getElementById('addClientModal').classList.remove('hidden')">
                + Add New Client
            </button>
        </div>

        <!-- Search -->
        <div class="mb-4">
            <input type="text" id="searchClient" placeholder=" Search client..."
                class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <!-- Table -->
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium">NAME</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">LOGO</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">STATUS</th>
                        <th class="px-6 py-3 text-right text-sm font-medium">ACTIONS</th>
                    </tr>
                </thead>
                <tbody id="clientTable" class="divide-y divide-gray-100 text-gray-800">
                    @foreach ($clients as $client)
                        <tr>
                            <td class="px-6 py-4">{{ $client->name }}</td>
                            <td class="px-6 py-4">
                               <img src="{{ asset('storage/' . $client->foto_client) }}" alt="Logo" class="w-12 h-12 object-contain">
                            </td>
                            <td class="px-6 py-4">
                                @if ($client->status == 1)
                                    Our Client
                                @elseif ($client->status == 2)
                                    Mitra
                                @elseif ($client->status == 3)
                                    SMK Binaan
                                @else
                                    Media
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button onclick="openEditModal({{ $client->id }})"
                                    class="text-blue-600 hover:text-blue-800 text-lg transition" title="Edit">
                                    <i class="fas fa-pen-to-square"></i>
                                </button>

                                <!-- Delete dengan SweetAlert -->
                                <form id="deleteForm{{ $client->id }}"
                                    action="{{ route('clients.destroy', $client->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete({{ $client->id }})"
                                        class="text-red-600 hover:text-red-800 text-lg transition" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div id="editClientModal{{ $client->id }}"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-white/30 backdrop-blur-sm hidden">
                            <div class="bg-white text-gray-800 
                                w-full max-w-md rounded-xl p-6 relative shadow-lg">

                                <button class="absolute top-3 right-4 text-gray-500 hover:text-gray-700 text-xl"
                                    onclick="document.getElementById('editClientModal{{ $client->id }}').classList.add('hidden')">
                                    &times;
                                </button>

                                <h2 class="text-xl font-semibold text-center text-gray-800 mb-6">Edit Client</h2>

                                <form action="{{ route('clients.update', $client->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="flex justify-center mb-6">
                                        <div
                                            class="w-24 h-24 rounded-full border-2 border-dashed border-gray-300 overflow-hidden">
                                            <img id="editPreviewImage{{ $client->id }}"
                                                src="{{ asset('storage/' . $client->foto_client) }}" alt="Preview"
                                                class="object-contain w-full h-full" />
                                        </div>
                                    </div>


                                    <div class="flex items-center gap-2 mb-5">
                                        <label
                                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded cursor-pointer">
                                            CHOOSE FILE
                                            <input type="file" name="foto_client" class="hidden"
                                                onchange="previewEditImage(event, {{ $client->id }})">
                                        </label>
                                        <span id="editFileName{{ $client->id }}" class="text-gray-500 text-sm">Current:
                                            {{ $client->foto_client }}</span>
                                    </div>

                                    <div class="mb-5">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                                        <input type="text" name="name" required value="{{ $client->name }}"
                                            class="w-full px-4 py-2 border-2  rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600" />
                                    </div>

                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                        <div class="flex gap-4">
                                            <label class="inline-flex items-center gap-2 text-sm">
                                                <input type="radio" name="status" value="1" class="accent-blue-600"
                                                    {{ $client->status == 1 ? 'checked' : '' }}>
                                                Our Client
                                            </label>
                                            <label class="inline-flex items-center gap-2 text-sm">
                                                <input type="radio" name="status" value="0" class="accent-blue-600"
                                                    {{ $client->status == 0 ? 'checked' : '' }}>
                                                Our Media
                                            </label>
                                            <label class="inline-flex items-center gap-2 text-sm">
                                                <input type="radio" name="status" value="2" class="accent-blue-600"
                                                    {{ $client->status == 2 ? 'checked' : '' }}>
                                                Mitra
                                            </label>
                                            <label class="inline-flex items-center gap-2 text-sm">
                                                <input type="radio" name="status" value="3" class="accent-blue-600"
                                                    {{ $client->status == 3 ? 'checked' : '' }}>
                                                SMK Binaan
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <button type="button" class="bg-gray-100 text-gray-800 px-4 py-2 rounded"
                                            onclick="document.getElementById('editClientModal{{ $client->id }}').classList.add('hidden')">
                                            Batal
                                        </button>
                                        <button type="submit"
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                            Update
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addClientModal" class="fixed inset-0 z-50 flex items-center justify-center bg-white/30 hidden">
       <div class="bg-white text-gray-800 
    w-full max-w-md rounded-xl p-6 relative shadow-lg">

            <button class="absolute top-3 right-4 text-gray-500 hover:text-gray-700 text-xl"
                onclick="document.getElementById('addClientModal').classList.add('hidden')">
                &times;
            </button>

            <h2 class="text-xl font-semibold text-center text-gray-800 mb-6">Tambah Client Baru</h2>

            <form action="{{ route('clients.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="flex justify-center mb-6">
                    <div id="addPreviewContainer"
                    class="w-24 h-24 rounded-full border-2 
                        border-gray-300 dark:border-gray-600 
                        bg-gray-100 dark:bg-gray-700
                        flex items-center justify-center overflow-hidden">
                        <img id="addPreviewImage" src="{{ asset('images/preview-icon.png') }}" alt="Preview"
                            class="object-contain w-full h-full hidden" />
                        <span id="addPlaceholderIcon"><i class="fas fa-image"></i></span>
                    </div>
                </div>

                <div class="flex items-center gap-2 mb-5">
                    <label
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded cursor-pointer">
                        CHOOSE FILE
                        <input type="file" name="foto_client" class="hidden" onchange="previewAddImage(event)">
                    </label>
                    <span id="addFileName" class="text-gray-500 text-sm">No file chosen</span>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="name" placeholder="Masukkan nama client"
                        class="w-full px-4 py-2 border-2  rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
                        required />
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="radio" name="status" value="1" class="accent-blue-600" checked />
                            Our Client
                        </label>
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="radio" name="status" value="0" class="accent-blue-600" />
                            Our Media
                        </label>
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="radio" name="status" value="2" class="accent-blue-600" />
                            Mitra
                        </label>
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="radio" name="status" value="3" class="accent-blue-600">
                            SMK Binaan
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" class="bg-gray-100 text-gray-800 px-4 py-2 rounded"
                        onclick="document.getElementById('addClientModal').classList.add('hidden')">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function openEditModal(id) {
            const modal = document.getElementById('editClientModal' + id);
            if (modal) modal.classList.remove('hidden');
        }

        function previewEditImage(e, id) {
            const f = e.target.files[0];
            if (f && f.type.startsWith('image/')) {
                document.getElementById('editPreviewImage' + id).src = URL.createObjectURL(f);
                document.getElementById('editFileName' + id).textContent = f.name;
            }
        }

        function previewAddImage(e) {
            const f = e.target.files[0];
            if (f && f.type.startsWith('image/')) {
                document.getElementById('addPreviewImage').src = URL.createObjectURL(f);
                document.getElementById('addPreviewImage').classList.remove('hidden');
                document.getElementById('addPlaceholderIcon').classList.add('hidden');
                document.getElementById('addFileName').textContent = f.name;
            }
        }

        // SweetAlert untuk konfirmasi hapus
        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data ini tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }

        // Notifikasi sukses dari session
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        @endif
    </script>
@endsection
