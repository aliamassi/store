<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
    <div class="aspect-square bg-gray-100 overflow-hidden">
        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
    </div>
    <div class="p-4">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="font-semibold text-lg">{{ $item['name'] }}</h3>
                <p class="text-emerald-700 font-bold mt-1">${{ number_format($item['price'], 2) }}</p>
            </div>
            <form method="POST" action="{{ route('cart.add') }}">
                @csrf
                <input type="hidden" name="id" value="{{ $item['id'] }}">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm px-3 py-2">
                    Add
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    {{-- Make whole card clickable (optional). Wrap with a form for quick add: --}}
    <form method="POST" action="{{ route('cart.add') }}">
        @csrf
        <input type="hidden" name="id" value="{{ $item['id'] }}">
        <button type="submit" class="w-full text-center text-sm text-gray-600 hover:text-emerald-700 py-2 border-t">Click card to add</button>
    </form>
</div>
