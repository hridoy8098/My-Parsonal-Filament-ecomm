<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Forgot Password')]

class ForgotPassword extends Component
{


   
   
public $email;

public function save() {
    $this->validate([
        'email' => 'required|email|exists:users,email|max:255', 
        
    ]);

    $status = Password::sendResetLink(['email' => $this->email]);

    if($status === password::RESET_LINK_SENT){

        session()->flash('success','Password reset link has been sent you email address!');
        $this->email = '';
    }
}



    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
