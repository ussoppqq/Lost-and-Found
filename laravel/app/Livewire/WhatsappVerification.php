<?php

namespace App\Livewire;

use Livewire\Component;

class WhatsappVerification extends \Livewire\Component
{
    public $country_code = '+62';
    public $phone;
    public $captcha;
    public $generatedCode;

    public function mount()
    {
        $this->generatedCode = $this->randomCode();
    }

    public function refreshCaptcha()
    {
        $this->generatedCode = $this->randomCode();
    }

    public function submit()
    {
        $this->validate([
            'phone' => 'required',
            'captcha' => 'required|in:' . $this->generatedCode,
        ]);

        session()->flash('success', 'Demo: OTP sent to ' . $this->country_code . ' ' . $this->phone);
        $this->reset('phone','captcha');
        $this->refreshCaptcha();
    }

    public function render()
    {
        return view('livewire.whatsapp-verification')
            ->layout('components.layouts.app'); 
    }

    private function randomCode()
    {
        return strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 5));
    }
}
