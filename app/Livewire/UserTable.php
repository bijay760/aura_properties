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

    protected $queryString = [
        'filterId' => ['except' => ''],
        'filterName' => ['except' => ''],
        'filterEmail' => ['except' => ''],
        'filterType' => ['except' => ''],
    ];

    // Use a single method to handle all filter updates
    public function updatedFilterId($value)
    {
        $this->resetPage();
    }

    public function updatedFilterName($value)
    {
        $this->resetPage();
    }

    public function updatedFilterEmail($value)
    {
        $this->resetPage();
    }

    public function updatedFilterType($value)
    {
        $this->resetPage();
    }

    // Alternative: Use a single method for all filters
    public function updating($property, $value)
    {
        if (in_array($property, ['filterId', 'filterName', 'filterEmail', 'filterType'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $users = User::query()
            ->when($this->filterId, function($q) {
                return $q->where('users.id', $this->filterId);
            })
            ->when($this->filterName, function($q) {
                return $q->where('users.name', 'like', '%' . $this->filterName . '%');
            })
            ->when($this->filterEmail, function($q) {
                return $q->where('users.email', 'like', '%' . $this->filterEmail . '%');
            })
            ->when($this->filterType, function($q) {
                return $q->where('users.type', $this->filterType);
            })
            ->paginate(10);

        return view('livewire.user-table', compact('users'));
    }

    // Optional: Add method to clear all filters
    public function clearFilters()
    {
        $this->filterId = '';
        $this->filterName = '';
        $this->filterEmail = '';
        $this->filterType = '';
        $this->resetPage();
    }
}