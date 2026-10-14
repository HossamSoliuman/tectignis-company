{{-- JS --}}
<script src="{{ asset('assets/js/vendor/modernizr-2.8.3.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/jquery-migrate-3.3.0.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/plugins.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}?v={{ filemtime(public_path('assets/js/main.js')) }}"></script>

@stack('scripts')
