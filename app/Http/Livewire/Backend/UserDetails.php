<?php

namespace App\Http\Livewire\Backend;

use Carbon\Carbon;
use App\Models\Bet;
use App\Models\User;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class UserDetails extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $detail_limit = 10;
    public $user;
    public $search;
    public $category;
    public $categories;
    public $date_from;
    public $date_to;

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function mount($user_id)
    {
        $this->user = User::find($user_id);
    }

    public function render()
    {
        $items = [];
        $query = Bet::query();
        if ($this->date_from && $this->date_to) {
            $query->where(function (Builder $query) {
                $query->whereDate('created_at', '>=', $this->date_from)
                      ->whereDate('created_at', '<=', $this->date_to);
            }); 

            $items = $query->groupBy('category_id')
                           ->userId($this->user->id)
                           ->selectRaw('SUM(bet_amount) as bet_amount, SUM(win_amount) as win_amount, category_id')->paginate($this->detail_limit);
        } else {
            $items = Bet::groupBy('category_id')
                       ->userId($this->user->id)
                       ->selectRaw('SUM(bet_amount) as bet_amount, SUM(win_amount) as win_amount, category_id')->paginate($this->detail_limit);
        }

        return view('livewire.backend.user-details')->with(['items' => $items]);
    }
}
