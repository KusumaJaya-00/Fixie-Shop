<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold">Kelola User</h1>
    <a href="/admin/users/create" class="rounded-lg bg-brand px-4 py-2 text-sm text-white font-medium hover:bg-brand-dark">
        + Tambah User
    </a>
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
                    <td class="px-4 py-3"><?= $u['id'] ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($u['name']) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars($u['email']) ?></td>
                    <td class="px-4 py-3">
                        <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-medium <?= $u['role_name'] === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' ?>">
                            <?= htmlspecialchars($u['role_name']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 flex gap-2">
                        <a href="/admin/users/edit?id=<?= $u['id'] ?>" class="text-brand hover:underline text-xs">Edit</a>
                        <a href="/admin/users/delete?id=<?= $u['id'] ?>" class="text-red-600 hover:underline text-xs"
                           onclick="return confirm('Hapus user <?= htmlspecialchars(addslashes($u['name'])) ?>?')">Hapus</a>
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
