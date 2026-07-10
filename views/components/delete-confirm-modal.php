<div id="modal-delete-confirm" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40" onclick="if(event.target===this) closeDeleteModal()">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold">Konfirmasi Hapus</h2>
            <button type="button" onclick="closeDeleteModal()"
                    class="text-gray-400 hover:text-gray-700 text-xl leading-none">&times;</button>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-700">
                Yakin ingin menghapus <strong id="delete-confirm-name" class="text-gray-900"></strong>?
            </p>
            <p class="text-xs text-gray-500 mt-2">Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <div class="flex gap-2 px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
            <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Batal
            </button>
            <a id="delete-confirm-btn" href="#"
               class="flex-1 text-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                Hapus
            </a>
        </div>
    </div>
</div>

<script>
function openDeleteModal(name, url) {
    document.getElementById('delete-confirm-name').textContent = name;
    document.getElementById('delete-confirm-btn').setAttribute('href', url);
    document.getElementById('modal-delete-confirm').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('modal-delete-confirm').classList.add('hidden');
}
</script>