<x-adminlayout>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-md">
        <!-- Product Title -->
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">{{ $product->pname }}</h1>

        <!-- Product Details Section -->
        <div class="bg-gray-100 p-6 rounded-lg shadow-md">
            <!-- Product Image -->
            <div class="mb-6">
                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->pname }}" class="w-full h-64 object-cover rounded-lg shadow-md">
            </div>

            <!-- Product Information -->
            <div class="text-gray-700">
                <p class="text-lg font-semibold mb-2"><strong>Product Name:</strong> {{ $product->pname }}</p>
                <p class="text-lg font-semibold mb-2"><strong>About Product:</strong></p>
                <p class="text-gray-600 leading-relaxed">{{ $product->bio }}</p>
            </div>
            <a href="{{ route('sports.edit', $product->id) }}" class="block w-40 text-center bg-blue-500 text-white py-2 rounded-lg shadow hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mt-4">
                Edit Product
            </a>
        </div>

        <!-- Delete Product Button -->
        <form action="{{ route('sports.destroy', $product->id) }}" method="POST" class="mt-6">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full bg-red-500 text-white py-2 rounded-lg shadow hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                Delete Product
            </button>
        </form>
    </div>
</x-adminlayout>