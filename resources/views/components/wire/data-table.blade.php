@php
    use Illuminate\Support\Arr;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Str;
@endphp

@props([
    'toolBar' => null,
    'schema',
    'columnSorts',
    'columnFilters',
    'columnStatus',
    'paginator',
])

<div class="container-fluid g-0"
     wire:poll.300000ms>

    <div class="row mb-2">
        <div class="col-12 col-md-auto mb-2 mb-md-0">
            <x-button class="btn-primary">
                <x-icon class="fa-pen" />
                {{ trans('datatables.new') }}
            </x-button>

            <x-button :title="trans('datatables.refresh')"
                      class="btn-light"
                      wire:click="$refresh">
                <span wire:loading.remove
                      wire:target="$refresh">
                    <x-icon class="fa-sync-alt" />
                </span>

                <span wire:loading
                      wire:target="$refresh">
                    <x-icon class="fa-circle-notch fa-spin" />
                </span>
            </x-button>

            <x-button :title="trans('datatables.restore')"
                      class="btn-light"
                      wire:click="restore">
                <span wire:loading.remove
                      wire:target="restore">
                    <x-icon class="fa-undo-alt" />
                </span>

                <span wire:loading
                      wire:target="restore">
                    <x-icon class="fa-circle-notch fa-spin" />
                </span>
            </x-button>

            <x-button :title="trans('datatables.settings')"
                      class="btn-light"
                      data-bs-toggle="modal"
                      data-bs-target="#data-table-settings">
                <x-icon class="fa-cog" />
            </x-button>
        </div>

        <div class="col-12 col-md-6">
            {{ $toolBar }}
        </div>
    </div>

    <div class="row g-0 mb-2">
        <div class="col-12">
            <div class="table-responsive">
                <x-table class="table-sm table-bordered table-striped table-hover mb-0">
                    <x-table.head>

                        <x-table.row>
                            <x-table.heading rowspan="2"
                                             class="text-center align-middle"
                                             style="width: 125px;">
                                <x-icon class="fa-cog" />
                            </x-table.heading>

                            @foreach ($schema as $key => $column)
                                @if (Arr::get($columnStatus, $key, true) === true)
                                    <x-table.heading class="text-nowrap">
                                        <x-button :title="trans('datatables.sort')"
                                                  :disabled="$column->sortable === false"
                                                  class="btn-sm btn-link"
                                                  wire:click="sortColumn('{{ $key }}')">

                                            @switch(Arr::get($columnSorts, $key))
                                                @case('asc')
                                                    <span wire:loading.remove
                                                          wire:target="sortColumn('{{ $key }}')">
                                                        <x-icon class="fa-sort-up" />
                                                    </span>
                                                @break

                                                @case('desc')
                                                    <span wire:loading.remove
                                                          wire:target="sortColumn('{{ $key }}')">
                                                        <x-icon class="fa-sort-down" />
                                                    </span>
                                                @break

                                                @default
                                                    <span wire:loading.remove
                                                          wire:target="sortColumn('{{ $key }}')">
                                                        <x-icon class="fa-sort" />
                                                    </span>
                                                @break
                                            @endswitch

                                            <span wire:loading
                                                  wire:target="sortColumn('{{ $key }}')">
                                                <x-icon class="fa-circle-notch fa-spin" />
                                            </span>
                                        </x-button>

                                        {{ $column->get('label', $key) }}
                                    </x-table.heading>
                                @endif
                            @endforeach
                        </x-table.row>

                        <x-table.row>
                            @foreach ($schema as $key => $column)
                                @if (Arr::get($columnStatus, $key, true) === true)
                                    <x-table.heading style="min-width: 250px;">
                                        <div class="row g-0">
                                            <div class="col">
                                                <x-form.text :disabled="$column->filterable === false"
                                                             :placeholder="trans('datatables.type_to_search')"
                                                             class="form-control-sm"
                                                             wire:model="columnFilters.{{ $key }}" />
                                            </div>

                                            <div class="col-auto">
                                                <x-button :title="trans('datatables.clear')"
                                                          :disabled="Arr::get($columnFilters, $key) === null"
                                                          class="btn-sm btn-light ms-1"
                                                          wire:click="$set('columnFilters.{{ $key }}', null)">

                                                    <span wire:loading.remove
                                                          wire:target="$set('columnFilters.{{ $key }}', null)">
                                                        <x-icon class="fa-eraser" />
                                                    </span>

                                                    <span wire:loading
                                                          wire:target="$set('columnFilters.{{ $key }}', null)">
                                                        <x-icon class="fa-circle-notch fa-spin" />
                                                    </span>
                                                </x-button>
                                            </div>
                                        </div>
                                    </x-table.heading>
                                @endif
                            @endforeach
                        </x-table.row>

                    </x-table.head>
                    <x-table.body>

                        @foreach ($paginator as $item)
                            <x-table.row>
                                <x-table.data class="text-nowrap text-center align-middle">
                                    <x-button :title="trans('datatables.show')"
                                              class="btn-sm btn-light">
                                        <x-icon class="fa-eye" />
                                    </x-button>

                                    <x-button :title="trans('datatables.edit')"
                                              class="btn-sm btn-light">
                                        <x-icon class="fa-edit" />
                                    </x-button>

                                    <x-button :title="trans('datatables.delete')"
                                              class="btn-sm btn-light">
                                        <x-icon class="fa-trash" />
                                    </x-button>
                                </x-table.data>

                                @foreach ($schema as $key => $column)
                                    @if (Arr::get($columnStatus, $key, true) === true)
                                        <x-table.data class="text-nowrap align-middle">
                                            {{ ($column->render)($item) }}
                                        </x-table.data>
                                    @endif
                                @endforeach

                            </x-table.row>
                        @endforeach

                        @for ($row = $paginator->count(); $row < $paginator->perPage(); $row++)
                            <x-table.row>
                                <x-table.data class="text-nowrap text-center align-middle">
                                    <x-button :disabled="true"
                                              class="btn-sm btn-light">
                                        <x-icon class="fa-eye" />
                                    </x-button>

                                    <x-button :disabled="true"
                                              class="btn-sm btn-light">
                                        <x-icon class="fa-edit" />
                                    </x-button>

                                    <x-button :disabled="true"
                                              class="btn-sm btn-light">
                                        <x-icon class="fa-trash" />
                                    </x-button>
                                </x-table.data>

                                <x-table.data :colspan="Collection::make($columnStatus)
                                    ->filter(fn(bool $status) => $status === true)
                                    ->count()" />
                            </x-table.row>
                        @endfor

                    </x-table.body>
                </x-table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6 mb-2 mb-md-0 py-2 text-center text-md-start">
            <small class="text-muted">
                <x-icon class="fa-database" />

                {{ trans_choice('datatables.from_to_total', $this->paginator->total(), [
                    'from' => $this->paginator->firstItem(),
                    'to' => $this->paginator->lastItem(),
                    'total' => $this->paginator->total(),
                ]) }}
            </small>
        </div>

        <div class="col-12 col-md-6">
            <x-pagination class="justify-content-center justify-content-md-end mb-0">
                <x-pagination.item :disabled="$paginator->onFirstPage() === true">
                    <x-pagination.button :title="trans('datatables.previous')"
                                         :disabled="$paginator->onFirstPage() === true"
                                         wire:click="previousPage">
                        <span wire:loading.remove
                              wire:target="previousPage">
                            <x-icon class="fa-angle-left" />
                        </span>

                        <span wire:loading
                              wire:target="previousPage">
                            <x-icon class="fa-circle-notch fa-spin" />
                        </span>
                    </x-pagination.button>
                </x-pagination.item>

                <x-pagination.item :disabled="$paginator->onFirstPage() === true">
                    <x-pagination.button :title="trans('datatables.first')"
                                         :disabled="$paginator->onFirstPage() === true"
                                         wire:click="gotoPage(1)">
                        <span wire:loading.remove
                              wire:target="gotoPage(1)">
                            <x-icon class="fa-angle-double-left" />
                        </span>

                        <span wire:loading
                              wire:target="gotoPage(1)">
                            <x-icon class="fa-circle-notch fa-spin" />
                        </span>
                    </x-pagination.button>
                </x-pagination.item>

                <x-pagination.item :disabled="$paginator->hasMorePages() === false">
                    <x-pagination.button :title="trans('datatables.last')"
                                         :disabled="$paginator->hasMorePages() === false"
                                         wire:click="gotoPage({{ $paginator->lastPage() }})">
                        <span wire:loading.remove
                              wire:target="gotoPage({{ $paginator->lastPage() }})">
                            <x-icon class="fa-angle-double-right" />
                        </span>

                        <span wire:loading
                              wire:target="gotoPage({{ $paginator->lastPage() }})">
                            <x-icon class="fa-circle-notch fa-spin" />
                        </span>
                    </x-pagination.button>
                </x-pagination.item>

                <x-pagination.item :disabled="$paginator->hasMorePages() === false">
                    <x-pagination.button :title="trans('datatables.next')"
                                         :disabled="$paginator->hasMorePages() === false"
                                         wire:click="nextPage">
                        <span wire:loading.remove
                              wire:target="nextPage">
                            <x-icon class="fa-angle-right" />
                        </span>

                        <span wire:loading
                              wire:target="nextPage">
                            <x-icon class="fa-circle-notch fa-spin" />
                        </span>
                    </x-pagination.button>
                </x-pagination.item>
            </x-pagination>
        </div>
    </div>

    <x-modal id="data-table-settings"
             wire:ignore.self="true"
             aria-describedby="data-table-settings-title">
        <x-modal.dialog>
            <x-modal.content>

                <x-modal.header>
                    <x-modal.title id="data-table-settings-title"
                                   class="fs-6">
                        <x-icon class="fa-cog" />
                        {{ trans('datatables.settings') }}
                    </x-modal.title>
                    <x-modal.close />
                </x-modal.header>

                <x-modal.body>
                    <x-form wire:submit.prevent="$refresh">

                        <div class="mb-2">
                            <x-form.label>
                                <x-icon class="fa-eye" />
                                {{ trans('datatables.columns') }}
                            </x-form.label>

                            @foreach ($schema as $key => $column)
                                <x-form.switch>
                                    <x-form.switch.input id="data-table-toggle-{{ Str::of($key)->studly()->kebab() }}"
                                                         wire:model="columnStatus.{{ $key }}" />

                                    <x-form.switch.label for="data-table-toggle-{{ Str::of($key)->studly()->kebab() }}">
                                        {{ $column->label }}
                                    </x-form.switch.label>
                                </x-form.switch>
                            @endforeach
                        </div>

                        <div class="mb-2">
                            <x-form.label for="data-table-length">
                                <x-icon class="fa-ruler-vertical" />
                                {{ trans('datatables.length') }}
                            </x-form.label>

                            <x-form.range id="data-table-length"
                                          min="10"
                                          max="50"
                                          step="10"
                                          wire:model="pageLength" />
                        </div>

                    </x-form>
                </x-modal.body>

            </x-modal.content>
        </x-modal.dialog>
    </x-modal>
</div>
