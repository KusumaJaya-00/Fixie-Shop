<?php if (isset($_SESSION['flash'])): ?>
    <div class="mb-4 p-3 rounded-lg text-sm font-medium <?= $_SESSION['flash']['type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
        <?= htmlspecialchars($_SESSION['flash']['message']) ?>
    </div>
<?php unset($_SESSION['flash']); endif; ?>
