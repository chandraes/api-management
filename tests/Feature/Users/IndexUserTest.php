<?php

use App\Livewire\Users\Index;
use App\Models\User;
use Livewire\Livewire;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

it('can mount the component', function () {
    Livewire::test(Index::class)
        ->assertSet('perPage', 10)
        ->assertSet('search', '')
        ->assertSet('sortDirection', 'ASC')
        ->assertSet('sortColumn', 'name');
});

it('can sort users', function () {
    Livewire::test(Index::class)
        ->call('doSort', 'email')
        ->assertSet('sortColumn', 'email')
        ->assertSet('sortDirection', 'ASC')
        ->call('doSort', 'email')
        ->assertSet('sortDirection', 'DESC');
});

it('can delete a user', function () {
    $user = User::factory()->create();

    Livewire::test(Index::class)
        ->call('delete', ['id' => $user->id])
        ->assertHasNoErrors();

    assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});

it('shows an error when trying to delete the last admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::test(Index::class)
        ->call('delete', ['id' => $admin->id])
        ->assertHasErrors(['delete']);

    assertDatabaseHas('users', [
        'id' => $admin->id,
    ]);
});
