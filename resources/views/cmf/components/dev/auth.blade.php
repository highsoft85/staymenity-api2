@extends('cmf.layouts.cmf', [
    'breadcrumb' => 'dev.auth'
])

@section('content.title')
    @include('cmf.components.pages.title', [
        'title' => 'Auth',
    ])
@endsection

@push('scripts-before')
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <meta name="google-signin-client_id" content="684732544325-48odfkj2bdv71vbmostq9fup6dg09ucv.apps.googleusercontent.com">
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId            : 325778595353647,
                autoLogAppEvents : true,
                xfbml            : true,
                version          : 'v9.0'
            });
            FB.login(function(response) {
                console.log(response);
                if (response.authResponse) {
                    console.log('Welcome!  Fetching your information.... ');
                    FB.api('/me', function(response) {
                        console.log('Good to see you, ' + response.name + '.');
                        console.log(response);
                    });
                } else {
                    console.log('User cancelled login or did not fully authorize.');
                }
            });
        };
    </script>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>
    {{--
        authResponse:
        accessToken: "EAAEoSz1zlC8BAGQgVFJHlsK1YLXduaESAWz6HLb2xvuY86jXhdNNHpYOAXtuwb7JZCtm3cBe9REBZCzOB5cmTSZCJUcKOU10bxtxSV5TjbNZCErgIVV4tNoZAFMgWAuuARg7bidf6qWdZAAQ2oznV0ZAxdTmNMPzqLCnBAvj3KecHwLSZA07sXzVVH8mqkq4ZCS6QkcvC7MPNKKxMRt5TrsZB9bu0ZC75zKz2kZD"
        data_access_expiration_time: 1613232981
        expiresIn: 6219
        graphDomain: "facebook"
        signedRequest: "gY8aykVc93cmovH8x5eJJ48PcjkI4CWNavxxeXgENP4.eyJ1c2VyX2lkIjoiMTA4MzUxNTc3NzA3NDI4IiwiY29kZSI6IkFRQ0NFTkdIZnBpWWxCRTBJZVJlNkNsQTlmQnJSTXJEMlJPSTdNS01ZTnJ4dVFmZld4TmF2QXhWSk5GZndOYUxyOWN2QW8wUzJpV3kzUWRmZnNnRWhENUw5TDhXc2RiZGI1RTl0WVdyanVjLU9RTF9FWDZLbDRTTkQ1UFRJdHZFeEVkZE1vbzhVdzFoanMxOGVBelptQ1RfRDN4UThXOEhJM3dpcWhuTEFQRkhZdEVMaEhvX0VyNnF5eVluXzBWbFFDX1hhRGYtczc5N3UxQ1l4aHR5T0RZeFFjSWdTY0wxMXdPa1U2RW9NLUdYSDNMSzZSTm9OMGMtSTBXc3pLOWdsZ3k3eHJMLU8xZ0IzekhFUGhPV0h1S2dDQ0p4V1pSZERvbnRsUG5mVWx1NTNFOU5EYmNPbnRuOHNSM0ZJUTJFQnh0X0MyYkk4UjM2RTkwLUpKRUFfc0RmIiwiYWxnb3JpdGhtIjoiSE1BQy1TSEEyNTYiLCJpc3N1ZWRfYXQiOjE2MDU0NTY5ODF9"
        userID: "108351577707428"
    --}}
    {{--
       access_token: "ya29.A0AfH6SMBBHAqd2TPptU-Tx9b4w4WGyH_eDcv0QxF4wjvDwIW4dyq6ErzDM9-UzVcqERZN5Y6Sma6uHCQgumwupkri4Yy4BB-3C4NaL8ZoCXbjenaEC0o5h2t-AJoCKQzk-x8rOJBg31RXWzDehKs1-7N-B2V0eH69f1uvJBszpZg"
        expires_in: 3599
        id_token: "eyJhbGciOiJSUzI1NiIsImtpZCI6ImQ5NDZiMTM3NzM3Yjk3MzczOGU1Mjg2YzIwOGI2NmU3YTM5ZWU3YzEiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXpwIjoiNjg0NzMyNTQ0MzI1LTQ4b2Rma2oyYmR2NzF2Ym1vc3RxOWZ1cDZkZzA5dWN2LmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiYXVkIjoiNjg0NzMyNTQ0MzI1LTQ4b2Rma2oyYmR2NzF2Ym1vc3RxOWZ1cDZkZzA5dWN2LmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwic3ViIjoiMTAxMzc2ODU5MjY1Nzk2NTk5MjM2IiwiZW1haWwiOiJ3ZWVrZW5kYnVzdG91ckBnbWFpbC5jb20iLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwiYXRfaGFzaCI6Ik9GWThwY0hfNzZJRUdIdlQ5anlMQ0EiLCJuYW1lIjoiV2Vla2VuZCBCdXMgVG91ciIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS0vQU9oMTRHZ1EtMDJJM2NreHRlN1FzR3Nha3JjelRQV2xqZ0tlU211a2JrQT1zOTYtYyIsImdpdmVuX25hbWUiOiJXZWVrZW5kIiwiZmFtaWx5X25hbWUiOiJCdXMgVG91ciIsImxvY2FsZSI6InJ1IiwiaWF0IjoxNjA1NDY0MTQ5LCJleHAiOjE2MDU0Njc3NDksImp0aSI6IjE3N2M1YzFkOTFjY2QxMTFjMTE3Y2RmYWVhZWQyZjE3MTk4N2VhNWYifQ.HNOhMqinesnyzGX3WApMYxg-SEfA4vKC0wz9oHVoiMM9ivfdXZ1EsAE7zjdI__tugOiS5umiKC-kytG11Molzn1myTE8PU3vTOB7RvyKiEFVJHj6U1GsPx4hfVqOEzxiY0X9A-YCwHN_COd2rmCMOPIbZNyD-PNDhQs1nBGwysHrKhxjKDJd1EuHzVmuxwmLxgTbah7Rs5nQYjb3_I0lYVeP3hzvoKtkENwdJOPEAvvOjfUEr32un1SN8HdP3JjMxyAE49NTmsvfTEfBUVKFyHVhErErQWAn_LuaazsZ7I8Rg8bbjnuLIspvxxPwM06L61THZVl0ii7ceI7LdfJizg"
        login_hint: "AJDLj6JeSJVUO1en4gaohNXlVZQrJOfEYpGHSaVNNtphThErIRhqZedA8wDDJJdavCX4d_bIKlQN02DT7hF0bor2uhv08l3YUZkKB2nt4QT1Rn0lcV4P01I"
        scope: "email profile openid https://www.googleapis.com/auth/profile.emails.read https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile"
        session_state: {extraQueryParams: {authuser: "0"}}
        token_type: "Bearer"
    --}}
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-12 mb-1 mt-1">
                        <div class="g-signin2" data-onsuccess="onSignIn"></div>

                        <!-- Display Google sign-in button -->
                        <div id="gSignIn"></div>

                        <!-- Show the user profile details -->
                        <div class="userContent" style="display: none;"></div>
                    </div>
                    <div>
                        facebook.client_id <code>{{ config('services.facebook.client_id') }}</code> <code>{{ env('FACEBOOK_CLIENT_ID') }}</code>
                        <br>
                        facebook.client_secret <code>{{ config('services.facebook.client_secret') }}</code>
                    </div>
                    <div>
                        google.client_id <code>{{ config('services.google.client_id') }}</code> <code>{{ env('GOOGLE_CLIENT_ID') }}</code>
                        <br>
                        google.client_secret <code>{{ config('services.google.client_secret') }}</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
