@extends('cmf.layouts.cmf', [
    'breadcrumb' => 'dev.notifications'
])

@section('content.title')
    @include('cmf.components.pages.title', [
        'title' => 'Notifications',
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <script src="https://www.gstatic.com/firebasejs/8.0.1/firebase-app.js"></script>
                <script src="https://www.gstatic.com/firebasejs/8.0.1/firebase-database.js"></script>

                <script>
                    var app = firebase.initializeApp({
                        apiKey: "AIzaSyBhJSq8zsnlPIbLTnTH5T00VJrELd7nWBE",
                        authDomain: "staymenity-push.firebaseapp.com",
                        databaseURL: "https://staymenity-push.firebaseio.com",
                        projectId: "staymenity-push",
                        storageBucket: "staymenity-push.appspot.com",
                        messagingSenderId: "520491198378",
                        appId: "1:520491198378:web:34a5ffb610121e33912dd3"
                    });
                    // ...
                    var database  = firebase.database();
                    var usersRef  = database.ref('data/testing/counter/users/12/notifications');
                    //var usersRef  = database.ref('data/testing/counter/users/1078/messages/49');

                    usersRef.on('value', (snapshot) => {
                        console.log('user was added !!');
                        console.log(snapshot.val());
                    });
                    usersRef.on('child_changed', (snapshot) => {
                        console.log('user was changed !!');
                        console.log(snapshot.val());
                    });
                    //
                    // database.collection("cities").doc("SF")
                    //     .onSnapshot(function(doc) {
                    //         var source = doc.metadata.hasPendingWrites ? "Local" : "Server";
                    //         console.log(source, " data: ", doc.data());
                    //     });
                    //
                    //
                    // var userId = firebase.auth().currentUser.uid;
                    // firebase.database().ref('/users/' + userId).once('value').then(function(snapshot) {
                    //     var username = (snapshot.val() && snapshot.val().username) || 'Anonymous';
                    //     // ...
                    // });
                </script>
            </div>
        </div>
    </div>
@endsection
