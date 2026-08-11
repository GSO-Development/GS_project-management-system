<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ForcePasswordChange extends Component
{
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    public function mount()
    {
        return redirect()->route('dashboard');
    }

    protected function rules(): array
    {
        return [
            'new_password' => 'required|string|min:8|same:new_password_confirmation',
            'new_password_confirmation' => 'required',
        ];
    }

    protected array $messages = [
        'new_password.required' => 'Please enter a new password.',
        'new_password.min' => 'The new password must be at least 8 characters long.',
        'new_password.same' => 'The new password and confirmation password do not match.',
        'new_password_confirmation.required' => 'Please confirm your new password.',
    ];

    public function changePassword()
    {
        $this->validate();

        $user = auth()->user();

        if ($user) {
            $user->password = Hash::make($this->new_password);
            $user->must_change_password = false;
            $user->save();
        }

        session()->flash('message', 'Password updated successfully!');

        return redirect()->to(route('dashboard'));
    }

    public function render()
    {
        return view('livewire.force-password-change')
            ->layout('layouts.guest');
    }
}
