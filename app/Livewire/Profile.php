<?php

namespace App\Livewire;

use App\Models\Report;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class Profile extends Component
{
    use WithFileUploads;

    public $full_name, $nickname, $phone_number, $email;
    public $avatar, $newAvatar;
    public $editMode = false;
    public $currentTab = 'profile'; // profile or reports

    // Password change properties
    public $current_password, $new_password, $new_password_confirmation;

    public function mount()
    {
        $user = Auth::user();

        $this->full_name    = $user->full_name;
        $this->nickname     = $user->nickname;
        $this->phone_number = $user->phone_number;
        $this->email        = $user->email;
        $this->avatar       = $user->avatar; // path avatar
    }

    public function switchTab($tab)
    {
        $this->currentTab = $tab;
    }

    public function saveAvatar()
    {
        $this->validate([
            'newAvatar' => 'required|image|max:2048', // 2MB max
        ]);

        $user = Auth::user();

        // Hapus avatar lama jika ada
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Simpan avatar baru
        $path = $this->newAvatar->store('avatars', 'public');
        $user->avatar = $path;
        $user->save();

        $this->avatar = $user->avatar;
        $this->newAvatar = null;

        session()->flash('success', 'Profile photo updated successfully!');
    }

    public function update()
    {
        $this->validate([
            'full_name'    => 'required|string|max:255',
            'nickname'     => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email'        => 'required|email|max:255|unique:users,email,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->full_name    = $this->full_name;
        $user->phone_number = $this->phone_number;
        $user->email        = $this->email;

        $user->save();

        $this->editMode = false;

        session()->flash('success', 'Profile updated successfully!');
    }
    public function cancelEdit()
    {
        $this->editMode = false;
        $this->newAvatar = null;
        $this->mount(); // reload data
    }

    public function changePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Current password is incorrect');
            return;
        }

        $user->password = Hash::make($this->new_password);
        $user->save();

        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';

        session()->flash('success', 'Password changed successfully!');
    }

    public function render()
    {
        $reports = Report::with(['item.photos', 'item.category', 'category'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        // Load company relationship for admin/moderator
        $user = Auth::user();
        $user->load('company');

        return view('livewire.profile', [
            'reports' => $reports,
        ])->layout('components.layouts.profiledashboard');
    }
}
