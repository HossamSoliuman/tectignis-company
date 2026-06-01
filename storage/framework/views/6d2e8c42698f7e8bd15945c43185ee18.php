<?php $__env->startSection('title', 'Services'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold">Services (<?php echo e($services->count()); ?>)</h2>
        <a href="<?php echo e(route('admin.services.create')); ?>"
            class="rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white hover:bg-fuchsia-700">
            + New Service
        </a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Slug</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium"><?php echo e($service->title); ?></td>
                        <td class="px-4 py-3 text-slate-500"><?php echo e($service->slug); ?></td>
                        <td class="px-4 py-3 text-slate-500"><?php echo e($service->category); ?></td>
                        <td class="px-4 py-3"><?php echo e($service->sort_order); ?></td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs <?php echo e($service->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500'); ?>">
                                <?php echo e($service->is_active ? 'Active' : 'Inactive'); ?>

                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="<?php echo e(route('admin.services.edit', $service)); ?>"
                                    class="text-fuchsia-600 hover:underline">Edit</a>
                                <form action="<?php echo e(route('admin.services.destroy', $service)); ?>" method="POST"
                                    onsubmit="return confirm('Delete this service?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\tech-corporate\resources\views/admin/services/index.blade.php ENDPATH**/ ?>