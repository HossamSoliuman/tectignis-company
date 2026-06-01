<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?> · Tectignis Admin</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/admin.css', 'resources/js/admin.js']); ?>
</head>

<body class="h-full text-slate-800">
    <div class="min-h-full">
        
        <aside data-sidebar
            class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full transform bg-slate-900 text-slate-200 transition lg:translate-x-0">
            <div class="flex h-16 items-center gap-2 px-6 text-lg font-semibold text-white">
                <span class="inline-block h-3 w-3 rounded-full bg-fuchsia-500"></span> Tectignis
            </div>
            <nav class="space-y-1 px-3 py-4 text-sm">
                <?php
                    $nav = [
                        ['admin.dashboard', 'Dashboard', 'grid'],
                        ['admin.settings.index', 'Settings', 'cog'],
                        ['admin.services.index', 'Services', 'briefcase'],
                        ['admin.blog.index', 'Blog Posts', 'document-text'],
                        ['admin.case-studies.index', 'Case Studies', 'photograph'],
                        ['admin.testimonials.index', 'Testimonials', 'chat-alt'],
                        ['admin.brands.index', 'Brands', 'tag'],
                        ['admin.leads.index', 'Leads (Inbox)', 'inbox'],
                        ['admin.redirects.index', 'Redirects', 'switch-horizontal'],
                    ];
                ?>
                <?php $__currentLoopData = $nav; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$route, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route($route)); ?>"
                        class="block rounded-lg px-3 py-2 <?php echo e(request()->routeIs($route) ? 'bg-slate-800 text-white' : 'hover:bg-slate-800'); ?>">
                        <?php echo e($label); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>
        </aside>

        
        <div class="lg:pl-64">
            <header
                class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 lg:px-8">
                <button data-sidebar-toggle class="rounded-lg p-2 hover:bg-slate-100 lg:hidden"
                    aria-label="Toggle menu">
                    <span class="block h-0.5 w-5 bg-slate-700"></span>
                    <span class="mt-1 block h-0.5 w-5 bg-slate-700"></span>
                    <span class="mt-1 block h-0.5 w-5 bg-slate-700"></span>
                </button>
                <h1 class="text-base font-semibold"><?php echo $__env->yieldContent('title', 'Dashboard'); ?></h1>
                <div class="flex items-center gap-3">
                    <a href="<?php echo e(url('/')); ?>" target="_blank"
                        class="text-sm text-slate-500 hover:text-slate-700">View site</a>
                    <span class="text-sm text-slate-500"><?php echo e(auth()->user()?->name); ?></span>
                    <form method="post" action="<?php echo e(route('admin.logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button
                            class="rounded-lg bg-slate-900 px-3 py-1.5 text-sm font-medium text-white hover:bg-slate-700">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <main class="p-4 lg:p-8">
                <?php if(session('status')): ?>
                    <div
                        class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        <?php echo e(session('status')); ?>

                    </div>
                <?php endif; ?>
                <?php if($errors->any()): ?>
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <ul class="list-disc list-inside space-y-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
</body>

</html>
<?php /**PATH D:\projects\tech-corporate\resources\views/layouts/admin.blade.php ENDPATH**/ ?>