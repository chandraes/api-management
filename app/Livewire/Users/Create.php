<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class Create extends Component
{
    #[Validate('required|string')]
    public string $name = '';

    #[Validate('required|string|email|unique:users,email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('required|string|in:admin,user')]
    public string $role;

    public function storePrompt()
    {
        LivewireAlert::title('Are you sure?')
                ->text('You are about to create a new user')
                ->confirmButtonText('Yes, create user')
                ->withConfirmButton()
                ->asConfirm()
                ->onConfirm('store')
                ->show();
    }

    public function store()
    {
        $this->validate();

        // Store the user
        User::create([
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'name' => $this->name,
            'role' => $this->role,
        ]);

        // Show a success alert
        session()->flash('saved', [
            'title' => 'Success!',
            'text' => 'Berhasil menyimpan data!',
        ]);

        $this->redirect('/users', navigate: true);

        // Redirect to the users index page
    }

    public function render()
    {
        $roles = ['admin', 'user'];
        return view('livewire.users.create', compact('roles'));
    }
}
