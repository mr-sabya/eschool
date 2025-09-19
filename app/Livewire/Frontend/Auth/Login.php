<?php

namespace App\Livewire\Frontend\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Login extends Component
{
    public $username;
    public $password;
    public $remember = false;

    protected $rules = [
        'username' => 'required|string',
        'password' => 'required|string|min:6',
    ];

    public function login()
    {
        $this->validate();

        $user = User::where('username', $this->username)->first();

        if (!$user) {
            $this->addError('username', 'User not found.');
            return;
        }

        // Check if student
        if (!$user->isStudent()) {
            $this->addError('username', 'Only students can login here.');
            return;
        }

        // Check active status
        if (!$user->isActive()) {
            $this->addError('username', 'Your account is inactive.');
            return;
        }

        // Verify password
        if (!Hash::check($this->password, $user->password)) {
            $this->addError('password', 'Invalid password.');
            return;
        }

        // Login user
        Auth::login($user, $this->remember);

        // Redirect to student dashboard
        return $this->redirect(route('student.profile.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.frontend.auth.login');
    }
}
