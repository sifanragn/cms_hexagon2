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
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden">

                            <div class="bg-white text-gray-800 w-full max-w-md rounded-2xl p-6 relative shadow-xl">

                                <!-- Close -->
                                <button class="absolute top-3 right-4 text-gray-500 hover:text-gray-700 text-xl"
                                    onclick="document.getElementById('editClientModal{{ $client->id }}').classList.add('hidden')">
                                    &times;
                                </button>

                                <h2 class="text-xl font-semibold text-center mb-6">Edit Client</h2>

                                <form action="{{ route('clients.update', $client->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <!-- DRAG & DROP UPLOAD -->
                                    <div class="mb-5">
                                        <label class="block text-sm font-medium mb-2">Logo Client</label>

                                        <div id="dropZoneEdit{{ $client->id }}"
                                        class="relative border-2 border-dashed border-gray-300 rounded-xl p-4
                                            bg-gray-50 text-center cursor-pointer hover:bg-gray-100 transition">

                                        <!-- Zoom Button -->
                                        <button type="button" id="zoomBtnEdit{{ $client->id }}"
                                        class="absolute top-2 right-2 w-7 h-7 bg-white rounded-full shadow
                                            flex items-center justify-center text-gray-700 hover:bg-gray-200 z-20"
                                        style="pointer-events: auto;">
                                        <span style="pointer-events: none;">🔍</span>
                                    </button>

                                        <!-- File Input -->
                                        <input type="file"
                                            id="fileInputEdit{{ $client->id }}"
                                            name="foto_client"
                                            class="hidden"
                                            accept="image/png,image/jpeg,image/webp">

                                        <!-- Preview -->
                                        <img id="previewEdit{{ $client->id }}"
                                            src="{{ asset('storage/' . $client->foto_client) }}"
                                            class="w-28 h-28 mx-auto mt-3 object-contain rounded-lg cursor-pointer" />

                                        <!-- Placeholder (tidak dipakai saat edit, tapi tetap disiapkan) -->
                                        <p id="placeholderEdit{{ $client->id }}" class="text-gray-500 mt-2 hidden">
                                            Drag & Drop atau klik untuk pilih ulang gambar<br>
                                            <span class="text-xs text-gray-400">PNG, JPG, WEBP</span>
                                        </p>
                                    </div>

                                    <p id="fileNameEdit{{ $client->id }}" class="text-xs text-gray-500 mt-1">
                                        Current: {{ $client->foto_client }}
                                    </p>

                                    </div>

                                    <!-- NAME -->
                                    <div class="mb-5">
                                        <label class="block text-sm font-medium mb-1">Nama</label>
                                        <input type="text" name="name" value="{{ $client->name }}"
                                            class="w-full px-4 py-2 border-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
                                            required>
                                    </div>

                                    <!-- STATUS -->
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium mb-2">Status</label>
                                        <div class="flex flex-wrap gap-4">
                                            <label class="flex items-center gap-2 text-sm">
                                                <input type="radio" name="status" value="1" class="accent-blue-600"
                                                    {{ $client->status == 1 ? 'checked' : '' }}>
                                                Our Client
                                            </label>
                                            <label class="flex items-center gap-2 text-sm">
                                                <input type="radio" name="status" value="0" class="accent-blue-600"
                                                    {{ $client->status == 0 ? 'checked' : '' }}>
                                                Our Media
                                            </label>
                                            <label class="flex items-center gap-2 text-sm">
                                                <input type="radio" name="status" value="2" class="accent-blue-600"
                                                    {{ $client->status == 2 ? 'checked' : '' }}>
                                                Mitra
                                            </label>
                                            <label class="flex items-center gap-2 text-sm">
                                                <input type="radio" name="status" value="3" class="accent-blue-600"
                                                    {{ $client->status == 3 ? 'checked' : '' }}>
                                                SMK Binaan
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <button type="button"
                                            class="bg-gray-100 text-gray-800 px-4 py-2 rounded"
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
    <div id="addClientModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden">

        <div class="bg-white text-gray-800 w-full max-w-md rounded-2xl p-6 relative shadow-xl">

            <!-- Close Button -->
            <button class="absolute top-3 right-4 text-gray-500 hover:text-gray-700 text-xl"
                onclick="document.getElementById('addClientModal').classList.add('hidden')">
                &times;
            </button>

            <h2 class="text-xl font-semibold text-center mb-6">Tambah Client Baru</h2>

            <form action="{{ route('clients.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- DRAG & DROP UPLOAD -->
                <div class="mb-5">
                    <label class="block text-sm font-medium mb-2">Logo Client</label>

                    <div id="dropZoneAdd" class="relative border-2 border-dashed
                       border-gray-300 rounded-xl p-4
                            bg-gray-50 text-center cursor-pointer hover:bg-gray-100 transition">
                              <!-- Zoom button -->
                            <button type="button" id="zoomBtnAdd"
                                class="hidden absolute top-2 right-2 w-7 h-7 bg-white rounded-full shadow
                                flex items-center justify-center text-gray-700 hover:bg-gray-200 z-20">
                                🔍
                            </button>
                        <input type="file"
                            id="fileInputAdd"
                            name="foto_client"
                            class="hidden"
                            accept="image/png,image/jpeg,image/webp">

                        <!-- Preview -->
                        <img id="previewAdd"
                            class="w-28 h-28 mx-auto mt-3 object-contain rounded-lg hidden cursor-pointer" />

                        <!-- Placeholder text -->
                        <p id="placeholderAdd" class="text-gray-500">
                            Drag & Drop atau Klik untuk pilih gambar<br>
                            <span class="text-xs text-gray-400">Format: PNG, JPG, WEBP</span>
                        </p>
                    </div>

                    <p id="fileNameAdd" class="text-xs text-gray-500 mt-1">Belum ada file</p>
                </div>

                <!-- INPUT NAME -->
                <div class="mb-5">
                    <label class="block text-sm font-medium mb-1">Nama</label>
                    <input type="text" name="name" placeholder="Masukkan nama client"
                        class="w-full px-4 py-2 border-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600"
                        required>
                </div>

                <!-- STATUS -->
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2">Status</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="radio" name="status" value="1" class="accent-blue-600" checked>
                            Our Client
                        </label>

                        <label class="flex items-center gap-2 text-sm">
                            <input type="radio" name="status" value="0" class="accent-blue-600">
                            Our Media
                        </label>

                        <label class="flex items-center gap-2 text-sm">
                            <input type="radio" name="status" value="2" class="accent-blue-600">
                            Mitra
                        </label>

                        <label class="flex items-center gap-2 text-sm">
                            <input type="radio" name="status" value="3" class="accent-blue-600">
                            SMK Binaan
                        </label>
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="flex justify-end gap-3">
                    <button type="button"
                        class="bg-gray-100 px-4 py-2 rounded text-gray-800"
                        onclick="document.getElementById('addClientModal').classList.add('hidden')">
                        Batal
                    </button>

                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-white">
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

    <script>
