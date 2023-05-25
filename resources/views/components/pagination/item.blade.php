@props(['disabled' => false])

<li {{ $attributes->class(['page-item', 'disabled' => $disabled]) }}>
    {{ $slot }}
</li>
