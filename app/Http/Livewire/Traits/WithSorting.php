<?php

declare(strict_types=1);

namespace App\Http\Livewire\Traits;

use Illuminate\Support\Arr;

trait WithSorting
{
    public array $columnSorts = [];

    protected function getSortableColumns(): array
    {
        return method_exists($this, 'sortableColumns')
            ? $this->sortableColumns()
            : (property_exists($this, 'sortableColumns')
                ? $this->sortableColumns
                : []);
    }

    public function sortColumn(string $key)
    {
        $sortableColumns = $this->getSortableColumns();

        if (Arr::has($sortableColumns, $key)) {
            switch (Arr::get($this->columnSorts, $key)) {
                case 'asc':
                    Arr::set($this->columnSorts, $key, 'desc');
                    break;
                case 'desc':
                    Arr::forget($this->columnSorts, $key);
                    break;
                default:
                    Arr::set($this->columnSorts, $key, 'asc');
                    break;
            }
        }
    }

    protected function resetSorts(): void
    {
        $this->columnSorts = [];
    }
}