// ============================
// IMAGE POPUP
// ============================
function showImagePopup(src) {
    const popup = document.createElement("div");
    popup.className =
        "fixed inset-0 bg-black/70 flex items-center justify-center z-[9999]";
    popup.innerHTML = `
        <img src="${src}" class="max-w-[90%] max-h-[90%] rounded-lg shadow-2xl" />
    `;
    popup.onclick = () => popup.remove();
    document.body.appendChild(popup);
}

// ============================
// DRAG & DROP ADD
// ============================
function setupAddDragDrop() {
    const dropZone = document.getElementById("dropZoneAdd");
    const fileInput = document.getElementById("fileInputAdd");
    const preview = document.getElementById("previewAdd");
    const placeholder = document.getElementById("placeholderAdd");
    const fileName = document.getElementById("fileNameAdd");
    const zoomBtn = document.getElementById("zoomBtnAdd");

    dropZone.addEventListener("click", (e) => {
        if (e.target.id === "zoomBtnAdd") return; // ⛔ jangan buka file dialog
        fileInput.click();
    });

    dropZone.addEventListener("dragover", e => {
        e.preventDefault();
        dropZone.classList.add("bg-gray-200");
    });

    dropZone.addEventListener("dragleave", () => {
        dropZone.classList.remove("bg-gray-200");
    });

    dropZone.addEventListener("drop", e => {
        e.preventDefault();
        dropZone.classList.remove("bg-gray-200");
        handleFile(e.dataTransfer.files[0]);
    });

    fileInput.addEventListener("change", () => {
        handleFile(fileInput.files[0]);
    });

    function handleFile(file) {
        if (!file) return;

        const allowed = ["image/png", "image/jpeg", "image/webp"];
        if (!allowed.includes(file.type)) {
            alert("❌ Format harus PNG / JPG / WEBP");
            return;
        }

        preview.src = URL.createObjectURL(file);
        preview.classList.remove("hidden");
        placeholder.classList.add("hidden");

        fileName.textContent = file.name;

        zoomBtn.classList.remove("hidden");
        zoomBtn.onclick = () => showImagePopup(preview.src);
    }
}

document.addEventListener("DOMContentLoaded", setupAddDragDrop);
</script>
<script>
// ============================
// DRAG & DROP EDIT
// ============================
function setupEditDragDrop(id) {

    const dropZone = document.getElementById("dropZoneEdit" + id);
    const fileInput = document.getElementById("fileInputEdit" + id);
    const preview = document.getElementById("previewEdit" + id);
    const placeholder = document.getElementById("placeholderEdit" + id);
    const fileName = document.getElementById("fileNameEdit" + id);
    const zoomBtn = document.getElementById("zoomBtnEdit" + id);

    // Buka file dialog, kecuali klik tombol zoom
    dropZone.addEventListener("click", (e) => {
        if (e.target.id === "zoomBtnEdit" + id) return;
        fileInput.click();
    });

    dropZone.addEventListener("dragover", e => {
        e.preventDefault();
        dropZone.classList.add("bg-gray-200");
    });

    dropZone.addEventListener("dragleave", () => {
        dropZone.classList.remove("bg-gray-200");
    });

    dropZone.addEventListener("drop", e => {
        e.preventDefault();
        dropZone.classList.remove("bg-gray-200");
        handleFile(e.dataTransfer.files[0]);
    });

    fileInput.addEventListener("change", () => {
        handleFile(fileInput.files[0]);
    });

    function handleFile(file) {
        if (!file) return;

        const allowed = ["image/png", "image/jpeg", "image/webp"];
        if (!allowed.includes(file.type)) {
            alert("❌ Format harus PNG / JPG / WEBP");
            return;
        }

        preview.src = URL.createObjectURL(file);
        placeholder.classList.add("hidden");

        fileName.textContent = file.name;
    }

    zoomBtn.onclick = () => showImagePopup(preview.src);
}

// Register semua edit modal
document.addEventListener("DOMContentLoaded", () => {
    @foreach ($clients as $client)
        setupEditDragDrop({{ $client->id }});
    @endforeach
});
</script>

@endsection
