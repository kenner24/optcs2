<script type="text/javascript" src="{{ asset('/admin/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/admin/js/toastr.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/admin/js/jquery.validate.js') }}"></script>
<script type="text/javascript" src="{{ asset('/admin/js/additional-methods.js') }}"></script>


<script type="text/javascript" src="{{ asset('/admin/js/bootstrap.bundle.js') }}"></script>
<script type="text/javascript" src="{{ asset('/admin/js/adminlte.js') }}"></script>

<script type="text/javascript" src="{{ asset('/admin/js/dist/demo.js') }}"></script>

<script type="text/javascript" src="{{ asset('/admin/js/dashboard3.js') }}"></script>

<script type="text/javascript" src="{{ asset('/admin/js/toastr.min.js') }}"></script>

<!-- tables -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
<!-- tables -->
<!-- for session flash -->
<script>
    @if (Session::has('success'))
        $(function() {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            toastr.success("{{ Session::get('success') }}");
        });
    @endif
</script>

<script>
    @if (Session::has('error'))
        $(function() {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            toastr.error("{{ Session::get('error') }}");
        });
    @endif
</script>


<script type="text/javascript">
    $('.loader').hide();
</script>

<script>
    $.validator.addMethod('filesize', function(value, element, param) {
        return this.optional(element) || (element.files[0].size <= param)
    }, 'File size must be less than 1mb.');

    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z, ]+$/i.test(value);
    }, "Please enter the letters only.");
</script>
