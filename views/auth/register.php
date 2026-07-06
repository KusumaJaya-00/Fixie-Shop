<h1 class="text-2xl font-bold text-center">Daftar Akun</h1>
<p class="text-gray-500 text-sm text-center mt-1 mb-6">Buat akun baru untuk mulai berbelanja</p>

<form method="POST" class="max-w-md mx-auto rounded-xl border border-gray-200 bg-white p-6 shadow-sm space-y-4">
    <div>
        <label for="name" class="block text-sm font-medium mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required
               class="w-full rounded-lg border <?= isset($errors['name']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand">
        <?php if (isset($errors['name'])): ?>
            <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['name']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="phone" class="block text-sm font-medium mb-1">No. HP <span class="text-red-500">*</span></label>
        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
               required pattern="[0-9]{10,13}" title="10-13 digit angka"
               class="w-full rounded-lg border <?= isset($errors['phone']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand">
        <?php if (isset($errors['phone'])): ?>
            <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['phone']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="email" class="block text-sm font-medium mb-1">Email <span class="text-red-500">*</span></label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required
               class="w-full rounded-lg border <?= isset($errors['email']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand">
        <?php if (isset($errors['email'])): ?>
            <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['email']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="password" class="block text-sm font-medium mb-1">Password <span class="text-red-500">*</span></label>
        <input type="password" id="password" name="password" required minlength="8"
               class="w-full rounded-lg border <?= isset($errors['password']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand">
        <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
        <?php if (isset($errors['password'])): ?>
            <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['password']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="password_confirm" class="block text-sm font-medium mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
        <input type="password" id="password_confirm" name="password_confirm" required minlength="8"
               class="w-full rounded-lg border <?= isset($errors['password_confirm']) ? 'border-red-500' : 'border-gray-300' ?> px-3 py-2 focus:ring-2 focus:ring-brand">
        <?php if (isset($errors['password_confirm'])): ?>
            <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['password_confirm']) ?></p>
        <?php endif; ?>
    </div>

    <?php if (isset($errors['general'])): ?>
        <p class="text-red-600 text-sm text-center"><?= htmlspecialchars($errors['general']) ?></p>
    <?php endif; ?>

    <button type="submit" class="w-full rounded-lg bg-brand px-4 py-2 text-white font-medium hover:bg-brand-dark">
        Daftar
    </button>
</form>

<p class="text-center text-sm text-gray-500 mt-4">
    Sudah punya akun?
    <a href="/login" class="text-brand hover:underline">Login</a>
</p>
