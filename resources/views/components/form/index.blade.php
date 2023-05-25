<form {{ $attributes->merge(['method' => 'GET']) }}>
    {{ $slot }}
</form>
