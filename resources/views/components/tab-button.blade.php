@props(['tab' => 'hot-drinks'])
<button @click="tab='{{$tab}}'"
        {{$attributes->merge(["class"=>"px-4 py-2 border-b-2 font-medium whitespace-nowrap tab === $tab ? border-emerald-500 text-emerald-700 : border-transparent text-gray-500 hover:text-gray-700"])}}>
    {{$slot}}


</button>
