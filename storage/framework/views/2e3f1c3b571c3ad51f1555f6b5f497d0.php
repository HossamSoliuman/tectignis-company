<?php $__env->startSection('title', 'Careers at Tectignis IT Solutions | Join Our IT Team in Navi Mumbai & PAN India'); ?>

<?php $__env->startSection('seo'); ?>
    <meta name="keywords" content="Careers, IT Jobs Navi Mumbai, Software Developer Jobs, Networking Jobs India, Digital Marketing Careers, IT Careers PAN India">
    <meta name="description" content="Explore exciting career opportunities at Tectignis IT Solutions Pvt Ltd. Join our innovative team based in Navi Mumbai, serving clients PAN India with top-tier IT solutions.">
    <link rel="canonical" href="<?php echo e(route('careers')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal01c1b0e46c89eaeff9b0aabe5d063e2a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal01c1b0e46c89eaeff9b0aabe5d063e2a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.breadcrumb','data' => ['title' => 'Careers','items' => ['Careers' => null]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Careers','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Careers' => null])]); ?>
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
    <!--======== careers-experts Start ==========-->
    <div class="contact-us-area service-contact-bg section-space--ptb_100">
        <div class="container pb-5">
            <div class="row align-items-center">
                <div class="row">
                    <div class="col-lg-7 m-auto">
                        <div class="section-title-wrap text-center section-space--mb_30">
                            <h3 class="heading text-light">Become a part of our big family to inspire and get inspired by
                                professional experts.</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 m-auto">
                    <div class="contact-form-service-wrap">
                        <div class="contact-title text-center section-space--mb_40">
                            <h3 class="mb-10">Need a hand?</h3>
                            <p class="text">Reach out to the world's most reliable IT services.</p>
                        </div>

                        <?php if(session('status')): ?>
                            <div class="alert alert-success text-center mb-20"><?php echo e(session('status')); ?></div>
                        <?php endif; ?>

                        <form class="contact-form" action="<?php echo e(route('careers.submit')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="contact-form__two">
                                <div class="contact-input">
                                    <div class="contact-inner">
                                        <label>Enter Your Full Name *</label>
                                        <input name="con_name" type="text" value="<?php echo e(old('con_name')); ?>"
                                            placeholder="Full name" required>
                                        <?php $__errorArgs = ['con_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span style="color:red;font-size:13px"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="contact-inner">
                                        <label>Enter Your Email *</label>
                                        <input name="con_email" type="email" value="<?php echo e(old('con_email')); ?>"
                                            placeholder="Email address" required>
                                        <?php $__errorArgs = ['con_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span style="color:red;font-size:13px"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="contact-inner">
                                    <label>Enter Your Phone No. *</label>
                                    <input name="con_phone" type="text" maxlength="10"
                                        value="<?php echo e(old('con_phone')); ?>" placeholder="Phone number" required>
                                    <?php $__errorArgs = ['con_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span style="color:red;font-size:13px"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="contact-inner">
                                    <label>Tell us about yourself</label>
                                    <textarea name="con_message" rows="4"
                                        placeholder="Briefly describe your experience and the role you're interested in..."><?php echo e(old('con_message')); ?></textarea>
                                </div>
                                <div class="comment-submit-btn">
                                    <button class="ht-btn ht-btn-md" type="submit">Submit Application</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--======== careers-experts End ==========-->

    <div class="contact-us-section-wrappaer infotechno-contact-us-bg section-space--ptb_120">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="conact-us-wrap-one">
                        <h3 class="heading">Obtaining further information by
                            <span class="text-color-primary">make a contact</span> with our experienced IT staffs.
                        </h3>
                        <div class="sub-heading">We're available for 8 hours a day!<br>Contact to require a detailed
                            analysis and assessment of your plan.</div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="contact-info-one text-center">
                        <div class="icon">
                            <span class="fas fa-phone"></span>
                        </div>
                        <h6 class="heading font-weight--reguler">Reach out now!</h6>
                        <h2 class="call-us"><a href="tel:+919987705688">+91 9987705688</a></h2>
                        <div class="contact-us-button mt-20">
                            <a href="<?php echo e(route('contact')); ?>" class="btn btn--secondary">Contact us</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\tech-corporate\resources\views/public/careers.blade.php ENDPATH**/ ?>