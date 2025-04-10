<x-adminlayout>
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Orders</h1>

    @if ($orders->isEmpty())
        <p class="text-center text-gray-600">No orders found.</p>
    @else
        <div class="overflow-x-auto">
            <table class="table-auto w-full border-collapse border border-gray-300 rounded-lg shadow-md">
                <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                    <tr>
                        <th class="border border-gray-300 px-4 py-3 text-left text-gray-700 font-semibold">Order ID</th>
                        <th class="border border-gray-300 px-4 py-3 text-left text-gray-700 font-semibold">User</th>
                        <th class="border border-gray-300 px-4 py-3 text-left text-gray-700 font-semibold">Reference</th>
                        <th class="border border-gray-300 px-4 py-3 text-right text-gray-700 font-semibold">Total</th>
                        <th class="border border-gray-300 px-4 py-3 text-center text-gray-700 font-semibold">Status</th>
                        <th class="border border-gray-300 px-4 py-3 text-center text-gray-700 font-semibold">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="border border-gray-300 px-4 py-3 text-gray-800">{{ $order->id }}</td>
                            <td class="border border-gray-300 px-4 py-3 text-gray-800">{{ $order->user->name }}</td>
                            <td class="border border-gray-300 px-4 py-3 text-gray-800">{{ $order->reference }}</td>
                            <td class="border border-gray-300 px-4 py-3 text-right text-gray-800 font-semibold">₦{{ number_format($order->total, 2) }}</td>
                            <td class="border border-gray-300 px-4 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-white 
                                {{ $order->status === 'pending' ? 'bg-yellow-500' : ($order->status === 'completed' ? 'bg-green-500' : 'bg-red-500') }}">
                                {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="border border-gray-300 px-4 py-3 text-center text-gray-800">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td class="border border-gray-300 px-4 py-3 text-center">
                            @if ($order->status === 'pending')
                            <form action="{{ route('admin.orders.complete', $order->id) }}" method="POST" class="inline-block mt-2 py-1 px-1">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    Mark as Completed
                                </button>
                            </form>
                             @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="border border-gray-300 px-4 py-4 bg-gray-50">
                                <h3 class="font-semibold text-gray-700 mb-2">Ordered Items:</h3>
                                <ul class="pl-6 text-gray-600 list-none">
                                    @foreach ($order->items as $item)
                                        <li>
                                            <span class="font-medium">{{ $item->product->pname }}</span> 
                                            (x{{ $item->quantity }}) - 
                                            <span class="text-gray-800 font-semibold">₦{{ number_format($item->price, 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-adminlayout>