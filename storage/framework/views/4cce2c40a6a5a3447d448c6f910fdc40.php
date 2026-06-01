<?php $__env->startSection('title', 'Settings'); ?>

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('admin.settings.update')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="mb-8">
                <h2 class="mb-4 text-sm font-semibold uppercase tracking-wide text-slate-500"><?php echo e(ucfirst($group)); ?></h2>
                <div class="rounded-xl border border-slate-200 bg-white divide-y divide-slate-100">
                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-start gap-4 p-4">
                            <label class="w-48 shrink-0 pt-2 text-sm font-medium text-slate-700">
                                <?php echo e(ucwords(str_replace('_', ' ', $setting->key))); ?>

                            </label>
                            <div class="flex-1">
                                <?php if(str_contains($setting->key, 'content') || str_contains($setting->key, 'legal_') || str_contains($setting->key, 'description')): ?>
                                    <textarea name="settings[<?php echo e($setting->key); ?>]" rows="6"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400"><?php echo e($setting->value); ?></textarea>
                                <?php else: ?>
                                    <input type="text" name="settings[<?php echo e($setting->key); ?>]"
                                        value="<?php echo e($setting->value); ?>"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <div class="flex justify-end">
            <button type="submit"
                class="rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white hover:bg-fuchsia-700">
                Save Settings
            </button>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\tech-corporate\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>