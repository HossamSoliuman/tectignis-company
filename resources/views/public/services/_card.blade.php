<div class="col-lg-4 col-md-6 wow move-up">
    <!-- ht-box-icon Start -->
    <div class="ht-box-images style-01">
        <div class="image-box-wrap">
            <div class="box-image">
                <img class="img-fulid" src="{{ asset('uploads/'.$service->icon) }}" alt="{{ $service->title }}" loading="lazy">
            </div>
            <div class="content">
                <h5 class="heading">{{ $service->title }}</h5>
                <div class="text">{{ $service->short_description }}</div>
                <div class="circle-arrow">
                    <div class="middle-dot"></div>
                    <div class="middle-dot dot-2"></div>
                    <a href="{{ route('services.show', $service->slug) }}">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- ht-box-icon End -->
</div>
