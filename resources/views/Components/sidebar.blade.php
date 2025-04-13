@props(['active' => false])

<a {{ $attributes->merge([
    'class' => ($active 
        ? 'nav-link bg-light p-2 text-dark bg-opacity-50' 
        : 'nav-link text-light hover:bg-gray-100 hover:text-black') . 
        ' flex items-center rounded-md px-3 py-2'
    ]) }} 
    aria-current="{{ $active ? 'page' : 'false' }}">
    {{ $slot }}
</a>
