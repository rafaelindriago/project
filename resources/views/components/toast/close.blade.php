<button {{ $attributes->class(['btn-close'])->merge(['type' => 'button', 'data-bs-dismiss' => 'toast', 'aria-label' => 'Close']) }}>
    {{ $slot }}
</button>
