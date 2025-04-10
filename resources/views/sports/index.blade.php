<x-adminlayout>
    <h2>Currently Available Items</h2>
    
    <ul class="list-none">
        @foreach($products as $product)
            <li>
                <x-card href="{{route('sports.show', $product->id)}}">
                    <div>
                        <h3>{{$product->pname}}</h3>
                    </div>
                </x-card>
            </li>
        @endforeach
    </ul>

</x-adminlayout>