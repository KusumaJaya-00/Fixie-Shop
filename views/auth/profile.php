<div class="max-w-2xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold">Akun Saya</h1>

    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Data Diri</h2>
        <form method="POST" action="/profile?action=update_profile">
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium mb-1">Nama Lengkap <span class="text-red-600">*</span></label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($data['name']) ?>"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-brand <?= isset($errors['name']) ? 'border-red-500' : '' ?>">
                    <?php if (isset($errors['name'])): ?>
                        <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['name']) ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium mb-1">No. HP <span class="text-red-600">*</span></label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($data['phone'] ?? '') ?>"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-brand <?= isset($errors['phone']) ? 'border-red-500' : '' ?>">
                    <?php if (isset($errors['phone'])): ?>
                        <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['phone']) ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" value="<?= htmlspecialchars($data['email']) ?>" disabled
                           class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-gray-500">
                    <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah.</p>
                </div>
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-brand px-4 py-2 text-white font-medium hover:bg-brand-dark">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <h2 class="text-lg font-semibold mb-4">Ganti Password</h2>
        <form method="POST" action="/profile?action=change_password">
            <div class="space-y-4">
                <div>
                    <label for="old_password" class="block text-sm font-medium mb-1">Password Lama <span class="text-red-600">*</span></label>
                    <input type="password" id="old_password" name="old_password"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-brand <?= isset($errors['old_password']) ? 'border-red-500' : '' ?>">
                    <?php if (isset($errors['old_password'])): ?>
                        <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['old_password']) ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="new_password" class="block text-sm font-medium mb-1">Password Baru <span class="text-red-600">*</span></label>
                    <input type="password" id="new_password" name="new_password"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-brand <?= isset($errors['new_password']) ? 'border-red-500' : '' ?>">
                    <?php if (isset($errors['new_password'])): ?>
                        <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['new_password']) ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <label for="new_password_confirm" class="block text-sm font-medium mb-1">Konfirmasi Password Baru <span class="text-red-600">*</span></label>
                    <input type="password" id="new_password_confirm" name="new_password_confirm"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-brand <?= isset($errors['new_password_confirm']) ? 'border-red-500' : '' ?>">
                    <?php if (isset($errors['new_password_confirm'])): ?>
                        <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['new_password_confirm']) ?></p>
                    <?php endif; ?>
                </div>
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-brand px-4 py-2 text-white font-medium hover:bg-brand-dark">
                    Ganti Password
                </button>
            </div>
        </form>
    </div>
</div>
