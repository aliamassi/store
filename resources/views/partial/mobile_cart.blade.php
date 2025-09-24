<div class="inner">
    <a href="#cart"
       class="btn-cart relative flex items-center justify-between w-full max-w-sm bg-yellow-400 text-black font-bold rounded-lg px-4 py-2 shadow-md">

        {{-- Left: Total --}}
        <span class="total text-sm font-semibold">
                    {{ $total }}
                </span>

        {{-- Center: Label --}}
        <span class="flex-1 text-center">
                    See Your Cart
                </span>

        {{-- Right: Count --}}
        <span class="count text-xs font-bold bg-white text-black rounded-full px-2 py-1">
                    {{ $count }}
                </span>
    </a>

</div>
