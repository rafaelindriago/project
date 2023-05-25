<div {{ $attributes->class(['toast'])->merge(['role' => 'alert', 'aria-live' => 'assertive', 'aria-atomic' => 'true']) }}>
    {{ $slot }}
</div>
