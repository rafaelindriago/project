<?php

declare(strict_types=1);

namespace App\Http\Livewire\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

trait WithFiltering
{
    public array $columnFilters = [];

    protected function getFilterableColumns(): array
    {
        return method_exists($this, 'filterableColumns')
            ? $this->filterableColumns()
            : (property_exists($this, 'filterableColumns')
                ? $this->getFilterableColumns
                : []);
    }

    public function updatedWithFiltering(string $property, string|null $value): void
    {
        if (Str::of($property)->startsWith('columnFilters.')) {
            if (Str::of($value)->trim()->isEmpty()) {
                Arr::forget(
                    $this->columnFilters,
                    Str::of($property)
                        ->after('.')
                        ->toString()
                );
            }
        }
    }

    protected function resetFilters(): void
    {
        $this->columnFilters = [];
    }
}
