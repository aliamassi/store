<!DOCTYPE html>
<html lang="en" x-data="{ tab: 'hot-drinks' }">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Café Menu</title>

    {{-- If you use Vite + Tailwind in Laravel, keep this and remove the CDN below --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    {{-- Quick start (remove in production if using Vite) --}}
{{--    <script src="https://cdn.tailwindcss.com"></script>--}}
    @vite(['resources/js/app.js','resources/css/app.css'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-900">
<div class="max-w-7xl mx-auto p-6">
    <header class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold">Menu</h1>
            <p class="text-sm text-gray-500">Click any item to add it to your cart.</p>
        </div>

        {{-- Cart summary badge --}}
        <div class="flex items-center gap-2">
            @php $cartCount = array_sum(array_map(fn($i)=>$i['qty'],$cart)); @endphp
            <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-emerald-700 text-sm">
                    Cart: <strong class="ml-1">{{ $cartCount }}</strong> items
                </span>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- LEFT: Products --}}
        <div class="lg:col-span-2">
            {{-- Tabs --}}
            <div class="flex border-b border-gray-200 mb-4 overflow-x-auto">
                <x-tab-button tab="hot-drinks"> Hot Drinks</x-tab-button>
                <x-tab-button tab="waffles"> Waffles</x-tab-button>
                <x-tab-button tab="ice-cream"> Ice Cream</x-tab-button>
                <x-tab-button tab="juices"> Juices</x-tab-button>
            </div>

            {{-- Panels --}}
            @php
                $tabs = [
                    'hot-drinks' => 'Hot Drinks',
                    'waffles'    => 'Waffles',
                    'ice-cream'  => 'Ice Cream',
                    'juices'     => 'Juices',
                ];
            @endphp

            @foreach ($tabs as $key => $label)
                <div x-show="tab === '{{ $key }}'" x-cloak>
                    <h2 class="text-xl font-semibold mb-3">{{ $label }}</h2>

                    <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach ($catalog[$key] as $item)
                            <x-tab-content :item="$item"></x-tab-content>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        {{-- RIGHT: Cart --}}
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sticky top-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold">Your Cart</h2>
                    <form method="POST" action="{{ route('cart.clear') }}">
                        @csrf
                        <button class="text-sm text-red-600 hover:text-red-700">Clear</button>
                    </form>
                </div>

                @if (empty($cart))
                    <p class="text-gray-500 mt-4">No items yet. Add something tasty 😋</p>
                @else
                    <div class="mt-4 space-y-3">
                        @foreach ($cart as $line)
                            <div class="flex gap-3 items-center">
                                <img src="{{ $line['image'] }}" alt="{{ $line['name'] }}" class="w-14 h-14 rounded-lg object-cover bg-gray-100">
                                <div class="flex-1">
                                    <div class="flex justify-between">
                                        <p class="font-medium">{{ $line['name'] }}</p>
                                        <p class="font-semibold">${{ number_format($line['price'] * $line['qty'], 2) }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-600 mt-1">
                                        <span>Qty: {{ $line['qty'] }}</span>

                                        {{-- -1 --}}
                                        <form method="POST" action="{{ route('cart.remove') }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $line['id'] }}">
                                            <button class="px-2 py-0.5 border rounded-md hover:bg-gray-50">-1</button>
                                        </form>

                                        {{-- remove line --}}
                                        <form method="POST" action="{{ route('cart.removeLine') }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $line['id'] }}">
                                            <button class="px-2 py-0.5 border rounded-md hover:bg-gray-50 text-red-600">remove</button>
                                        </form>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-0.5">${{ number_format($line['price'],2) }} each</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t mt-4 pt-4">
                        <div class="flex items-center justify-between text-lg font-semibold">
                            <span>Total</span>
                            <span>${{ number_format($cartTotal, 2) }}</span>
                        </div>

                        <a href="{{ $waLink }}"
                           target="_blank" rel="noopener"
                           class="mt-3 w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2.5">
                            Go WhatsApp
                            {{-- icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.472-.148-.67.15-.198.297-.767.967-.94 1.165-.173.198-.347.223-.644.074-.297-.149-1.254-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.173.198-.297.297-.495.099-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.206-.241-.579-.487-.5-.669-.51l-.571-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.097 3.2 5.082 4.487.71.306 1.264.489 1.694.626.712.227 1.36.195 1.872.118.571-.085 1.758-.718 2.006-1.411.248-.694.248-1.289.173-1.411-.074-.123-.272-.198-.57-.347zM12.004 2c-5.514 0-9.996 4.482-9.996 9.996 0 1.763.462 3.479 1.34 4.996L2 22l5.145-1.331c1.456.796 3.085 1.214 4.858 1.214 5.514 0 9.996-4.482 9.996-9.996S17.518 2 12.004 2z"/>
                            </svg>
                        </a>
                    </div>

                @endif
            </div>

            {{-- Flash messages --}}
            @if (session('success') || session('error'))
                <div class="mt-4">
                    @if (session('success'))
                        <div class="rounded-xl bg-emerald-50 text-emerald-800 px-4 py-3">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="rounded-xl bg-red-50 text-red-800 px-4 py-3">{{ session('error') }}</div>
                    @endif
                </div>
            @endif
            @if(session('success'))
                <script>
                    toastr.success("{{ session('success') }}", "Added to Cart", {
                        timeOut: 2000,
                        closeButton: true,
                        progressBar: true
                    });
                </script>
            @endif
        </aside>
    </div>
</div>
</body>
</html>
