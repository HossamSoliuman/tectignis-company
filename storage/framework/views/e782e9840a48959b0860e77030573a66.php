<?php $__env->startSection('title', 'Blog | Latest Insights & Trends in IT Solutions - Tectignis'); ?>

<?php $__env->startSection('seo'); ?>
    <meta name="description" content="Stay updated with the latest IT industry trends, tips and insights from Tectignis IT Solutions on software, AI, cloud and security.">
    <link rel="canonical" href="<?php echo e(route('blog.index')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal01c1b0e46c89eaeff9b0aabe5d063e2a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal01c1b0e46c89eaeff9b0aabe5d063e2a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.breadcrumb','data' => ['title' => 'Blog','items' => ['Blog' => null]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Blog','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Blog' => null])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal01c1b0e46c89eaeff9b0aabe5d063e2a)): ?>
<?php $attributes = $__attributesOriginal01c1b0e46c89eaeff9b0aabe5d063e2a; ?>
<?php unset($__attributesOriginal01c1b0e46c89eaeff9b0aabe5d063e2a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal01c1b0e46c89eaeff9b0aabe5d063e2a)): ?>
<?php $component = $__componentOriginal01c1b0e46c89eaeff9b0aabe5d063e2a; ?>
<?php unset($__componentOriginal01c1b0e46c89eaeff9b0aabe5d063e2a); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="blog-pages-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row">
                <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-md-6 mb-30 wow move-up">
                        <div class="single-blog-item blog-grid">
                            <div class="post-feature blog-thumbnail">
                                <a href="<?php echo e(route('blog.show', $post->slug)); ?>">
                                    <?php if($post->image): ?>
                                        <img class="img-fluid" src="<?php echo e(asset('assets/images/blog/'.$post->image)); ?>" alt="<?php echo e($post->title); ?>">
                                    <?php endif; ?>
                                </a>
                            </div>
                            <div class="post-info lg-blog-post-info">
                                <div class="post-meta">
                                    <div class="post-date">
                                        <span class="far fa-calendar meta-icon"></span> <?php echo e($post->published_at->format('M d, Y')); ?>

                                    </div>
                                </div>
                                <h5 class="post-title font-weight--bold">
                                    <a href="<?php echo e(route('blog.show', $post->slug)); ?>"><?php echo e($post->title); ?></a>
                                </h5>
                                <div class="post-excerpt mt-15">
                                    <p><?php echo e($post->excerpt); ?></p>
                                </div>
                                <div class="btn-text">
                                    <a href="<?php echo e(route('blog.show', $post->slug)); ?>">Read more <i class="ml-1 button-icon fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <?php if (isset($component)) { $__componentOriginaldf30a18d9440dd691ef9604b919389f4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf30a18d9440dd691ef9604b919389f4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.cta','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.cta'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldf30a18d9440dd691ef9604b919389f4)): ?>
<?php $attributes = $__attributesOriginaldf30a18d9440dd691ef9604b919389f4; ?>
<?php unset($__attributesOriginaldf30a18d9440dd691ef9604b919389f4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldf30a18d9440dd691ef9604b919389f4)): ?>
<?php $component = $__componentOriginaldf30a18d9440dd691ef9604b919389f4; ?>
<?php unset($__componentOriginaldf30a18d9440dd691ef9604b919389f4); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\tech-corporate\resources\views/public/blog/index.blade.php ENDPATH**/ ?>