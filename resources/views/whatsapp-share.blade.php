<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Order via WhatsApp</title>
    @vite(['resources/js/app.js','resources/css/app.css'])
</head>
<body class="bg-gray-50">
<div class="max-w-2xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-2 text-center">Share Your Order via WhatsApp</h1>
        <p class="text-gray-600 text-center mb-6">Choose how you'd like to share your order</p>

        {{-- Order Summary --}}
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-semibold mb-3">Your Order:</h3>
            <div class="space-y-2">
                @foreach($cart as $item)
                    <div class="flex justify-between items-center">
                        <span>{{ $item['name'] }} × {{ $item['qty'] }}</span>
                        <span class="font-semibold">${{ number_format($item['price'] * $item['qty'], 2) }}</span>
                    </div>
                @endforeach
                <div class="border-t pt-2 flex justify-between items-center font-bold">
                    <span>Total:</span>
                    <span>${{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['qty'], $cart)), 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Sharing Options --}}
        <div class="space-y-4">
            @foreach($links as $index => $link)
                <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                    @if($link['type'] === 'text')
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-green-600">{{ $link['title'] }}</h3>
                                    <p class="text-sm text-gray-600">{{ $link['description'] }}</p>
                                </div>
                            </div>
                            <a href="{{ $link['url'] }}"
                               target="_blank"
                               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.472-.148-.67.15-.198.297-.767.967-.94 1.165-.173.198-.347.223-.644.074-.297-.149-1.254-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.173.198-.297.297-.495.099-.198.05-.371-.025-.52-.074-.149-.669-1.612-.916-2.206-.241-.579-.487-.5-.669-.51l-.571-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.097 3.2 5.082 4.487.71.306 1.264.489 1.694.626.712.227 1.36.195 1.872.118.571-.085 1.758-.718 2.006-1.411.248-.694.248-1.289.173-1.411-.074-.123-.272-.198-.57-.347zM12.004 2c-5.514 0-9.996 4.482-9.996 9.996 0 1.763.462 3.479 1.34 4.996L2 22l5.145-1.331c1.456.796 3.085 1.214 4.858 1.214 5.514 0 9.996-4.482 9.996-9.996S17.518 2 12.004 2z"/>
                                </svg>
                                Send Order
                            </a>
                        </div>
                    @else
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <img src="{{ $link['image'] }}"
                                     alt="Product"
                                     class="w-12 h-12 rounded-lg object-cover shadow-sm">
                                <div>
                                    <h3 class="font-semibold">{{ $link['title'] }}</h3>
                                    <p class="text-sm text-gray-600">{{ $link['description'] }}</p>
                                    <div class="flex items-center gap-2 mt-1 text-xs text-gray-500">
                                        <span>Qty: {{ $link['product']['qty'] }}</span>
                                        <span>•</span>
                                        <span>${{ number_format($link['product']['price'] * $link['product']['qty'], 2) }}</span>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ $link['url'] }}"
                               target="_blank"
                               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Share
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Instructions --}}
        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-medium text-blue-900 mb-1">How to share:</h4>
                    <ol class="text-sm text-blue-700 space-y-1">
                        <li>1. <strong>Send Order Details</strong> - Click first to share your complete order</li>
                        <li>2. <strong>Share Product Images</strong> - Click each product to share individual images</li>
                        <li>3. Each click opens WhatsApp with pre-filled message ready to send</li>
                    </ol>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="mt-6 flex justify-between items-center">
            <a href="{{ route('menu.index') }}"
               class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Menu
            </a>

            <div class="text-sm text-gray-500">
                {{ count($cart) }} items in cart
            </div>
        </div>
    </div>
</div>

<script>
    // Add click tracking for analytics (optional)
    document.querySelectorAll('a[target="_blank"]').forEach(link => {
        link.addEventListener('click', function() {
            console.log('WhatsApp share clicked:', this.textContent.trim());
        });
    });
</script>
</body>
</html>
