@extends('cmf.layouts.cmf', [
    'breadcrumb' => 'dev.push'
])

@section('content.title')
    @include('cmf.components.pages.title', [
        'title' => 'Push',
    ])
@endsection

@push('scripts')

@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-12 mb-1 mt-1">
                        <center>
                            <button id="btn-nft-enable" onclick="initFirebaseMessagingRegistration()" class="btn btn-danger btn-xs btn-flat">Allow for Notification</button>
                        </center>
                        <form action="{{ routeCmf('dev.push.send.post') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" name="title">
                            </div>
                            <div class="form-group">
                                <label>Body</label>
                                <textarea class="form-control" name="body"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Notification</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://www.gstatic.com/firebasejs/8.0.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.0.1/firebase-messaging.js"></script>
    <script>

        var firebaseConfig = {
            apiKey: "AIzaSyBhJSq8zsnlPIbLTnTH5T00VJrELd7nWBE",
            authDomain: "staymenity-push.firebaseapp.com",
            databaseURL: "https://staymenity-push.firebaseio.com",
            projectId: "staymenity-push",
            storageBucket: "staymenity-push.appspot.com",
            messagingSenderId: "520491198378",
            appId: "1:520491198378:web:34a5ffb610121e33912dd3"
        };

        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        function initFirebaseMessagingRegistration() {
            messaging
                .requestPermission()
                .then(function () {
                    return messaging.getToken()
                })
                .then(function(token) {
                    console.log(token);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: '{{ routeCmf('dev.push.token.post') }}',
                        type: 'POST',
                        data: {
                            token: token
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            alert('Token saved successfully.');
                        },
                        error: function (err) {
                            console.log('User Chat Token Error'+ err);
                        },
                    });

                }).catch(function (err) {
                console.log('User Chat Token Error'+ err);
            });
        }

        messaging.onMessage(function(payload) {
            const noteTitle = payload.notification.title;
            const noteOptions = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(noteTitle, noteOptions);
        });

    </script>
@endsection
