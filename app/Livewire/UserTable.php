<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;

    public $filterId = '';
    public $filterName = '';
    public $filterEmail = '';
    public $filterType = '';
    public $perPage = 10;

    protected $queryString = [
        'filterId' => ['except' => ''],
        'filterName' => ['except' => ''],
        'filterEmail' => ['except' => ''],
        'filterType' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updating($property, $value)
    {
        if (in_array($property, ['filterId', 'filterName', 'filterEmail', 'filterType', 'perPage'])) {
            $this->resetPage();
        }
    }

public function clearFilters()
{
    $this->filterId = '';
    $this->filterName = '';
    $this->filterEmail = '';
    $this->filterType = '';
    $this->resetPage();
}


    public function render()
    {
        $users = User::query()
            ->when($this->filterId, fn($q) => $q->where('users.id', $this->filterId))
            ->when($this->filterName, fn($q) => $q->where('users.name', 'like', '%' . $this->filterName . '%'))
            ->when($this->filterEmail, fn($q) => $q->where('users.email', 'like', '%' . $this->filterEmail . '%'))
            ->when($this->filterType, fn($q) => $q->where('users.type', $this->filterType))
            ->paginate($this->perPage);

        return view('livewire.user-table', compact('users'));
    }
}

