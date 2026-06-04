{{--
    Renders an admin-authored raw HTML body inside the public site layout.
    The HTML is authored only by trusted admin users via the admin panel,
    so unescaped output ({!! !!}) is acceptable here.
--}}
<div class="dynamic-page-body section-space--ptb_100">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                {!! $body !!}
            </div>
        </div>
    </div>
</div>
