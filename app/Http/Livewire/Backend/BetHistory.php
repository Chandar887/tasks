<?php

namespace App\Http\Livewire\Backend;

use App\Models\Bet;
use Livewire\Component;

class BetHistory extends Component
{
    public $bet_limit=10;
    public function render()
    {
        return view('livewire.backend.bet-history',
        [
            'bets' => Bet::with('user','category')->latest()->paginate($this->bet_limit)
        ]);
    }
}
