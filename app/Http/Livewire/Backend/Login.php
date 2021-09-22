<?php

namespace App\Http\Livewire\Backend;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;
    public $remember;

    public function UserLogin()
    {
        $request = request();
        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $validation = [
            'email' => 'required|min:4|max:255|string|alpha_dash',
            'password' => 'required|string|min:4',
        ];
        if ($fieldType == 'email') {
            $data['email'] = 'required|string|email|max:255';
        }
        $data = $this->validate($validation);

        if (Auth::attempt([$fieldType => $this->email, 'password' => $this->password], $this->remember)) {
            session()->flash('success', "You are Login successfully");
            redirect()->to(route('dashboard'));
        } else {
            session()->flash('error', 'These credentials do not match our records!');
        }
    }

    public function mount()
    {
        if (auth()->check())
            redirect()->to(route('dashboard'));
    }
    public function render()
    {
        return view('livewire.backend.login');
        // ->layout('layouts.backend');
    }
}
