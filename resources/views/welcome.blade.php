<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    @vite('resources/css/app.css')
</head>
<header>
    <nav>
    <h1>
        <a href="{{route('sports.index')}}">Chess Boards</a>
    </h1>
    
    @guest
        <a href="{{route('show.login')}}" class="btn">Login</a>
        <a href="{{route('show.register')}}" class="btn">Register</a>
    @endguest

    @auth
        <span class="border-r-2 pr-2">
            Hi there, {{Auth::user()->name}}
        </span>
        <a href="{{route('cart.index')}}">View Cart</a>
        <form action="{{route('logout')}}" method="POST" class="m-0">
            @csrf
            <button class="btn">Logout</button>
        </form>
    @endauth

</nav>
</header>
<div class="max-w-6xl mx-auto p-6 pt-20">

  </header>

  <!-- Product Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    
    <!-- Product Card -->
    @foreach($products as $p)
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
      <img src="{{ asset('storage/' . $p->image_path) }}" alt="{{ $p->name }}" class="w-full h-64 object-cover">
      <div class="p-4">
          <h3 class="text-xl text-center font-semibold">{{$p->pname}}</h3>
          <p class="text-gray-500 text-center min-h-[70px]">{{$p->bio}}.</p>
          <p class="text-xl font-bold mt-2 text-center">₦{{ number_format($p->price, 2) }}</p>
          <form action="{{ route('cart.add') }}" method="POST" class="mt-4 py-2">
              @csrf
              <input type="hidden" name="product_id" value="{{ $p->id }}">
              <div class="flex items-center justify-center mt-1">
                  <!-- Quantity Input -->
                  <input type="number" name="quantity" value="1" min="1" class="mt-1 w-16 text-center border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 py-1">
              </div>
              <!-- Add to Cart Button -->
              <button type="submit" class="mt-1 w-full py-2 px-4 bg-green-500 text-white rounded hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
                  Add to Cart
              </button>
          </form>
      </div>
  </div>
    @endforeach
  </div>
</div>

<script>
  function incrementQuantity(button) {
      const quantityInput = button.previousElementSibling;
      let currentValue = parseInt(quantityInput.value);
      if (!isNaN(currentValue)) {
          quantityInput.value = currentValue + 1;
      }
  }

  function decrementQuantity(button) {
      const quantityInput = button.nextElementSibling;
      let currentValue = parseInt(quantityInput.value);
      if (!isNaN(currentValue) && currentValue > 1) {
          quantityInput.value = currentValue - 1;
      }
  }
</script>
</body>
</html>