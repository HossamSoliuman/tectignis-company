<!DOCTYPE html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="revisit-after" content="7 days">
    <meta property="og:locale" content="en_US" />
    <meta property="twitter:account_id" content="29759031" />
    <meta name="email" content="info@tectignis.in">
    <meta name="robots" content="index, follow">
    <meta name="copyright" content="Copyright Tectignis IT solutions - all rights reserved" />
    <meta name="document-type" content="Public">
    <meta name="document-distribution" content="Global">
    <meta property="fb:app_id" content="560418770098574" />
    <meta name="geo.region" content="IN-MH">
    <meta name="geo.placename" content="Navi Mumbai">
    <meta name="geo.position" content="19.02993219206727;73.06512509395156">
    <meta name="ICBM" content="19.02993219206727,73.06512509395156" />

    
    <title><?php echo $__env->yieldContent('title', 'Tectignis IT Solutions | Software, AI, Cloud & Security'); ?></title>
    <?php echo $__env->yieldContent('seo'); ?>
    <?php echo $__env->yieldPushContent('head'); ?>

    
    <?php $ga4Id = \App\Models\Setting::get('ga4_id'); ?>
    <?php if($ga4Id): ?>
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e($ga4Id); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo e($ga4Id); ?>');
        </script>
    <?php endif; ?>

    
    <link rel="icon" href="<?php echo e(asset('assets/images/favicon.webp')); ?>">

    
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet">

    
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/vendor/vendor.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/plugins.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/custom.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>

    
    <?php echo $__env->yieldPushContent('json-ld'); ?>
    <?php if (isset($component)) { $__componentOriginal1ff444a6761d54b4237649bd3eed67fc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1ff444a6761d54b4237649bd3eed67fc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.seo.json-ld','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('seo.json-ld'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1ff444a6761d54b4237649bd3eed67fc)): ?>
<?php $attributes = $__attributesOriginal1ff444a6761d54b4237649bd3eed67fc; ?>
<?php unset($__attributesOriginal1ff444a6761d54b4237649bd3eed67fc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1ff444a6761d54b4237649bd3eed67fc)): ?>
<?php $component = $__componentOriginal1ff444a6761d54b4237649bd3eed67fc; ?>
<?php unset($__componentOriginal1ff444a6761d54b4237649bd3eed67fc); ?>
<?php endif; ?>

    
    <?php if (isset($component)) { $__componentOriginalb146cbf8306c95b172d2591af732a390 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb146cbf8306c95b172d2591af732a390 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb146cbf8306c95b172d2591af732a390)): ?>
<?php $attributes = $__attributesOriginalb146cbf8306c95b172d2591af732a390; ?>
<?php unset($__attributesOriginalb146cbf8306c95b172d2591af732a390); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb146cbf8306c95b172d2591af732a390)): ?>
<?php $component = $__componentOriginalb146cbf8306c95b172d2591af732a390; ?>
<?php unset($__componentOriginalb146cbf8306c95b172d2591af732a390); ?>
<?php endif; ?>

    <?php if (! empty(trim($__env->yieldContent('breadcrumb')))): ?>
        <?php echo $__env->yieldContent('breadcrumb'); ?>
    <?php endif; ?>

    <div id="main-wrapper">
        <div class="site-wrapper-reveal">
            <?php echo $__env->yieldContent('content'); ?>
        </div>

        
        <?php if (isset($component)) { $__componentOriginalbb84be681bbe94cc31d6257779433433 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbb84be681bbe94cc31d6257779433433 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbb84be681bbe94cc31d6257779433433)): ?>
<?php $attributes = $__attributesOriginalbb84be681bbe94cc31d6257779433433; ?>
<?php unset($__attributesOriginalbb84be681bbe94cc31d6257779433433); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbb84be681bbe94cc31d6257779433433)): ?>
<?php $component = $__componentOriginalbb84be681bbe94cc31d6257779433433; ?>
<?php unset($__componentOriginalbb84be681bbe94cc31d6257779433433); ?>
<?php endif; ?>
    </div>

    
    <a href="#" class="scroll-top" id="scroll-top">
        <i class="arrow-top fas fa-chevron-up"></i>
        <i class="arrow-bottom fas fa-chevron-up"></i>
    </a>

    
    <script src="<?php echo e(asset('assets/js/vendor/modernizr-2.8.3.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/vendor/jquery-3.5.1.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/vendor/jquery-migrate-3.3.0.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/vendor/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/plugins.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/main.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/right-click.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>

</html>
<?php /**PATH D:\projects\tech-corporate\resources\views/layouts/public.blade.php ENDPATH**/ ?>