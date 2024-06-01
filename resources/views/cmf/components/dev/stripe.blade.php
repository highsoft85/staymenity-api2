@extends('cmf.layouts.cmf', [
    'breadcrumb' => 'dev.stripe'
])

@section('content.title')
    @include('cmf.components.pages.title', [
        'title' => 'Stripe',
    ])
@endsection

@push('scripts-before')

@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body" style="padding: 1rem;">
                    <form action="{{ routeCmf('dev.stripe.post') }}" method="post" id="payment-form">
                        <div class="form-row pt-2 pb-2">
                            <div id="card-element">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>
                            <!-- Used to display form errors. -->
                            <div id="card-errors" role="alert"></div>
                        </div>

                        <button type="submit" class="btn btn-success">Submit Payment</button>
                    </form>

                    <script src="https://js.stripe.com/v3/"></script>
                    <script>
                        // Create a Stripe client.
                        var stripe = Stripe('{{ config('services.stripe.test_public_key') }}');

                        // Create an instance of Elements.
                        var elements = stripe.elements();

                        // Custom styling can be passed to options when creating an Element.
                        // (Note that this demo uses a wider set of styles than the guide below.)
                        var style = {
                            base: {
                                color: '#32325d',
                                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                                fontSmoothing: 'antialiased',
                                fontSize: '16px',
                                '::placeholder': {
                                    color: '#aab7c4'
                                }
                            },
                            invalid: {
                                color: '#fa755a',
                                iconColor: '#fa755a'
                            }
                        };

                        // Create an instance of the card Element.
                        var card = elements.create('card', {style: style});

                        // Add an instance of the card Element into the `card-element` <div>.
                        card.mount('#card-element');

                        // Handle real-time validation errors from the card Element.
                        card.addEventListener('change', function(event) {
                            var displayError = document.getElementById('card-errors');
                            if (event.error) {
                                displayError.textContent = event.error.message;
                            } else {
                                displayError.textContent = '';
                            }
                        });

                        // Handle form submission.
                        var form = document.getElementById('payment-form');
                        form.addEventListener('submit', function(event) {
                            event.preventDefault();

                            stripe.createToken(card).then(function(result) {
                                if (result.error) {
                                    // Inform the user if there was an error.
                                    var errorElement = document.getElementById('card-errors');
                                    errorElement.textContent = result.error.message;
                                } else {
                                    // Send the token to your server.
                                    stripeTokenHandler(result.token);
                                }
                            });
                        });

                        // Submit the form with the token ID.
                        function stripeTokenHandler(token) {
                            // Insert the token ID into the form so it gets submitted to the server
                            // var form = document.getElementById('payment-form');
                            // var hiddenInput = document.createElement('input');
                            // hiddenInput.setAttribute('type', 'text');
                            // hiddenInput.setAttribute('name', 'stripeToken');
                            // hiddenInput.setAttribute('value', token.id);
                            // form.appendChild(hiddenInput);
                            //alert('Success! Got token: ' + token.id);

                            console.log(token);
                            stripe
                                .createPaymentMethod({
                                    type: 'card',
                                    card: card,
                                    billing_details: {
                                        name: 'Jenny Rosen',
                                    },
                                })
                                .then(function(result) {
                                    console.log(result);
                                    // Handle result.error or result.paymentMethod

                                    fetch('{{ routeCmf('dev.stripe.post') }}', {
                                        method: 'POST', // or 'PUT'
                                        headers: {
                                            'Content-Type': 'application/json',
                                        },
                                        body: JSON.stringify({
                                            payment_method_id: result.paymentMethod.id,
                                        }),
                                    })
                                        .then(response => response.json())
                                        .then(data => {
                                            console.log('Success:', data);
                                        })
                                        .catch((error) => {
                                            console.error('Error:', error);
                                        });
                                });
                            // Submit the form
                            // form.submit();
                        }
                    </script>

                </div>
            </div>
        </div>
    </div>
@endsection
