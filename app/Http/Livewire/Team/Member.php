<?php

namespace App\Http\Livewire\Team;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Member extends Component
{
    public User $member;

    public function makeAdmin()
    {
        $this->member->teams()->updateExistingPivot(currentTeam()->id, ['role' => 'admin']);
        $this->emit('reloadWindow');
    }

    public function makeReadonly()
    {
        $this->member->teams()->updateExistingPivot(currentTeam()->id, ['role' => 'member']);
        $this->emit('reloadWindow');
    }

    public function remove()
    {
        $this->member->teams()->detach(currentTeam());
        Cache::forget("team:{$this->member->id}");
        Cache::remember('team:' . $this->member->id, 3600, function() {
            return $this->member->teams()->first();
        });
        $this->emit('reloadWindow');
    }
}
