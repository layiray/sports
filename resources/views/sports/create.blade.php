<x-adminlayout>
    <form action="{{route('sports.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <h2>Create a New Item</h2>

        <!--name -->
        <label for="name">Product Name: </label>
        <input
        type="text"
        id="pname"
        name="pname"
        value="{{old('pname')}}"
        required
        >
        <!--price -->
        <label for="price">Price: </label>
        <input
        type="number"
        id="price"
        name="price"
        value="{{old('price')}}"
        required
        >
        <!--details -->
        <label for="bio">Product Details: </label>
        <textarea
        rows="5"
        id="bio"
        name="bio"
        required
        >{{old('bio')}}</textarea>

        <div class="mb-4">
            <label for="image">Upload Image:</label>
            <input type="file" name="image" accept="image/*" required>
        </div>

        </select>

        <button type="submit" class="btn mt-4">Create Item</button>

        <!-- validation errors -->
        @if ($errors->any())
            <ul class="px-4 py-2 bg-red-100">
                @foreach($errors->all() as $error)
                    <li class=" my-2 text-red-500">{{$error}}</li>
                @endforeach
            </ul>
        @endif


</x-adminlayout>