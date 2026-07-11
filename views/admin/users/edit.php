<form id="form-user-edit-<?= (int)$user['id'] ?>" method="POST" action="/admin/users/edit" class="flex flex-col gap-6">
    <input type="hidden" name="_csrf_token" value="<?= generateCsrfToken() ?>">
    <input type="hidden" name="id" value="<?= (int)($user['id']) ?>">
    <div>
        <label for="edit-name-<?= (int)$user['id'] ?>" class="block text-sm font-medium mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
        <input type="text" id="edit-name-<?= (int)$user['id'] ?>" name="name" value="<?= htmlspecialchars($_POST['name'] ?? $user['name']) ?>"
               class="w-full rounded-lg border <?= isset($errors['name']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand"
               required>
        <?php if (isset($errors['name'])): ?>
            <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['name']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="edit-phone-<?= (int)$user['id'] ?>" class="block text-sm font-medium mb-2">No. HP</label>
        <input type="tel" id="edit-phone-<?= (int)$user['id'] ?>" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? $user['phone']) ?>"
               class="w-full rounded-lg border <?= isset($errors['phone']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand"
               placeholder="08xxxxxxxxxx" pattern="[0-9]{10,13}" title="10-13 digit angka">
        <?php if (isset($errors['phone'])): ?>
            <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['phone']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="edit-email-<?= (int)$user['id'] ?>" class="block text-sm font-medium mb-2">Email <span class="text-red-500">*</span></label>
        <input type="email" id="edit-email-<?= (int)$user['id'] ?>" name="email" value="<?= htmlspecialchars($_POST['email'] ?? $user['email']) ?>"
               class="w-full rounded-lg border <?= isset($errors['email']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand"
               required>
        <?php if (isset($errors['email'])): ?>
            <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['email']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="edit-role-<?= (int)$user['id'] ?>" class="block text-sm font-medium mb-2">Role</label>
        <select id="edit-role-<?= (int)$user['id'] ?>" name="role_id"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-brand">
            <option value="2" <?= ((int)($user['role_id']) === 2) ? 'selected' : '' ?>>Buyer</option>
            <option value="1" <?= ((int)($user['role_id']) === 1) ? 'selected' : '' ?>>Admin</option>
        </select>
    </div>

    <button type="submit" class="w-full rounded-lg bg-brand px-4 py-2 text-white font-medium hover:bg-brand-dark">
        Simpan
    </button>
</form>