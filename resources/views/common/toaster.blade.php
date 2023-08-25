@push('scripts')
    <script type="text/javascript">
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "3000",
            "extendedTimeOut": "3000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
        let action_title = '{{ session("action_title") }}';

        @if(Session::has('message') && !Session::has('success'))
            toastr.success("{{ session('message') }}", action_title);
        @endif

        @if(Session::has('info'))
            toastr.info("{{ session('info') }}", action_title);
        @endif

        @if(Session::has('success'))
            toastr.success("{{ session('success') }}", action_title);
        @endif

        @if(Session::has('error'))
            toastr.error("{{ session('error') }}", action_title);
        @endif

        @if(Session::has('warning'))
            toastr.warning("{{ session('warning') }}", action_title);
        @endif
    </script>
@endpush
