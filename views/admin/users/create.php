<form id="form-user-create" method="POST" action="/admin/users/create" class="flex flex-col gap-6">
    <input type="hidden" name="_csrf_token" value="<?= generateCsrfToken() ?>">
    <div>
        <label for="name" class="block text-sm font-medium mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
               class="w-full rounded-lg border <?= isset($errors['name']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand"
               required>
        <?php if (isset($errors['name'])): ?>
            <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['name']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="phone" class="block text-sm font-medium mb-2">No. HP</label>
        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
               class="w-full rounded-lg border <?= isset($errors['phone']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand"
               placeholder="08xxxxxxxxxx" pattern="[0-9]{10,13}" title="10-13 digit angka">
        <?php if (isset($errors['phone'])): ?>
            <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['phone']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="email" class="block text-sm font-medium mb-2">Email <span class="text-red-500">*</span></label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
               class="w-full rounded-lg border <?= isset($errors['email']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand"
               required>
        <?php if (isset($errors['email'])): ?>
            <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['email']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="password" class="block text-sm font-medium mb-2">Password <span class="text-red-500">*</span></label>
        <input type="password" id="password" name="password"
               class="w-full rounded-lg border <?= isset($errors['password']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand"
               required minlength="8">
        <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
        <?php if (isset($errors['password'])): ?>
            <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['password']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="role_id" class="block text-sm font-medium mb-2">Role</label>
        <select id="role_id" name="role_id"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-brand">
            <option value="2" <?= ((int)($_POST['role_id'] ?? 2) === 2) ? 'selected' : '' ?>>Buyer</option>
            <option value="1" <?= ((int)($_POST['role_id'] ?? 2) === 1) ? 'selected' : '' ?>>Admin</option>
        </select>
    </div>

    <button type="submit" class="w-full rounded-lg bg-brand px-4 py-2 text-white font-medium hover:bg-brand-dark">
        Simpan
    </button>
</form>