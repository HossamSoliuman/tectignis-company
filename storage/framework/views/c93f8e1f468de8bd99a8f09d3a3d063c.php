<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login · Tectignis</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/admin.css']); ?>
</head>

<body class="flex h-full items-center justify-center p-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-8 shadow-sm">
        <div class="mb-6 text-center">
            <div class="mx-auto mb-3 inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-900 text-white">T</div>
            <h1 class="text-lg font-semibold text-slate-900">Tectignis Admin</h1>
            <p class="text-sm text-slate-500">Sign in to manage your website</p>
        </div>

        <?php if($errors->any()): ?>
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo e(route('admin.login.store')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Password</label>
                <input type="password" name="password" required
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" class="rounded border-slate-300"> Remember me
            </label>
            <button type="submit"
                class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-slate-700">
                Sign in
            </button>
        </form>
    </div>
</body>

</html>
<?php /**PATH D:\projects\tech-corporate\resources\views/admin/auth/login.blade.php ENDPATH**/ ?>