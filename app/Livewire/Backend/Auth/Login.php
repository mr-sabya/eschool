<?php

namespace App\Livewire\Backend\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $username = '';
    public $password = '';
    public $remember = false; // Add this for the Remember Me checkbox

    protected $rules = [
        'username' => 'required|string',
        'password' => 'required|min:6',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['username' => $this->username, 'password' => $this->password, 'is_staff' => true], $this->remember)) {
            // Redirect on successful login
            return $this->redirect(route('admin.dashboard'), navigate: true);
        }

        // Display error on failure
        session()->flash('error', 'Invalid username or password.');
    }



    public function render()
    {
        return view('livewire.backend.auth.login');
    }
}
