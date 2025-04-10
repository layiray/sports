<x-layout>
    <body>
        <div class="checkout-container max-w-4xl mx-auto p-6 bg-white shadow-md rounded-md">
            <h1 class="text-2xl font-bold mb-6">Checkout</h1>
            <form action="{{ route('cart.processCheckout') }}" method="POST" class="checkout-form" id="checkout-form" enctype="multipart/form-data">
                @csrf
                <!-- Billing Information -->
                <section class="mb-6">
                    <h2 class="text-lg font-semibold mb-4">Billing Address</h2>
                    <input type="hidden" name="reference" value="order_{{ uniqid() }}">

                    <label for="billing-name" class="block mb-2">Full Name</label>
                    <input type="text" id="billing-name" name="billing-name" class="w-full p-2 border border-gray-300 rounded mb-4" required>

                    <label for="billing-address" class="block mb-2">Address</label>
                    <input type="text" id="billing-address" name="billing-address" class="w-full p-2 border border-gray-300 rounded mb-4" required>

                    <label for="billing-city" class="block mb-2">City</label>
                    <input type="text" id="billing-city" name="billing-city" class="w-full p-2 border border-gray-300 rounded mb-4" required>

                    <label for="billing-zip" class="block mb-2">ZIP Code</label>
                    <input type="text" id="billing-zip" name="billing-zip" class="w-full p-2 border border-gray-300 rounded mb-4" required>

                    <label for="billing-country" class="block mb-2">Country</label>
                    <input type="text" id="billing-country" name="billing-country" class="w-full p-2 border border-gray-300 rounded mb-4" required>
                </section>

                <!-- Shipping Method -->
                <section class="mb-6">
                    <h2 class="text-lg font-semibold mb-4">Shipping Method</h2>
                    <div class="flex items-center mb-4">
                        <label for="standard-shipping" class="text-sm">Standard Shipping (3-5 days)</label>
                        <input type="radio" id="standard-shipping" name="shipping" value="standard" class="mr-1" checked>
                    </div>
                    <div class="flex items-center">
                        <label for="express-shipping" class="text-sm">Express Shipping (1-2 days)</label>
                        <input type="radio" id="express-shipping" name="shipping" value="express" class="mr-1">
                    </div>
                </section>

                <!-- Payment Information -->
                <section class="mb-6">
                    <h2 class="text-lg font-semibold mb-4">Payment Information</h2>
                    <label for="card-name" class="block mb-2">Cardholder Name</label>
                    <input type="text" id="card-name" name="card-name" class="w-full p-2 border border-gray-300 rounded mb-4" required>

                    <label for="card-number" class="block mb-2">Card Number</label>
                    <input type="text" id="card-number" name="card-number" class="w-full p-2 border border-gray-300 rounded mb-4" required>

                    <label for="expiration-date" class="block mb-2">Expiration Date</label>
                    <input type="month" id="expiration-date" name="expiration-date" class="w-full p-2 border border-gray-300 rounded mb-4" required>

                    <label for="cvv" class="block mb-2">CVV</label>
                    <input type="text" id="cvv" name="cvv" class="w-full p-2 border border-gray-300 rounded mb-4" required>
                </section>

                <!-- Order Summary -->
                <section class="mb-6">
                    <h2 class="text-lg font-semibold mb-4">Order Summary</h2>
                    <div>
                        @foreach ($cartItems as $item)
                            <div class="flex justify-between mb-2">
                                <span>{{ $item->product->pname }} ({{ $item->quantity }})</span>
                                <span>₦{{ number_format($item->quantity * $item->product->price, 2) }}</span>
                            </div>
                        @endforeach
                        <div class="flex justify-between font-bold">
                            <span>Total</span>
                            <span>₦{{ number_format($overallTotal, 2) }}</span>
                        </div>
                    </div>
                </section>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600" id="paystack-button">
                    Place Order
                </button>
            </form>
        </div>

        <!-- Paystack Script -->
        <script src="https://js.paystack.co/v1/inline.js"></script>

        <script>
            document.getElementById('paystack-button').addEventListener('click', function(e) {
                e.preventDefault();
        
                // Initialize Paystack payment
                var handler = PaystackPop.setup({
                    key: 'pk_test_daf51e21ba22742667d8f4a5ce43b619afa39b1e', // Paystack Public Key
                    email: '{{ Auth::user()->email }}', // User's email address
                    amount: {{ $overallTotal * 100 }}, // Amount in kobo (multiply by 100)
                    currency: 'NGN',
                    ref: 'order_' + Math.floor((Math.random() * 1000000000) + 1), // Generate a random reference number
                    callback: function(response) {
                        // Notify the user of success
                        alert('Payment Successful! Transaction Reference: ' + response.reference);
        
                        // Send the transaction reference and form data to the backend
                        fetch('{{ route('cart.processCheckout') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                reference: response.reference,
                                billing_name: document.getElementById('billing-name').value,
                                billing_address: document.getElementById('billing-address').value,
                                billing_city: document.getElementById('billing-city').value,
                                billing_zip: document.getElementById('billing-zip').value,
                                billing_country: document.getElementById('billing-country').value,
                                shipping: document.querySelector('input[name="shipping"]:checked').value,
                                card_name: document.getElementById('card-name').value,
                                card_number: document.getElementById('card-number').value,
                                expiration_date: document.getElementById('expiration-date').value,
                                cvv: document.getElementById('cvv').value,
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Redirect to the home page
                                window.location.href = '{{ route('welcome') }}';
                            } else {
                                alert('Failed to process checkout. Please contact support.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        });
                    },
                    onClose: function() {
                        alert('Payment window closed.');
                    }
                });
        
                handler.openIframe(); // Open Paystack's payment iframe
            });
        </script>
    </body>

    @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li class="text-red-500">{{ $error }}</li>
        @endforeach
    </ul>
@endif
</x-layout>
