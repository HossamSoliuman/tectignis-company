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

    <?php $siteSettings = \App\Models\Setting::values(); ?>

    
    <?php if($siteSettings['google_search_console_verification'] ?? false): ?>
        <meta name="google-site-verification" content="<?php echo e($siteSettings['google_search_console_verification']); ?>">
    <?php endif; ?>

    
    <?php if($siteSettings['site_gtm_id'] ?? false): ?>
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','<?php echo e($siteSettings['site_gtm_id']); ?>');</script>
    <?php endif; ?>

    
    <?php $ga4Id = $siteSettings['site_ga_id'] ?? null; ?>
    <?php if($ga4Id): ?>
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo e($ga4Id); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?php echo e($ga4Id); ?>');
        </script>
    <?php endif; ?>

    
    <?php if($siteSettings['meta_pixel_id'] ?? false): ?>
        <script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        document,'script','https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '<?php echo e($siteSettings['meta_pixel_id']); ?>');
        fbq('track', 'PageView');</script>
        <noscript><img height="1" width="1" style="display:none" alt=""
            src="https://www.facebook.com/tr?id=<?php echo e($siteSettings['meta_pixel_id']); ?>&ev=PageView&noscript=1"></noscript>
    <?php endif; ?>

    
    <link rel="icon" href="<?php echo e(\App\Models\Setting::imageUrl($siteSettings['site_favicon'] ?? null, 'site_favicon') ?? asset('assets/images/favicon.webp')); ?>">

    
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&amp;family=Poppins:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">

    
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/vendor/vendor.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/plugins.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/custom.css')); ?>?v=<?php echo e(filemtime(public_path('assets/css/custom.css'))); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>

    
    <?php if($siteSettings['site_gtm_id'] ?? false): ?>
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo e($siteSettings['site_gtm_id']); ?>"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <?php endif; ?>

    
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
        <div class=" site-wrapper-reveal">
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

    
    <?php if (isset($component)) { $__componentOriginal4841e57275fcdfec38efa8608ed3efa1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4841e57275fcdfec38efa8608ed3efa1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.consultation-modal','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.consultation-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4841e57275fcdfec38efa8608ed3efa1)): ?>
<?php $attributes = $__attributesOriginal4841e57275fcdfec38efa8608ed3efa1; ?>
<?php unset($__attributesOriginal4841e57275fcdfec38efa8608ed3efa1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4841e57275fcdfec38efa8608ed3efa1)): ?>
<?php $component = $__componentOriginal4841e57275fcdfec38efa8608ed3efa1; ?>
<?php unset($__componentOriginal4841e57275fcdfec38efa8608ed3efa1); ?>
<?php endif; ?>

    
    <?php echo $__env->yieldPushContent('modals'); ?>

    
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

    
    <?php if($siteSettings['recaptcha_site_key'] ?? false): ?>
        <script src="https://www.google.com/recaptcha/api.js?render=<?php echo e($siteSettings['recaptcha_site_key']); ?>"></script>
        <script>
            document.querySelectorAll('form[action*="contact"], form[action*="careers"], form[action*="downloads"]').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.dataset.recaptchaDone) { return; }
                    event.preventDefault();
                    grecaptcha.ready(function () {
                        grecaptcha.execute('<?php echo e($siteSettings['recaptcha_site_key']); ?>', { action: 'lead' }).then(function (token) {
                            var input = form.querySelector('input[name="g-recaptcha-response"]');
                            if (!input) {
                                input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'g-recaptcha-response';
                                form.appendChild(input);
                            }
                            input.value = token;
                            form.dataset.recaptchaDone = '1';
                            form.submit();
                        });
                    });
                });
            });
        </script>
    <?php endif; ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>

</html>
<?php /**PATH D:\projects\tech-corporate\resources\views/layouts/public.blade.php ENDPATH**/ ?>