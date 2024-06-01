<template>
    <div>
        <stripe-elements
            ref="elementsRef"
            :pk="publishableKey"
            :amount="amount"
            locale="en"
            @token="tokenCreated"
            @loading="loading = $event"
        >
        </stripe-elements>
        <button @click="submit">Pay ${{amount / 100}}</button>
    </div>
</template>

<script>
import { StripeElements } from 'vue-stripe-checkout';
export default {
    name: 'ExampleComponent',
    components: {
        StripeElements
    },
    data() {
        return {
            loading: false,
            amount: 1000,
            publishableKey: 'pk_test_51HJdBZIFDQsDl8swJQFrJ1cfZyOhdIpa9s1KIwjllnC495zbbqBPNqrIxVfmiewzccRvdhXWoGL5tPGLRTa53Xxm0094Y0JODp',
            token: null,
            charge: null
        };
    },
    methods: {
        submit () {
            this.$refs.elementsRef.submit();
        },
        tokenCreated (token) {
            console.log(token);
            this.token = token;
            // for additional charge objects go to https://stripe.com/docs/api/charges/object
            this.charge = {
                card_id: token.card.id,
                last: token.card.last4,
                brand: token.card.brand,
                token_id: token.id,
                source: token.id,
                amount: this.amount, // the amount you want to charge the customer in cents. $100 is 1000 (it is strongly recommended you use a product id and quantity and get calculate this on the backend to avoid people manipulating the cost)
                description: this.description // optional description that will show up on stripe when looking at payments
            }
            this.sendTokenToServer(this.charge);
        },
        sendTokenToServer (charge) {
            console.log(charge);
            axios.post('http://api.laravel.staymenity.test/api/user/reservations/2/payment', charge, {
                headers: {
                    'Authorization': 'Bearer 16|bahwEtNBI5TkBOPQYAZmVtYfBYrAw5p9s9uooZh5',
                    'Content-Type': 'application/json',
                    'Access-Control-Allow-Origin': '*',
                    'Access-Control-Allow-Headers': '*',
                    'Accept': 'application/json, text/plain'
                },
            }).then(response => {
                console.log(response);
            }).catch(error => {
                console.log(error);
            })
            // fetch('http://api.laravel.staymenity.test/api/user/reservations/2/payment', {
            //     method: 'POST', // or 'PUT'
            //     headers: {
            //         'Content-Type': 'application/json',
            //         'Authorization': 'Bearer 16|bahwEtNBI5TkBOPQYAZmVtYfBYrAw5p9s9uooZh5',
            //     },
            //     body: JSON.stringify(charge),
            //     mode: 'no-cors',
            // })
            //     .then(response => response.json())
            //     .then(data => {
            //         console.log('Success:', data);
            //     })
            //     .catch((error) => {
            //         console.error('Error:', error);
            //     });
            // Send to charge to your backend server to be processed
            // Documentation here: https://stripe.com/docs/api/charges/create

        }
    },
    created() {
    },
    mounted() {
    },
    computed: {}
}
</script>
<style lang="scss">

</style>
