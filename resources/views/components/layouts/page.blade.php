@extends('layouts.app')

@props(['title', 'icon'])

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <span class="fas fa-fw {{ $icon }}"></span>
                        {{ $title }}
                    </div>

                    <div class="card-body">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>

        <livewire:components.notificator />
    </div>
@endsection
