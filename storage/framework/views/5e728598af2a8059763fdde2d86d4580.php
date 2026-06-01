<?php $__env->startSection('title', 'Contact Us | Tectignis IT Solutions - Navi Mumbai & PAN India'); ?>

<?php $__env->startSection('seo'); ?>
    <meta name="description" content="Have a question or need assistance? Contact Tectignis IT Solutions Pvt Ltd, a leading IT solutions provider in Navi Mumbai offering services PAN India.">
    <link rel="canonical" href="<?php echo e(route('contact')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal01c1b0e46c89eaeff9b0aabe5d063e2a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal01c1b0e46c89eaeff9b0aabe5d063e2a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.breadcrumb','data' => ['title' => 'Get In Touch','items' => ['Contact us' => null]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Get In Touch','items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Contact us' => null])]); ?>
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
    <div class="feature-icon-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="feature-list__one">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 wow move-up">
                                <div class="ht-box-icon style-01 single-svg-icon-box">
                                    <div class="icon-box-wrap">
                                        <div class="content">
                                            <h5 class="heading">Phone</h5>
                                            <div class="text">Call us today to explore software, AI, cloud and IT solutions.</div>
                                            <div class="feature-btn">
                                                <a href="tel:+919987705688"><span class="button-text">+91 9987705688</span>
                                                    <i class="fas fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 wow move-up">
                                <div class="ht-box-icon style-01 single-svg-icon-box">
                                    <div class="icon-box-wrap">
                                        <div class="content">
                                            <h5 class="heading">Registered Address</h5>
                                            <div class="text">Aashiyana CHS Shop no 05, Sector 11, Plot no 29, Kamothe, Navi Mumbai - 410206</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 wow move-up">
                                <div class="ht-box-icon style-01 single-svg-icon-box">
                                    <div class="icon-box-wrap">
                                        <div class="content">
                                            <h5 class="heading">Email</h5>
                                            <div class="text">Email us your query today and our team will provide tailored solutions.</div>
                                            <div class="feature-btn">
                                                <a href="mailto:info@tectignis.in"><span class="button-text">info@tectignis.in</span>
                                                    <i class="fas fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="contact-us-area appointment-contact-bg section-space--pb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="contact-title section-space--mb_50">
                        <h3 class="mb-20">Get in touch with us.</h3>
                        <p class="sub-title">We'd love to hear from you</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="contact-info-three text-left">
                        <h6 class="heading font-weight--reguler">Reach out now!</h6>
                        <h3 class="call-us"><a href="tel:9987705688">(+91) 9987705688</a></h3>
                        <div class="text">Have a project in mind or need expert IT solutions? Reach out and our team will
                            connect with you to bring your ideas to life.</div>
                        <div class="location-button mt-20">
                            <a href="mailto:info@tectignis.in" class="location-text-button">
                                <span class="button-icon"></span>
                                <span class="button-text">info@tectignis.in</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="contact-form-wrap">
                        <?php if(session('status')): ?>
                            <div class="alert alert-success"><?php echo e(session('status')); ?></div>
                        <?php endif; ?>
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <form class="contact-form" id="contact-form" action="<?php echo e(route('contact.submit')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="contact-form__two">
                                <div class="contact-input">
                                    <div class="contact-inner">
                                        <input name="con_name" type="text" placeholder="Full Name *"
                                            value="<?php echo e(old('con_name')); ?>" required>
                                    </div>
                                    <div class="contact-inner">
                                        <input name="con_email" type="email" placeholder="Email *"
                                            value="<?php echo e(old('con_email')); ?>" required>
                                    </div>
                                </div>
                                <div class="contact-input">
                                    <div class="contact-inner">
                                        <input name="con_phone" type="text" placeholder="Phone Number *"
                                            value="<?php echo e(old('con_phone')); ?>" maxlength="15" required>
                                    </div>
                                    <div class="contact-inner">
                                        <input name="con_subject" type="text" placeholder="Subject *"
                                            value="<?php echo e(old('con_subject')); ?>" required>
                                    </div>
                                </div>
                                <div class="contact-inner contact-message">
                                    <textarea name="con_message" placeholder="Message" required><?php echo e(old('con_message')); ?></textarea>
                                </div>
                                <div class="comment-submit-btn">
                                    <button class="ht-btn ht-btn-md" type="submit">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
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

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\tech-corporate\resources\views/public/contact.blade.php ENDPATH**/ ?>