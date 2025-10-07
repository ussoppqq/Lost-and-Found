<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class Edit extends Component
{
    public $showModal = false;
    
    // Form fields
    public $userId;
    public $full_name;
    public $email;
    public $phone_number;
    public $password;
    public $password_confirmation;
    public $role_id;
    
    // Track original phone
    public $originalPhone;

    protected $listeners = ['openEditModal'];

    protected function rules()
    {
        return [
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $this->userId . ',user_id',
            'phone_number' => 'required|string|regex:/^62[0-9]{9,13}$/|unique:users,phone_number,' . $this->userId . ',user_id',
            'role_id' => 'required|exists:roles,role_id',
            'password' => 'nullable|min:8|confirmed',
        ];
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
        $this->originalPhone = $user->phone_number;
        
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
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
            'originalPhone',
        ]);
        $this->resetErrorBag();
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
        ];

        // Jika phone number berubah, set is_verified ke false
        $phoneChanged = false;
        if ($this->originalPhone !== $this->phone_number) {
            $data['is_verified'] = false;
            $data['phone_verified_at'] = null;
            $phoneChanged = true;
        }

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        $this->dispatch('userUpdated');
        $this->closeModal();

        // Jika phone berubah, buka modal verifikasi
        if ($phoneChanged) {
            $this->dispatch('openVerifyModal', userId: $user->user_id);
            session()->flash('info', 'Phone number changed. Please verify the new number.');
        }
    }

    public function render()
    {
        $roles = Role::all();
        return view('livewire.admin.users.edit', compact('roles'));
    }
}