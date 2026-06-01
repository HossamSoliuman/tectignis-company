<?php $__env->startSection('title', $post->seo_title ?: $post->title.' | Tectignis IT Solutions'); ?>

<?php $__env->startSection('seo'); ?>
    <meta name="description" content="<?php echo e($post->seo_description ?: $post->excerpt); ?>">
    <?php if($post->seo_keywords): ?>
        <meta name="keywords" content="<?php echo e($post->seo_keywords); ?>">
    <?php endif; ?>
    <link rel="canonical" href="<?php echo e(route('blog.show', $post->slug)); ?>">
    <meta property="og:title" content="<?php echo e($post->seo_title ?: $post->title); ?>">
    <meta property="og:description" content="<?php echo e($post->seo_description ?: $post->excerpt); ?>">
    <?php if($post->image): ?>
        <meta property="og:image" content="<?php echo e(asset('assets/images/blog/'.$post->image)); ?>">
    <?php endif; ?>
    <meta property="og:url" content="<?php echo e(route('blog.show', $post->slug)); ?>">
    <meta property="og:type" content="article">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <?php if (isset($component)) { $__componentOriginal01c1b0e46c89eaeff9b0aabe5d063e2a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal01c1b0e46c89eaeff9b0aabe5d063e2a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.public.breadcrumb','data' => ['title' => $post->title,'items' => ['Blog' => route('blog.index'), $post->title => null]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('public.breadcrumb'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post->title),'items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['Blog' => route('blog.index'), $post->title => null])]); ?>
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
    <!--====================  Blog Area Start ====================-->
    <div class="blog-pages-wrapper section-space--ptb_100">
        <div class="container">
            <div class="row">

                
                <div class="col-lg-4 order-lg-1 order-2">
                    <div class="page-sidebar-content-wrapper page-sidebar-left small-mt__40 tablet-mt__40">

                        <div class="sidebar-widget widget-blog-recent-post wow move-up">
                            <h4 class="sidebar-widget-title">Recent Posts</h4>
                            <ul>
                                <?php $__currentLoopData = $recentPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <a href="<?php echo e(route('blog.show', $recent->slug)); ?>"
                                            class="<?php echo e($recent->slug === $post->slug ? 'active' : ''); ?>">
                                            <?php echo e($recent->title); ?>

                                        </a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>

                        <div class="sidebar-widget widget-images wow move-up">
                            <img class="img-fluid" src="<?php echo e(asset('assets/images/blog/Startup-Services-India.webp')); ?>"
                                alt="Startup Marketing">
                            <p class="pt-4"><strong>Affordable Growth Solutions</strong></p>
                            <p><strong>Let <a href="<?php echo e(route('home')); ?>" style="color: #C10897;">Tectignis IT Solutions</a> build your brand online</strong></p>
                            <p><strong>Schedule Your Free Consultation!</strong></p>
                            <a href="<?php echo e(route('contact')); ?>" class="ht-btn ht-btn-xs mt-15">Contact Us</a>
                        </div>

                    </div>
                </div>

                
                <div class="col-lg-8 order-lg-2 order-1">
                    <div class="main-blog-wrap">
                        <div class="single-blog-item">
                            <div class="post-feature blog-thumbnail wow move-up">
                                <h3 class="post-title mb-5"><?php echo e($post->title); ?></h3>
                            </div>

                            <div class="post-info lg-blog-post-info wow move-up">
                                <div class="post-meta mt-20">
                                    <div class="post-date">
                                        <span class="far fa-calendar meta-icon"></span>
                                        <?php echo e($post->published_at->format('M d, Y')); ?>

                                    </div>
                                </div>

                                <div class="post-excerpt mt-15">
                                    <?php echo $post->content; ?>

                                </div>

                                <div class="entry-post-share-wrap border-bottom">
                                    <div class="row align-items-center">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="entry-post-share">
                                                <div class="share-label">Share this post</div>
                                                <div class="share-media">
                                                    <span class="share-icon fas fa-share-alt"></span>
                                                    <div class="share-list">
                                                        <a class="hint--bounce hint--top hint--primary twitter"
                                                            target="_blank" aria-label="Twitter"
                                                            href="https://twitter.com/intent/tweet?url=<?php echo e(urlencode(route('blog.show', $post->slug))); ?>&text=<?php echo e(urlencode($post->title)); ?>">
                                                            <i class="fab fa-twitter"></i>
                                                        </a>
                                                        <a class="hint--bounce hint--top hint--primary facebook"
                                                            target="_blank" aria-label="Facebook"
                                                            href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(route('blog.show', $post->slug))); ?>">
                                                            <i class="fab fa-facebook-f"></i>
                                                        </a>
                                                        <a class="hint--bounce hint--top hint--primary linkedin"
                                                            target="_blank" aria-label="LinkedIn"
                                                            href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo e(urlencode(route('blog.show', $post->slug))); ?>">
                                                            <i class="fab fa-linkedin"></i>
                                                        </a>
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
        </div>
    </div>
    <!--====================  Blog Area End  ====================-->

    <!--========== Call to Action Area Start ============-->
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
    <!--========== Call to Action Area End ============-->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\projects\tech-corporate\resources\views/public/blog/show.blade.php ENDPATH**/ ?>