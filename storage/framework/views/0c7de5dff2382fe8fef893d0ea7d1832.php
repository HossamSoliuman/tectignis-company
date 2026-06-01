<?php $__env->startSection('title', 'Our Expertise | Leading IT Solutions Provider in Navi Mumbai & PAN India'); ?>

<?php $__env->startSection('seo'); ?>
    <meta name="keywords" content="Expertise in IT Services, Custom Software Development, Network Security, Digital Marketing Services, Web Development Navi Mumbai, IT Solutions PAN India, IT Expertise in Navi Mumbai, Software Development Mumbai, Digital Marketing India, IT Solutions PAN India, Networking Services India, Web App Development India">
    <meta name="description" content="Explore the expertise of Tectignis IT Solutions Pvt Ltd. We provide world-class IT solutions with specialized skills in software development, networking, and security, serving Navi Mumbai & PAN India.">
    <link rel="canonical" href="<?php echo e(route('capabilities.index')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal01c1b0e46c89eaeff9b0aabe5d063e2a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal01c1b0e46c89eaeff9b0aabe5d063e2a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.breadcrumb','data' => ['title' => 'IT Services','items' => ['IT Services' => null]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'IT Services','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['IT Services' => null])]); ?>
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
    <!--===========  feature-images-wrapper  Start =============-->
    <div class="feature-images-wrapper section-space--ptb_100">
        <div class="container">

            <div class="row">
                <div class="col-lg-12">
                    <!-- section-title-wrap Start -->
                    <div class="section-title-wrap text-center">
                        <h3 class="heading">Preparing for your success, <br> we provide <span class="text-color-primary"> truly prominent IT solutions.</span></h3>
                    </div>
                    <!-- section-title-wrap Start -->
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="feature-images__one">
                        <div class="row">
                            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-lg-4 col-md-6 wow move-up">
                                <!-- ht-box-icon Start -->
                                <div class="ht-box-images style-01">
                                    <div class="image-box-wrap">
                                        <div class="box-image">
                                            <img class="img-fulid" src="<?php echo e(asset('assets/images/icons/'.$service->icon)); ?>" alt="<?php echo e($service->title); ?>" loading="lazy">
                                        </div>
                                        <div class="content">
                                            <h5 class="heading"><?php echo e($service->title); ?></h5>
                                            <div class="text"><?php echo e($service->short_description); ?></div>
                                            <div class="circle-arrow">
                                                <div class="middle-dot"></div>
                                                <div class="middle-dot dot-2"></div>
                                                <a href="<?php echo e(route('capabilities.show', $service->slug)); ?>">
                                                    <i class="fas fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ht-box-icon End -->
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--===========  feature-images-wrapper  End =============-->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\tech-corporate\resources\views/public/capabilities/index.blade.php ENDPATH**/ ?>