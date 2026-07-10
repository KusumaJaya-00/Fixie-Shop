<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold">Kelola User</h1>
    <button type="button" data-open-modal="modal-user-create"
            class="rounded-lg bg-brand px-4 py-2 text-sm text-white font-medium hover:bg-brand-dark">
        + Tambah User
    </button>
</div>

<div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-4 py-3 font-semibold">ID</th>
                <th class="px-4 py-3 font-semibold">Nama</th>
                <th class="px-4 py-3 font-semibold">Email</th>
                <th class="px-4 py-3 font-semibold">Role</th>
                <th class="px-4 py-3 font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <?php foreach ($users as $u): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3"><?= (int)$u['id'] ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($u['name']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($u['email']) ?></td>
                    <td class="px-4 py-3">
                        <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium <?= $u['role_name'] === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' ?>">
                            <?= htmlspecialchars($u['role_name']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 flex gap-3">
                        <button type="button" data-open-modal="modal-user-edit-<?= (int)$u['id'] ?>"
                                class="text-brand hover:underline text-xs">Edit</button>
                        <button type="button"
                                onclick="openDeleteModal('<?= htmlspecialchars(addslashes($u['name']), ENT_QUOTES) ?>', '/admin/users/delete?id=<?= (int)$u['id'] ?>')"
                                class="text-red-600 hover:underline text-xs">Hapus</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada user.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal Tambah User -->
<div id="modal-user-create" data-dirty="false" class="<?= !empty($errors) ? 'fixed inset-0 z-50 flex items-center justify-center bg-black/40' : 'hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40' ?>" onclick="if(event.target===this) closeModalProtected('modal-user-create')">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4 max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-bold">Tambah User</h2>
            <button type="button" data-close-modal onclick="closeModalProtected('modal-user-create')"
                    class="text-gray-400 hover:text-gray-700 text-xl leading-none">&times;</button>
        </div>
        <div class="p-6 overflow-y-auto">
                <?php require __DIR__ . '/create.php'; ?>
        </div>
    </div>
</div>

<!-- Modal Edit User (satu per baris) -->
<?php foreach ($users as $u): ?>
    <?php
    $editFormId = 'form-user-edit-' . (int)$u['id'];
    $editModalId = 'modal-user-edit-' . (int)$u['id'];
    $editErrKey = 'edit_' . (int)$u['id'];
    $editErrors = $errors[$editErrKey] ?? [];
    $showEditModal = !empty($editErrors);
    ?>
    <div id="<?= $editModalId ?>" data-dirty="false" class="<?= $showEditModal ? 'fixed inset-0 z-50 flex items-center justify-center bg-black/40' : 'hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40' ?>" onclick="if(event.target===this) closeModalProtected('<?= $editModalId ?>')">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg mx-4 max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-bold">Edit User: <?= htmlspecialchars($u['name']) ?></h2>
                <button type="button" data-close-modal onclick="closeModalProtected('<?= $editModalId ?>')"
                        class="text-gray-400 hover:text-gray-700 text-xl leading-none">&times;</button>
            </div>
            <div class="p-6 overflow-y-auto">
                <?php
                $user = $u;
                $errors = $editErrors;
                ?>
                    <?php require __DIR__ . '/edit.php'; ?>
                <?php
                unset($user, $editErrors);
                ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php require __DIR__ . '/../../components/delete-confirm-modal.php'; ?>
<?php require __DIR__ . '/../../components/unsaved-changes-modal.php'; ?>

<script>
function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
}

function closeModal(id) {
    const el = document.getElementById(id);
    el.classList.add('hidden');
    el.dataset.dirty = 'false';
    const form = el.querySelector('form');
    if (form) form.reset();
}

function closeModalProtected(id) {
    const el = document.getElementById(id);
    if (el.dataset.dirty === 'true') {
        const form = el.querySelector('form');
        if (form) {
            openUnsavedModal(el, form);
            return;
        }
    }
    closeModal(id);
}

document.querySelectorAll('[data-open-modal]').forEach(btn => {
    btn.addEventListener('click', () => openModal(btn.dataset.openModal));
});

document.querySelectorAll('[id^="modal-user-"]').forEach(modal => {
    const form = modal.querySelector('form');
    if (!form) return;
    form.querySelectorAll('input, select, textarea').forEach(el => {
        el.addEventListener('input', () => { modal.dataset.dirty = 'true'; });
        el.addEventListener('change', () => { modal.dataset.dirty = 'true'; });
    });
    form.addEventListener('submit', () => { modal.dataset.dirty = 'false'; });
});
</script>