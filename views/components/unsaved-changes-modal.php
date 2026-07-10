<div id="modal-unsaved" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black/40" onclick="if(event.target===this) closeUnsavedModal()">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold">Perubahan Belum Disimpan</h2>
            <button type="button" onclick="closeUnsavedModal()"
                    class="text-gray-400 hover:text-gray-700 text-xl leading-none">&times;</button>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-700">
                Anda memiliki perubahan yang belum disimpan. Apa yang ingin dilakukan?
            </p>
        </div>
        <div class="flex gap-2 px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
            <button type="button" onclick="closeUnsavedModal()"
                    class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Batal
            </button>
            <button type="button" onclick="unsavedDiscard()"
                    class="flex-1 rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                Buang
            </button>
            <button type="button" onclick="unsavedSave()"
                    class="flex-1 rounded-lg bg-brand px-4 py-2 text-sm font-medium text-white hover:bg-brand-dark">
                Simpan
            </button>
        </div>
    </div>
</div>

<script>
let unsavedContext = null;
let unsavedForm = null;

function openUnsavedModal(contextModal, form) {
    unsavedContext = contextModal;
    unsavedForm = form;
    document.getElementById('modal-unsaved').classList.remove('hidden');
}

function closeUnsavedModal() {
    document.getElementById('modal-unsaved').classList.add('hidden');
    unsavedContext = null;
    unsavedForm = null;
}

function unsavedSave() {
    if (unsavedForm) {
        unsavedForm.submit();
    }
}

function unsavedDiscard() {
    if (unsavedForm) {
        unsavedForm.reset();
    }
    if (unsavedContext) {
        unsavedContext.classList.add('hidden');
    }
    closeUnsavedModal();
}

function bindDirtyTracking(modalId, formId) {
    const modal = document.getElementById(modalId);
    const form = document.getElementById(formId);
    if (!modal || !form) return;

    const markDirty = () => { modal.dataset.dirty = 'true'; };
    const markClean = () => { modal.dataset.dirty = 'false'; };

    form.querySelectorAll('input, select, textarea').forEach(el => {
        el.addEventListener('input', markDirty);
        el.addEventListener('change', markDirty);
    });

    form.addEventListener('submit', markClean);

    const tryClose = (e) => {
        if (modal.dataset.dirty === 'true') {
            e.preventDefault();
            e.stopPropagation();
            openUnsavedModal(modal, form);
        }
    };

    modal.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', tryClose);
    });

    const backdrop = modal;
    backdrop.addEventListener('click', (e) => {
        if (e.target === backdrop) {
            tryClose(e);
        }
    });
}
</script>