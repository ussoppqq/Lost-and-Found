<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = 'all';
    public $verifiedFilter = 'all';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $showDetailModal = false;
    
    // Form fields
    public $userId;
    public $full_name;
    public $email;
    public $phone_number;
    public $password;
    public $password_confirmation;
    public $role_id;
    public $is_verified = false;
    
    // Selected user untuk detail/delete
    public $selectedUser;

    protected $queryString = ['search', 'roleFilter', 'verifiedFilter'];

    protected function rules()
    {
        $rules = [
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . ($this->userId ?? 'NULL') . ',user_id',
            'phone_number' => 'required|string|unique:users,phone_number,' . ($this->userId ?? 'NULL') . ',user_id',
            'role_id' => 'required|exists:roles,role_id',
            'is_verified' => 'boolean',
        ];

        if ($this->showCreateModal) {
            $rules['password'] = 'required|min:8|confirmed';
        } elseif ($this->password) {
            $rules['password'] = 'min:8|confirmed';
        }

        return $rules;
    }

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

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function openEditModal($userId)
    {
        $this->resetForm();
        $user = User::findOrFail($userId);
        
        $this->userId = $user->user_id;
        $this->full_name = $user->full_name;
        $this->email = $user->email;
        $this->phone_number = $user->phone_number;
        $this->role_id = $user->role_id;
        $this->is_verified = $user->is_verified;
        
        $this->showEditModal = true;
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
        $this->showCreateModal = false;
        $this->showEditModal = false;
        $this->showDeleteModal = false;
        $this->showDetailModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'userId',
            'full_name',
            'email',
            'phone_number',
            'password',
            'password_confirmation',
            'role_id',
            'is_verified',
            'selectedUser',
        ]);
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        $companyId = auth()->user()->company_id;

        User::create([
            'user_id' => Str::uuid(),
            'company_id' => $companyId,
            'role_id' => $this->role_id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'password' => Hash::make($this->password),
            'is_verified' => $this->is_verified,
        ]);

        session()->flash('success', 'User created successfully!');
        $this->closeModal();
        $this->resetPage();
    }

    public function update()
    {
        $this->validate();

        $user = User::findOrFail($this->userId);
        
        $data = [
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'role_id' => $this->role_id,
            'is_verified' => $this->is_verified,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        session()->flash('success', 'User updated successfully!');
        $this->closeModal();
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