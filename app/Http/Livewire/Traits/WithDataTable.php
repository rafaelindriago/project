<?php

declare(strict_types=1);

namespace App\Http\Livewire\Traits;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\WithPagination;

trait WithDataTable
{
    use WithSorting, WithFiltering, WithPagination;

    protected Collection $schema;
    protected QueryBuilder|EloquentBuilder $query;
    protected LengthAwarePaginator $paginator;

    public array $columnStatus = [];
    public int $pageLength = 10;

    public function bootWithDataTable(): void
    {
        $this->schema = Collection::make($this->schema())
            ->mapInto(Fluent::class);

        $this->query = $this->query();
    }

    public function mountWithDataTable(): void
    {
        foreach ($this->schema->keys() as $key) {
            Arr::set($this->columnStatus, $key, true);
        }
    }

    public function updatedWithDataTable(string $property, mixed $value): void
    {
        if (Str::of($property)->startsWith('columnFilters.')) {
            $this->resetPage();
        }

        if (Str::of($property)->startsWith('columnStatus.')) {
            if (Collection::make([true, false])->doesntContain($value)) {
                Arr::set(
                    $this->columnStatus,
                    Str::of($property)
                        ->after('.')
                        ->toString(),
                    true
                );
            }
        }

        if (Str::of($property)->exactly('pageLength')) {
            if (Collection::make([10, 20, 30, 40, 50])->doesntContain($value)) {
                $this->pageLength = 10;
            }
        }
    }

    public function renderedWithDataTable(View $view): void
    {
        $sortableColumns = $this->getSortableColumns();

        foreach ($this->columnSorts as $key => $direction) {
            if (Arr::has($sortableColumns, $key)) {
                foreach ($sortableColumns[$key] as $column) {
                    $this->query->orderBy($column, $direction);
                }
            }
        }

        $filterableColumns = $this->getFilterableColumns();

        foreach ($this->columnFilters as $key => $filter) {
            if (Arr::has($filterableColumns, $key)) {
                $filterableColumns[$key]($this->query, $filter);
            }
        }

        $this->paginator = $this->query->paginate($this->pageLength);

        $view->with([
            'schema'    => $this->schema,
            'paginator' => $this->paginator,
        ]);
    }

    public function restore(): void
    {
        $this->resetSorts();
        $this->resetFilters();
        $this->resetPage();
    }
}
