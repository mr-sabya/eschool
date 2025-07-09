<?php

namespace App\Livewire\Backend\Auth;

use Livewire\Component;

class Logout extends Component
{
    /**
     * Log the user out.
     */

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function render()
    {
        return view('livewire.backend.auth.logout');
    }
}
