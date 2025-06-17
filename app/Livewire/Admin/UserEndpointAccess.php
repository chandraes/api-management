<?php

namespace App\Livewire\Admin;

use App\Models\ApiEndpoint;
use App\Models\User;
use Livewire\Component;

class UserEndpointAccess extends Component
{
    public $selectedUserId;
    public $availableEndpoints = [];
    public $selectedEndpointIds = [];

    public function mount()
    {
         $this->availableEndpoints = ApiEndpoint::orderBy('uri')->get();
    }

   public function updatedSelectedUserId($userId)
    {
        $user = User::find($userId);
        $this->selectedEndpointIds = $user?->apiEndpoints->pluck('id')->toArray() ?? [];
    }

    public function save()
    {
        $user = User::find($this->selectedUserId);

        if (!$user) {
            session()->flash('error', 'User tidak ditemukan');
            return;
        }

        $user->apiEndpoints()->sync($this->selectedEndpointIds);

        session()->flash('success', 'Hak akses endpoint berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.user-endpoint-access', [
            'users' => User::orderBy('name')->get()
        ]);
    }
}
