<!DOCTYPE html>
<html lang="en" x-data="{ tab: 'hot-drinks' }">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Café Menu</title>

    {{-- If you use Vite + Tailwind in Laravel, keep this and remove the CDN below --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    {{-- Quick start (remove in production if using Vite) --}}
    {{--    <script src="https://cdn.tailwindcss.com"></script>--}}
    @vite(['resources/js/app.js','resources/css/app.css'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        /* Hidden by default (desktop/tablet). We’ll show it on small screens only */
        #mobile-cart-bar {
            display: none;
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            background: #111; /* dark background */
            color: #fff;
            padding: 12px 16px;
            /* Respect iOS safe area (notches) */
            padding-bottom: calc(12px + env(safe-area-inset-bottom));
            box-shadow: 0 -6px 16px rgba(0, 0, 0, .25);
        }

        #mobile-cart-bar .inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        #mobile-cart-bar .title {
            display: flex;
            gap: 10px;
            align-items: center;
            font-weight: 600;
            font-size: 15px;
        }

        #mobile-cart-bar .btn-cart {
            background: #ffc107; /* warm highlight */
            color: #111;
            font-weight: 700;
            border: 0;
            border-radius: 10px;
            padding: 10px 14px;
            text-decoration: none;
            white-space: nowrap;
            width: 100%;
        }

        #mobile-cart-bar .count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            height: 24px;
            padding: 0 6px;
            font-size: 12px;
            font-weight: 700;
            color: #111;
            background: #fff;
            border-radius: 9999px;
        }

        /* Show the bar on phone view only */
        @media (max-width: 768px) {
            #mobile-cart-bar {
                display: block;
            }
        }
    </style>
</head>
<body class="antialiased bg-gray-100">
<div class="max-w-7xl mx-auto p-6">
    <header class="">
        <div class="max-w-7xl mx-auto  py-3 flex items-center justify-between">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center">
                <img style="height: 80px;" src="{{ asset('images/logo.jpeg') }}" alt="Logo" class="h-10 w-auto">
{{--                <span class="ml-2 text-xl font-bold text-gray-700">Scoop</span>--}}
            </a>
        </div>
    </header>
{{--    <header class="mb-6 flex items-center justify-between">--}}
{{--        <div>--}}
{{--            <h1 class="text-3xl font-bold">Scoop</h1>--}}
{{--            <p class="text-sm text-gray-500">Click any item to add it to your cart.</p>--}}
{{--        </div>--}}

