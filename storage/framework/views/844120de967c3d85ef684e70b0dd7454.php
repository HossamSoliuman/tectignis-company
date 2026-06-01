<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="rounded-2xl bg-white p-5 shadow-sm">
                <div class="text-sm text-slate-500"><?php echo e($label); ?></div>
                <div class="mt-2 text-3xl font-semibold text-slate-900"><?php echo e($value); ?></div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="mt-8 rounded-2xl bg-white p-6 shadow-sm">
        <h2 class="text-base font-semibold text-slate-900">Welcome to the Tectignis admin</h2>
        <p class="mt-2 text-sm text-slate-600">
            The foundation is ready. Content modules (services, solutions, industries, blog, insights, downloads,
            FAQs, case studies, testimonials, leads, menus, settings and per-page SEO) will appear in the sidebar as
            each is built in the following phases.
        </p>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\tech-corporate\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>