<x-layout>
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Your Cart</h1>

    @if($cartItems->isEmpty())
        <p class="text-center text-gray-600">
            Your cart is empty. 
            <a href="{{ route('welcome') }}" class="text-blue-500 hover:underline">Continue shopping</a>.
        </p>
    @else
        <div class="overflow-x-auto">
            <table class="table-auto w-full border-collapse border border-gray-300 rounded-lg shadow-md">
                <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                    <tr>
                        <th class="border border-gray-300 px-6 py-4 text-left text-gray-700 font-semibold">Image</th>
                        <th class="border border-gray-300 px-6 py-4 text-left text-gray-700 font-semibold">Product</th>
                        <th class="border border-gray-300 px-6 py-4 text-center text-gray-700 font-semibold">Quantity</th>
                        <th class="border border-gray-300 px-6 py-4 text-center text-gray-700 font-semibold">Price</th>
                        <th class="border border-gray-300 px-6 py-4 text-center text-gray-700 font-semibold">Total</th>
                        <th class="border border-gray-300 px-6 py-4 text-center text-gray-700 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <!-- Product Image -->
                            <td class="border border-gray-300 px-6 py-4 text-center">
                                <img src="{{ $item->product->image_path }}" alt="{{ $item->product->pname }}" class="w-20 h-20 object-cover rounded-lg shadow-md">
                            </td>
                            <!-- Product Name -->
                            <td class="border border-gray-300 px-6 py-4 text-gray-800 font-medium">
                                {{ $item->product->pname }}
                            </td>
                            <!-- Quantity -->
                            <td class="border border-gray-300 px-6 py-4 text-center text-gray-800">
                                {{ $item->quantity }}
                            </td>
                            <!-- Price -->
                            <td class="border border-gray-300 px-6 py-4 text-center text-gray-800">
                                ₦{{ number_format($item->product->price, 2) }}
                            </td>
                            <!-- Total -->
                            <td class="border border-gray-300 px-6 py-4 text-center text-gray-800 font-semibold">
                                ₦{{ number_format($item->quantity * $item->product->price, 2) }}
                            </td>
                            <!-- Actions -->
                            <td class="border border-gray-300 px-6 py-4 text-center">
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="hover:bg-gray-50 transition duration-200 px-1 py-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-2 bg-red-500 text-white rounded-lg shadow hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                                        Remove
                                    </button>
                                </form>
                                    <form action="{{ route('cart.increment', $item->id) }}" method="POST" class="inline-block px-2 py-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-2 py-1 bg-green-500 text-white rounded-lg shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                                            +
                                        </button>
                                    </form>
                                        <form action="{{ route('cart.decrement', $item->id) }}" method="POST" class="inline-block px-2 py-1">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-2 py-1 bg-yellow-500 text-white rounded-lg shadow hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                                                -
                                            </button>
                                        </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Display Grand Total -->
        <div class="text-right font-bold text-2xl mt-6 text-gray-800">
            Grand Total: ₦{{ number_format($overallTotal, 2) }}
        </div>

        <!-- Checkout Button -->
        <div class="flex justify-end mt-6">
            <a href="{{ route('cart.showCheckout') }}" class="px-6 py-3 bg-green-500 text-white font-semibold rounded-lg shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                Proceed to Checkout
            </a>
        </div>
    @endif

</x-layout>