{{--        --}}{{-- Cart summary badge --}}
{{--        <div class="flex items-center gap-2">--}}
{{--            @php $cartCount = array_sum(array_map(fn($i)=>$i['qty'],$cart)); @endphp--}}
{{--            <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-emerald-700 text-sm">--}}
{{--                    Cart: <strong class="ml-1">{{ $cartCount }}</strong> items--}}
{{--                </span>--}}
{{--        </div>--}}
{{--    </header>--}}

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- LEFT: Products --}}
        <div class="lg:col-span-2">
            <div class="sticky border-0 top-0  z-50 bg-white shadow">
                {{-- Tabs --}}
                <div class="flex border-b border-gray-200 mb-4 overflow-x-auto">
                    <x-tab-button tab="hot-drinks"> Hot Drinks</x-tab-button>
                    <x-tab-button tab="waffles"> Waffles</x-tab-button>
                    <x-tab-button tab="ice-cream"> Ice Cream</x-tab-button>
                    <x-tab-button tab="juices"> Juices</x-tab-button>
                </div>
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
        <aside class="lg:col-span-1" id="cart">
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
                            <div class="flex gap-3 items-center cart-item">
                                <img src="{{ $line['image'] }}" alt="{{ $line['name'] }}"
                                     class="w-14 h-14 rounded-lg object-cover bg-gray-100">
                                <div class="flex-1">
                                    <div class="flex justify-between">
                                        <p class="font-medium">{{ $line['name'] }}</p>
                                        <p class="font-semibold">
                                            ${{ number_format($line['price'] * $line['qty'], 2) }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm text-gray-600 mt-1">
                                        <span class="qty">Qty: {{ $line['qty'] }}</span>

                                        {{-- -1 --}}
                                        <form method="POST" action="{{ route('cart.remove') }}" class="ajax-request"
                                              data-decrease="yes">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $line['id'] }}">
                                            @if($line['qty'] > 1)
                                                <button
                                                    class="px-2 py-0.5 border rounded-md hover:bg-gray-50 decrease-btn">
                                                    -1
                                                </button>
                                            @endif
                                        </form>

                                        {{-- remove line --}}
                                        <form method="POST" action="{{ route('cart.removeLine') }}" class="ajax-request"
                                              data-remove="yes">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $line['id'] }}">
                                            <button class="px-2 py-0.5 border rounded-md hover:bg-gray-50 text-red-600">
                                                remove
                                            </button>
                                        </form>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-0.5">${{ number_format($line['price'],2) }}
                                        each</p>
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                 fill="currentColor">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.472-.148-.67.15-.198.297-.767.967-.94 1.165-.173.198-.347.223-.644.074-.297-.149-1.254-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.173.198-.297.297-.495.099-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.206-.241-.579-.487-.5-.669-.51l-.571-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.097 3.2 5.082 4.487.71.306 1.264.489 1.694.626.712.227 1.36.195 1.872.118.571-.085 1.758-.718 2.006-1.411.248-.694.248-1.289.173-1.411-.074-.123-.272-.198-.57-.347zM12.004 2c-5.514 0-9.996 4.482-9.996 9.996 0 1.763.462 3.479 1.34 4.996L2 22l5.145-1.331c1.456.796 3.085 1.214 4.858 1.214 5.514 0 9.996-4.482 9.996-9.996S17.518 2 12.004 2z"/>
                            </svg>
                        </a>
                    </div>

                    {{-- WhatsApp sharing options --}}
                    <div class="mt-3 space-y-2">
                        {{-- Quick share (text only) --}}
                        <a href="{{ $waLink }}"
                           target="_blank" rel="noopener"
                           class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2.5 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                 fill="currentColor">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.472-.148-.67.15-.198.297-.767.967-.94 1.165-.173.198-.347.223-.644.074-.297-.149-1.254-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.173.198-.297.297-.495.099-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.206-.241-.579-.487-.5-.669-.51l-.571-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.097 3.2 5.082 4.487.71.306 1.264.489 1.694.626.712.227 1.36.195 1.872.118.571-.085 1.758-.718 2.006-1.411.248-.694.248-1.289.173-1.411-.074-.123-.272-.198-.57-.347zM12.004 2c-5.514 0-9.996 4.482-9.996 9.996 0 1.763.462 3.479 1.34 4.996L2 22l5.145-1.331c1.456.796 3.085 1.214 4.858 1.214 5.514 0 9.996-4.482 9.996-9.996S17.518 2 12.004 2z"/>
                            </svg>
                            Quick Share
                        </a>

                        {{-- Share with individual images --}}
                        <a href="{{ route('whatsapp.share') }}"
                           class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Share with Images
                        </a>

                        {{-- Share all images in one message --}}
                        {{--                        <a href="{{ route('whatsapp.share.all') }}"--}}
                        {{--                           class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-medium py-2.5 text-sm">--}}
                        {{--                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">--}}
                        {{--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>--}}
                        {{--                            </svg>--}}
                        {{--                            Share All Links--}}
                        {{--                        </a>--}}
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
        </aside>
    </div>
</div>

{{-- MOBILE BOTTOM CART BAR --}}
<div id="mobile-cart-bar" role="region" aria-label="Cart notification">
    <div class="inner">
        <a href="#cart"
           class="btn-cart relative flex items-center justify-between w-full max-w-sm bg-yellow-400 text-black font-bold rounded-lg px-4 py-2 shadow-md">

            {{-- Left: Total --}}
            <span class="total text-sm font-semibold">
                    {{ session('total') }}
                </span>

            {{-- Center: Label --}}
            <span class="flex-1 text-center">
                    See Your Cart
                </span>

            {{-- Right: Count --}}
            <span class="count text-xs font-bold bg-white text-black rounded-full px-2 py-1">
                    {{ session('count') }}
                </span>
        </a>

    </div>

</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="/lib/ajax-request.js"></script>
<script>
    // Prevent the bar from covering content: add bottom padding equal to bar height
    (function () {
        const bar = document.getElementById('mobile-cart-bar');
        if (!bar) return;
        const isPhone = window.matchMedia('(max-width: 768px)').matches;
        if (!isPhone) return;

        const addSpacer = () => {
            const h = bar.getBoundingClientRect().height;
            document.documentElement.style.setProperty('--cart-bar-height', h + 'px');
            document.body.style.paddingBottom = 'var(--cart-bar-height)';
        };

        // Initial + on resize (orientation change, keyboard, etc.)
        addSpacer();
        window.addEventListener('resize', addSpacer, {passive: true});
    })();

    document.addEventListener("DOMContentLoaded", function () {
        const btn = document.querySelector(".btn-cart");
        if (btn) {
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                const cart = document.getElementById("cart");
                if (cart) {
                    cart.scrollIntoView({behavior: "smooth", block: "start"});
                }
            });
        }
    });
</script>
<script>
    $(document).on('click', '.cart-add', function () {
        var id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: "{{route('cart.add')}}",
            data: {
                id: id,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            success: function (data) {
                if (data.status) {
                    $('#cart').html(data.cart_view);
                    $('#mobile-cart-bar').html(data.mobile_cart_view);
                }
            }
        }).fail(function (jqXhr) {
        }).always(function () {
        });
    });
</script>
</body>
</html>
