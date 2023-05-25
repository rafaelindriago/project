@php
    use Illuminate\Support\Carbon;
@endphp

<div class="toast-container position-fixed bottom-0 end-0 mb-3 me-3"
     @if (count($notifications) >= 1) wire:poll.5000ms @endif>

    @foreach ($notifications as $key => $notification)
        <x-toast data-key="{{ $key }}"
                 data-show="false"
                 wire:key="notification-{{ $key }}"
                 wire:ignore.self>
            <x-toast.header>
                @switch($notification['type'])
                    @case('success')
                        <x-icon class="fa-check-circle fa-xl text-success" />
                    @break

                    @case('warning')
                        <x-icon class="fa-exclamation-circle fa-xl text-warning" />
                    @break

                    @case('danger')
                        <x-icon class="fa-times-circle fa-xl text-danger" />
                    @break

                    @default
                        <x-icon class="fa-info-circle fa-xl text-info" />
                    @break
                @endswitch

                <strong class="ms-1 me-auto">
                    {{ $notification['title'] }}
                </strong>
                <small>
                    {{ Carbon::parse($notification['timestamp'])->diffForHumans(['options' => Carbon::JUST_NOW]) }}
                </small>

                <x-toast.close />
            </x-toast.header>

            <x-toast.body>
                {{ $notification['message'] }}
            </x-toast.body>
        </x-toast>
    @endforeach

</div>

@push('js')
    <script>
        window.addEventListener('notificator:show', () => {
            document.querySelectorAll('.toast-container .toast[data-show="false"]')
                .forEach((toastEl) => {
                    let toast = new bootstrap.Toast(toastEl, {
                        delay: 30000
                    })

                    toastEl.addEventListener('hidden.bs.toast', () => {
                        @this.dismiss(toastEl.dataset.key)
                    })
                    toastEl.setAttribute('data-show', true)

                    toast.show()
                })
        })
    </script>
@endpush
