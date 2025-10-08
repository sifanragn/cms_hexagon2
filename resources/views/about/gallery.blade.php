@extends('layouts.app')

@section('content')
    @include('about._tabs')

    <div class="mb-6">
        <div class="flex justify-between items-center mb-6 px-6 max-w-4xl mx-auto">
            <h2 class="text-3xl font-semibold text-gray-800">Gallery</h2>
            <div class="relative inline-block text-left self-center md:self-auto">
                <button onclick="toggleDropdown()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    <i class="fas fa-bars mr-2"></i> Menu
                </button>
                <div id="dropdown" class="hidden absolute right-0 mt-2 w-32 bg-white shadow-lg rounded z-50">
                    <button onclick="openAddModal()" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-green-600">
                        <i class="fas fa-plus mr-1"></i>Add</button>
                    <button onclick="openEditModal()" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-blue-600">
                        <i class="fas fa-edit mr-1"></i>Edit</button>
                    <button onclick="confirmDelete()" class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">
                        <i class="fas fa-trash mr-1"></i>Delete</button>
                </div>
            </div>
        </div>
    </div>

    @if ($galleries->count())
        <div class="rounded overflow-hidden shadow mb-6 max-w-4xl mx-auto aspect-video">
            <img id="mainImage" src="{{ asset('storage/' . $galleries->first()->image) }}"
                class="w-full h-full object-cover rounded transition-all duration-300" />
        </div>
    @endif

    <div class="flex flex-wrap gap-3 justify-center mb-6">
        @foreach ($galleries as $gallery)
            <div class="w-24 h-24 cursor-pointer rounded overflow-hidden border-2 border-transparent hover:border-blue-500 transition gallery-item"
                data-id="{{ $gallery->id }}" data-image="{{ asset('storage/' . $gallery->image) }}"
                data-delete-url="{{ route('about.gallery.delete', $gallery->id) }}"
                data-update-url="{{ route('about.gallery.update', $gallery->id) }}" onclick="selectGalleryItem(this)">
                <img src="{{ asset('storage/' . $gallery->image) }}" class="object-cover h-full w-full" alt="Gallery image">

                <!-- Hidden delete form -->
                <form method="POST" action="{{ route('about.gallery.delete', $gallery->id) }}" class="delete-form"
                    style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        @endforeach
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="fixed inset-0 z-50 bg-gray-900/70 flex items-center justify-center hidden">
        <div
            class="bg-white rounded-lg p-6 w-[400px] max-w-[90vw] shadow-2xl transform transition-all duration-300 scale-95 opacity-0">
            <h3 class="text-xl font-bold mb-4 text-blue-600">Upload New Images</h3>
            <form method="POST" action="{{ route('about.gallery.store') }}" enctype="multipart/form-data" id="addForm">
                @csrf
                <div id="fileInputs">
                    <input type="file" name="image[]" class="mb-2 block w-full border p-2 rounded" multiple required
                        accept="image/*">
                </div>
                <button type="button" onclick="addMoreFiles()"
                    class="bg-blue-600 text-white w-full py-2 rounded mb-2 hover:bg-blue-700">
                    Add More Files
                </button>
                <div class="flex justify-between gap-2">
                    <button type="button" onclick="closeAddModal()"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 bg-gray-900/70 flex items-center justify-center hidden">
        <div
            class="bg-white rounded-lg p-6 w-[400px] max-w-[90vw] shadow-2xl transform transition-all duration-300 scale-95 opacity-0">
            <h3 class="text-xl font-bold mb-4 text-blue-600">Edit Image</h3>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="editImageInput" class="block text-sm font-medium text-gray-700 mb-2">Choose New
                        Image:</label>
                    <input type="file" id="editImageInput" name="image" class="block w-full border p-2 rounded"
                        required accept="image/*">
                </div>
                <div class="flex justify-between gap-2">
                    <button type="button" onclick="closeEditModal()"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancel</button>
                    <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 z-60 bg-gray-900/40 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 text-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-700">Processing...</p>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let selectedGalleryItem = null;

        function toggleDropdown() {
            document.getElementById('dropdown').classList.toggle('hidden');
        }

        function selectGalleryItem(element) {
            document.querySelectorAll('.gallery-item').forEach(item => {
                item.classList.remove('border-blue-500');
                item.classList.add('border-transparent');
            });

            element.classList.add('border-blue-500');
            selectedGalleryItem = element;

            const mainImage = document.getElementById('mainImage');
            if (mainImage) mainImage.src = element.dataset.image;

            document.getElementById('dropdown').classList.add('hidden');
        }

        // ✅ Perbaikan fungsi modal tambah
        function openAddModal() {
            const modal = document.getElementById('addModal');
            const content = modal.querySelector('.bg-white'); // ambil div konten dalam modal

            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);

            document.getElementById('dropdown').classList.add('hidden');
        }

        function closeAddModal() {
            const modal = document.getElementById('addModal');
            const content = modal.querySelector('.bg-white'); // pastikan sama
            const form = document.getElementById('addForm');

            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                if (form) form.reset();
            }, 200);
        }


        function openEditModal() {
            if (!selectedGalleryItem) {
                Swal.fire('Peringatan', 'Silakan pilih gambar yang ingin diedit terlebih dahulu.', 'warning');
                document.getElementById('dropdown').classList.add('hidden');
                return;
            }

            const editForm = document.getElementById('editForm');
            const updateUrl = selectedGalleryItem.dataset.updateUrl;

            editForm.setAttribute('action', updateUrl);

            const modal = document.getElementById('editModal');
            const content = modal.querySelector('div');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
            document.getElementById('dropdown').classList.add('hidden');
        }

        function closeEditModal() {
            const form = document.getElementById('editForm');
            form.reset();
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
        }

        function confirmDelete() {
            if (!selectedGalleryItem) {
                Swal.fire('Peringatan', 'Silakan pilih gambar yang ingin dihapus terlebih dahulu.', 'warning');
                document.getElementById('dropdown').classList.add('hidden');
                return;
            }

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    const deleteForm = selectedGalleryItem.querySelector('.delete-form');
                    if (deleteForm) deleteForm.submit();
                }
            });

            document.getElementById('dropdown').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const firstItem = document.querySelector('.gallery-item');
            if (firstItem) selectGalleryItem(firstItem);

            document.getElementById('editForm')?.addEventListener('submit', () => {
                document.getElementById('loadingOverlay').classList.remove('hidden');
            });
            document.getElementById('addForm')?.addEventListener('submit', () => {
                document.getElementById('loadingOverlay').classList.remove('hidden');
            });
        });

        // ✅ Notifikasi sukses/error
        @if (session('success') || session('status'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') ?? session('status') }}",
                timer: 2000,
                showConfirmButton: false
            });
        @endif

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                html: '<ul style="text-align: left;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>'
            });
        @endif

        // Tambah file input baru
        function addMoreFiles() {
            const fileInputs = document.getElementById('fileInputs');
            const newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.name = 'image[]';
            newInput.required = true;
            newInput.accept = 'image/*';
            newInput.className = 'mb-2 block w-full border p-2 rounded';
            fileInputs.appendChild(newInput);
        }
    </script>
@endsection
