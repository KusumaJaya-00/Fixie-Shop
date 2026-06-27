<div class="max-w-lg">
    <a href="/admin/users" class="text-sm text-brand hover:underline">&larr; Kembali</a>
    <h1 class="text-xl font-bold mt-2 mb-6">Tambah User</h1>

    <form method="POST" class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                   class="w-full rounded-lg border <?= isset($errors['name']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand">
            <?php if (isset($errors['name'])): ?>
                <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['name']) ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium mb-1">Email <span class="text-red-500">*</span></label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                   class="w-full rounded-lg border <?= isset($errors['email']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand">
            <?php if (isset($errors['email'])): ?>
                <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['email']) ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium mb-1">Password <span class="text-red-500">*</span></label>
            <input type="password" id="password" name="password"
                   class="w-full rounded-lg border <?= isset($errors['password']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand">
            <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
            <?php if (isset($errors['password'])): ?>
                <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['password']) ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="role_id" class="block text-sm font-medium mb-1">Role</label>
            <select id="role_id" name="role_id"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-brand">
                <option value="2" <?= ((int)($_POST['role_id'] ?? 2) === 2) ? 'selected' : '' ?>>Buyer</option>
                <option value="1" <?= ((int)($_POST['role_id'] ?? 2) === 1) ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <button type="submit" class="rounded-lg bg-brand px-4 py-2 text-white font-medium hover:bg-brand-dark">
            Simpan
        </button>
    </form>
</div>
