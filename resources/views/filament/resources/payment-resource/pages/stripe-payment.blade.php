<x-filament::page>
@php
    $payment = $this->payment;
@endphp
    <div class="container mx-auto">
        <div class="flex justify-center">
            <div class="w-full max-w-md">
                <div class="bg-white rounded-lg shadow-md bg-gray-100 dark:bg-gray-900">
                    <div class="p-6">
                        <h2 class="text-2xl font-semibold mb-4">Payment for Order #{{ $payment->id }}</h2>
                        <p class="text-lg mb-4">Amount: ${{ number_format($payment->amount, 2) }}</p>

                        @if (session('success'))
                        <div 
                            class="text-green-600 border-2 border-green-600 text-center p-2 mb-4">
                            Payment Successful!
                        </div>
                        @endif

                        <form id="checkout-form" method="POST" action="{{ route('stripe.create-charge', ['payment' => $payment->id]) }}">
                            @csrf
                            <input type="hidden" name="stripeToken" id="stripe-token-id">
                            
                            <label for="card-element" class="block text-lg font-medium text-gray-700 mb-5">Card Details</label>
                            <div id="card-element" class="form-control border border-gray-300 dark:border-gray-600 rounded-lg p-2 mb-4"></div>
                            
                            <button type="button" id="pay-btn" class="bg-green-500 text-black mt-4 w-20 h-40 w-full py-2 rounded-lg hover:bg-green-600 transition-colors " onclick="createToken()">
                                PAY ${{ number_format($payment->amount, 2) }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('{{ env('STRIPE_KEY') }}');
        var elements = stripe.elements();
        var cardElement = elements.create('card');
        cardElement.mount('#card-element');

        function createToken() {
            document.getElementById("pay-btn").disabled = true;
            stripe.createToken(cardElement).then(function(result) {
                if (result.error) {
                    document.getElementById("pay-btn").disabled = false;
                    alert(result.error.message);
                }
                if (result.token) {
                    document.getElementById("stripe-token-id").value = result.token.id;
                    document.getElementById('checkout-form').submit();
                }
            });
        }
    </script>

</x-filament::page>

{{-- <x-filament::page>
@php
    $payment = $this->payment;
@endphp

    <div class="flex items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-800">
        <div class="w-full max-w-md p-6 bg-white rounded-lg shadow dark:bg-gray-900">
            <div class="space-y-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Payment for Order #{{ $payment->id }}
                </h2>
                <p class="text-lg text-gray-700 dark:text-gray-300">
                    Amount: ${{ number_format($payment->amount, 2) }}
                </p>

                @if (session('success'))
                    <div class="p-4 text-green-700 bg-green-100 border border-green-400 rounded dark:bg-green-800 dark:text-green-200">
                        Payment Successful!
                    </div>
                @endif

                <form id="checkout-form" method="POST" action="{{ route('stripe.create-charge', ['payment' => $payment->id]) }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="stripeToken" id="stripe-token-id">

                    <label for="card-element" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Card Details
                    </label>
                    <div id="card-element" class="p-3 border text-white dark:text-white border-gray-300 rounded-lg shadow-sm dark:border-gray-700"></div>

                    <button type="button" id="pay-btn" class="w-full px-4 py-2 font-medium dark:text-white text-black bg-green-600 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        PAY ${{ number_format($payment->amount, 2) }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('{{ env('STRIPE_KEY') }}');
        var elements = stripe.elements();
        var cardElement = elements.create('card');
        cardElement.mount('#card-element');

        function createToken() {
            document.getElementById("pay-btn").disabled = true;
            stripe.createToken(cardElement).then(function(result) {
                if (result.error) {
                    document.getElementById("pay-btn").disabled = false;
                    alert(result.error.message);
                }
                if (result.token) {
                    document.getElementById("stripe-token-id").value = result.token.id;
                    document.getElementById('checkout-form').submit();
                }
            });
        }
    </script>

</x-filament::page> --}}
