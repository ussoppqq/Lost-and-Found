<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Role;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = 'all';
    public $verifiedFilter = 'all';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Modal states
    public $showDetailModal = false;
    public $showDeleteModal = false;

    // Selected user
    public $selectedUser;
    public $userId;

    protected $queryString = ['search', 'roleFilter', 'verifiedFilter'];

    protected $listeners = [
        'userCreated' => 'handleUserCreated',
        'userUpdated' => 'handleUserUpdated',
        'userVerified' => 'handleUserVerified',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedRoleFilter()
    {
        $this->resetPage();
    }

    public function updatedVerifiedFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortBy = $field;
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'roleFilter', 'verifiedFilter']);
        $this->resetPage();
    }

    public function openDetailModal($userId)
    {
        $this->selectedUser = User::with(['role', 'company'])->findOrFail($userId);
        $this->showDetailModal = true;
    }

    public function openDeleteModal($userId)
    {
        $this->userId = $userId;
        $this->selectedUser = User::findOrFail($userId);
        $this->showDeleteModal = true;
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->showDeleteModal = false;
        $this->reset(['selectedUser', 'userId']);
    }

    public function delete()
    {
        if ($this->userId) {
            User::findOrFail($this->userId)->delete();

            session()->flash('success', 'User deleted successfully!');
            $this->closeModal();
            $this->resetPage();
        }
    }

    public function toggleVerification($userId)
    {
        $user = User::findOrFail($userId);

        $user->update([
            'is_verified' => !$user->is_verified,
            'phone_verified_at' => !$user->is_verified ? now() : null,
        ]);

        $status = $user->is_verified ? 'verified' : 'unverified';
        session()->flash('success', "User status changed to {$status}!");
    }

    public function handleUserCreated()
    {
        $this->resetPage();
        session()->flash('success', 'User created successfully!');
    }

    public function handleUserUpdated()
    {
        $this->resetPage();
        session()->flash('success', 'User updated successfully!');
    }

    public function handleUserVerified()
    {
        $this->resetPage();
        session()->flash('success', 'User verified successfully!');
    }

    public function render()
    {
        $companyId = auth()->user()->company_id;

        $users = User::with('role', 'company')
            ->where('company_id', $companyId)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('full_name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone_number', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->roleFilter !== 'all', function ($query) {
                $query->whereHas('role', function ($q) {
                    $q->where('role_code', $this->roleFilter);
                });
            })
            ->when($this->verifiedFilter !== 'all', function ($query) {
                $query->where('is_verified', $this->verifiedFilter === 'verified');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        $roles = Role::all();

        return view('livewire.admin.users.index', compact('users', 'roles'))
            ->layout('components.layouts.admin', [
                'title' => 'Users Management',
                'pageTitle' => 'Users',
                'pageDescription' => 'Manage system users and their roles'
            ]);
    }
}