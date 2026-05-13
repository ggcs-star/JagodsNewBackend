    <!--========= JS LINK PART START =====-->
    <script src="{{ asset('frontend/lib/jquery-3.5.0.min.js') }}"></script>
    <script src="{{ asset('frontend/lib/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/lib/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/lib/swiper/swiper-initialize.js') }}"></script>
    <script src="{{ asset('frontend/lib/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
    <!-- custom js Start -->
    <script type="application/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script type="text/javascript">
        @if (session('success'))
            iziToast.success({
                title: 'Success',
                message: '{{ session('success') }}',
                position: 'topRight'
            });
        @endif

        @if (session('error'))
            iziToast.error({
                title: 'Error',
                message: '{{ session('error') }}',
                position: 'topRight'
            });
        @endif

        @if (session('warning'))
            iziToast.error({
                title: 'Warning',
                message: '{{ session('warning') }}',
                position: 'topRight'
            });
        @endif
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            const beep = document.getElementById("myAudio1");
            function sound() {
                beep.play();
            }
            const firebaseConfig = {
                apiKey: "{{ setting('firebase_api_key') }}",
                authDomain: "{{ setting('firebase_authDomain') }}",
                projectId: "{{ setting('projectId') }}",
                storageBucket: "{{ setting('storageBucket') }}",
                messagingSenderId: "{{ setting('messagingSenderId') }}",
                appId: "{{ setting('appId') }}",
                measurementId: "{{ setting('measurementId') }}",
            };
            firebase.initializeApp(firebaseConfig);
            const messaging = firebase.messaging();
            startFCM();
            function startFCM() {
                messaging.requestPermission()
                    .then(function() {
                        return messaging.getToken()
                    })
                    .then(function(response) {
                        $.ajax({
                            url: '{{ route('store.token') }}',
                            type: 'POST',
                            data: {
                                token: response
                            },
                            dataType: 'JSON',
                            success: function(response) {
                            },
                            error: function(error) {
                            },
                        });

                    }).catch(function(error) {});
            }

            messaging.onMessage(function(payload) {

                const title = payload.notification.title;
                const body = payload.notification.body;
                sound(); 
                new Notification(title, {
                    body: body,
                });
            });
        });
    </script>

    @stack('js')
    @livewireScripts
    @stack('livewire')
    <script src="{{ asset('frontend/js/script.js') }}"></script>
