<script src="{{ mix('/js/framework.js') }}"></script>



<!-- JAVASCRIPT -->
{{--<script src="{{ asset('/libs/jquery/jquery.min.js') }}"></script>--}}
<script src="{{ asset('/libs/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('/libs/jquery-validation/additional-methods.js') }}"></script>

{{--<script src="{{ asset('/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>--}}


<script src="{{ asset('/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('/libs/spectrum-colorpicker2/spectrum.min.js') }}"></script>
<script src="{{ asset('/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('/libs/node-waves/waves.min.js') }}"></script>


<script src="{{ asset('/libs/sod-eod-date-picker/jquery.datetimepicker.full.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.22/jquery-ui.min.js"></script>

<!-- form repeater js -->
<script src="{{ asset('/libs/jquery.repeater/jquery.repeater.min.js') }}"></script>

<!-- Toaster js -->
<script src="{{ asset('/libs/toastr/build/toastr.min.js') }}"></script>
<script src="{{ asset('/js/pages/toastr.init.js') }}"></script>

<script src="{{ asset('/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('/libs/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>
<script src="{{ asset('/libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<!-- Sweetalert2 -->
<script src="{{ asset('/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('/js/pages/sweet-alerts.init.js') }}"></script>

<!-- jquery step -->
<script src="{{ asset('/libs/jquery-steps/build/jquery.steps.min.js')}}"></script>
<!-- form advanced init -->
<script src="{{ asset('/js/pages/form-advanced.init.js') }}"></script>

<!-- calendars js -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>


<!-- owl.carousel js -->
<script src="{{ asset('/libs/owl.carousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('/js/pages/auth-2-carousel.init.js') }}"></script>

<!-- App js -->
<script src="{{ asset('/js/app.js') }}"></script>

<!-- Developer js -->
<script src="{{ asset('/js/common.js') }}"></script>

<!-- General js -->
<script src="{{ asset('/js/general.js') }}"></script>

<!-- Moment js -->
<script src="{{ asset('/libs/moment/min/moment.min.js') }}"></script>

<!-- ck editor js -->
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

{{-- <script src="{{ asset('/libs/ckeditor5/build/ckeditor.js') }}"></script> --}}
{{-- <script src="{{ asset('/libs/ckeditor5/build/translations/en-gb.js') }}"></script> --}}


<script type="text/javascript">
var tokenelem = "{{ csrf_token() }}";
var getState = "{{ route('get-state') }}";
var getCity = "{{ route('get-city') }}";

</script>
