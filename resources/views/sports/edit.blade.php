<x-adminlayout>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Edit Product</h1>

        <form action="{{ route('sports.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- Product Name -->
            <label for="pname" class="block mb-2 font-semibold">Product Name</label>
            <input type="text" id="pname" name="pname" value="{{ old('pname', $product->pname) }}" class="w-full p-2 border border-gray-300 rounded mb-4" required>

            <!-- Product Bio -->
            <label for="bio" class="block mb-2 font-semibold">About Product</label>
            <textarea id="bio" name="bio" rows="4" class="w-full p-2 border border-gray-300 rounded mb-4" required>{{ old('bio', $product->bio) }}</textarea>

            <!-- Product Image -->
            <label for="image" class="block mb-2 font-semibold">Product Image</label>
            <input type="file" id="image" name="image" class="w-full p-2 border border-gray-300 rounded mb-4">
            <p class="text-sm text-gray-500">Leave blank to keep the current image.</p>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg shadow hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Update Product
            </button>
        </form>
    </div>
</x-adminlayout>