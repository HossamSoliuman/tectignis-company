<?php $__env->startSection('title', 'Case Studies | Successful IT Solutions - Tectignis'); ?>

<?php $__env->startSection('seo'); ?>
    <meta name="description" content="Discover our successful case studies. Tectignis delivers measurable results with software, AI, cloud and security solutions for businesses across India.">
    <link rel="canonical" href="<?php echo e(route('case-studies.index')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal01c1b0e46c89eaeff9b0aabe5d063e2a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal01c1b0e46c89eaeff9b0aabe5d063e2a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.breadcrumb','data' => ['title' => 'Case Studies','items' => ['Case Studies' => null]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Case Studies','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Case Studies' => null])]); ?>
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
    <div class="projects-wrapper section-space--ptb_100 projects-masonary-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-wrap text-center section-space--mb_40">
                        <h3 class="heading">Proud projects that <span class="text-color-primary">make us stand out</span></h3>
                    </div>
                </div>
            </div>

            <div class="projects-case-wrap">
                <div class="row mesonry-list">
                    <?php $__currentLoopData = $caseStudies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $caseStudy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4 col-md-6 cat--2">
                            <a href="<?php echo e(route('case-studies.index')); ?>" class="projects-wrap style-01">
                                <div class="projects-image-box">
                                    <div class="projects-image">
                                        <?php if($caseStudy->image): ?>
                                            <img class="img-fluid" src="<?php echo e(asset('assets/images/features/'.$caseStudy->image)); ?>"
                                                alt="<?php echo e($caseStudy->title); ?>" loading="lazy">
                                        <?php endif; ?>
                                    </div>
                                    <div class="content">
                                        <h6 class="heading"><?php echo e($caseStudy->title); ?></h6>
                                        <div class="post-categories"><?php echo e($caseStudy->category); ?></div>
                                        <div class="text"><?php echo e($caseStudy->short_description); ?></div>
                                        <div class="box-projects-arrow">
                                            <span class="button-text">View case study</span>
                                            <i class="fas fa-arrow-right ml-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
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

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\tech-corporate\resources\views/public/case-studies/index.blade.php ENDPATH**/ ?>