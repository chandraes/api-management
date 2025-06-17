<?php

use App\Livewire\Users\Create;
use Livewire\Livewire;
use function Pest\Laravel\assertDatabaseHas;

it('can mount the component', function () {
    Livewire::test(Create::class)
        ->assertSet('name', '')
        ->assertSet('email', '')
        ->assertSet('password', '')
        ->assertSet('role', null);
});

it('can store a new user', function () {
    Livewire::test(Create::class)
        ->set('name', 'John Doe')
        ->set('email', 'john@example.com')
        ->set('password', 'secret')
        ->set('role', 'admin')
        ->call('store')
        ->assertHasNoErrors();

    assertDatabaseHas('users', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'role' => 'admin',
    ]);
});
