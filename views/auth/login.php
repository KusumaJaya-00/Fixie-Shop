<h1 class="text-2xl font-bold text-center">Masuk</h1>
<p class="text-gray-500 text-sm text-center mt-1 mb-6">Masuk ke akun Anda</p>

<form method="POST" class="max-w-md mx-auto rounded-xl border border-gray-200 bg-white p-6 shadow-sm space-y-4">
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
        <?php if (isset($errors['password'])): ?>
            <p class="text-red-600 text-sm mt-1"><?= htmlspecialchars($errors['password']) ?></p>
        <?php endif; ?>
    </div>

    <button type="submit" class="w-full rounded-lg bg-brand px-4 py-2 text-white font-medium hover:bg-brand-dark">
        Login
    </button>
</form>

<p class="text-center text-sm text-gray-500 mt-4">
    Belum punya akun?
    <a href="/register" class="text-brand hover:underline">Daftar</a>
</p>
