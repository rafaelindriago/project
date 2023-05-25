<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Traits\WithDataTable;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Livewire\Component;

class UserDataTable extends Component
{
    use WithDataTable;

    protected function schema(): array
    {
        return [
            'name' => [
                'label'         => trans('Name'),
                'sortable'      => true,
                'filterable'    => true,
                'render'        => function (User $user): string {
                    return Blade::render(<<<'blade'
                        {{ $user->name }}
                    blade, ['user' => $user]);
                },
            ],
            'email' => [
                'label'         => trans('Email Address'),
                'sortable'      => true,
                'filterable'    => true,
                'render'        => function (User $user): string {
                    return Blade::render(<<<'blade'
                        {{ $user->email }}
                    blade, ['user' => $user]);
                },
            ],
        ];
    }

    protected function query(): Builder
    {
        return User::query()
            ->withoutGlobalScopes();
    }

    protected function sortableColumns(): array
    {
        return [
            'name'  => ['users.name'],
            'email' => ['users.email'],
        ];
    }

    protected function filterableColumns(): array
    {
        return [
            'name'  => function (Builder $query, $filter): void {
                $query->where('users.name', 'like', "%{$filter}%");
            },
            'email' => function (Builder $query, $filter): void {
                $query->where('users.email', 'like', "%{$filter}%");
            },
        ];
    }

    public function render()
    {
        return view('livewire.user-data-table');
    }
}
