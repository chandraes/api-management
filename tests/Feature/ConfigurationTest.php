<?php

use App\Livewire\Configuration;
use Livewire\Livewire;
use function Pest\Laravel\assertDatabaseHas;

it('can mount the component', function () {
    Livewire::test(Configuration::class)
        ->assertSet('url', '')
        ->assertSet('password', '')
        ->assertSet('username', '');
});

it('can store configuration', function () {
    Livewire::test(Configuration::class)
        ->set('url', 'https://example.com')
        ->set('password', 'secret')
        ->set('username', 'user')
        ->call('store')
        ->assertHasNoErrors();

    assertDatabaseHas('configurations', [
        'url' => 'https://example.com',
        'password' => 'secret',
        'username' => 'user',
    ]);
});
