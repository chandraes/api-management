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
    public $search = '';

    public function mount()
    {
         $this->loadEndpoints();
    }

    public function updatedSearch()
    {
        $this->loadEndpoints();
    }

    public function updatedSelectedUserId($userId)
    {
        $user = User::with('apiEndpoints')->find($userId);
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

    protected function loadEndpoints()
    {
        $this->availableEndpoints = ApiEndpoint::when($this->search, function ($query) {
            $query->where('uri', 'like', "%{$this->search}%")
                  ->orWhere('method', 'like', "%{$this->search}%");
        })->orderBy('uri')->get();
    }

    public function render()
    {
        return view('livewire.admin.user-endpoint-access', [
            'users' => User::orderBy('name')->get()
        ]);
    }
}
