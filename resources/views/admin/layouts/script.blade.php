
<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<!-- DATERANGE -->
<script src="{{ asset('backend/lib/daterange/moment.min.js') }}"></script>
<script src="{{ asset('backend/lib/daterange/daterange.min.js') }}"></script>
<script defer src="{{ asset('backend/lib/daterange/daterange-init.js') }}"></script>
<!-- SWIPER -->
<script src="{{ asset('backend/lib/swiper/bundle.min.js') }}"></script>
<script src="{{ asset('backend/lib/swiper/initialize.js') }}"></script>
<!-- SCRIPTS -->
<script src="{{ asset('backend/js/dropdown.js') }}"></script>
<script src="{{ asset('backend/js/drawer.js') }}"></script>
<script src="{{ asset('backend/js/modal.js') }}"></script>
<script src="{{ asset('backend/js/tabs.js') }}"></script>
<script src="{{ asset('backend/js/script.js') }}"></script>
<!-- Template JS File -->
<script src="{{ asset('backend/lib/izitoast/dist/js/iziToast.min.js') }}"></script>
<script src="{{ asset('backend/js/scripts.js') }}"></script>
<script src="{{ asset('backend/js/confirm-delete.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
{{-- Mix Js --}}
<script src="{{ mix('js/app.js') }}" defer></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    @if(session('success'))
    iziToast.success({
        title: 'Success',
        message: '{{ session('success') }}',
        position: 'topRight'
    });
    @endif

    @if(session('error'))
    iziToast.error({
        title: 'Error',
        message: '{{ session('error') }}',
        position: 'topRight'
    });
    @endif
</script>

@stack('js')




