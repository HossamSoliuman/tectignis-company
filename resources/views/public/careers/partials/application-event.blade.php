{{-- A stored job application, tracked as its own event and never as a lead
     conversion (generate_lead), so careers traffic stays out of the sales
     goals. Sent once per submission and without personal data. --}}
@if (is_array(session('application_submitted')))
    <script>
        (function () {
            var application = @js(session('application_submitted'));
            try {
                var key = 'tx_application_' + application.event_id;
                if (sessionStorage.getItem(key)) { return; }
                sessionStorage.setItem(key, '1');
            } catch (e) {}
            window.txTrack('job_application_submitted', application);
        })();
    </script>
@endif
