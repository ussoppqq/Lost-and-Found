<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Profile extends Component
{
    use WithFileUploads;

    public $full_name, $nickname, $phone_number, $company_name, $email;
    public $avatar, $newAvatar;
    public $editMode = false;

    public function mount()
    {
        $user = Auth::user();

        $this->full_name    = $user->full_name;
        $this->nickname     = $user->nickname;
        $this->phone_number = $user->phone_number;
        $this->company_name = $user->company->company_name ?? '';
        $this->email        = $user->email;
        $this->avatar       = $user->avatar; // path avatar
    }

    public function update()
    {
        $this->validate([
            'full_name'    => 'required|string|max:255',
            'nickname'     => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'newAvatar'    => 'nullable|image|max:2048', // 2MB max
        ]);

        $user = Auth::user();
        $user->full_name    = $this->full_name;
        $user->phone_number = $this->phone_number;
        $user->email        = $this->email;

        if ($user->company) {
            $user->company->update(['company_name' => $this->company_name]);
        }

        // simpan avatar baru kalau ada
        if ($this->newAvatar) {
            $path = $this->newAvatar->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        $this->avatar = $user->avatar; // refresh
        $this->editMode = false;

        session()->flash('success', 'Profile updated successfully!');
    }
public function cancelEdit()
{
    $this->editMode = false;
    $this->newAvatar = null;
    $this->mount(); // reload data
}
    public function render()
    {
        return view('livewire.profile')
            ->layout('components.layouts.auth');
    }
}
