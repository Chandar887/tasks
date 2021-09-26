<?php

namespace App\Http\Livewire\Backend;

use Carbon\Carbon;
use App\Models\Bet;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class ReportUsers extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $bet_limit = 10;
    public $groupBy = "Day";
    public $search;

    public function updatingSearch()
    {
        $this->resetPage(); 
    }

    public function ChangeOrder($by = "Day")
    {
        $this->groupBy = $by;
        $this->resetPage();
    }
    public function render()
    {
        $groupBy = "DATE_FORMAT(created_at, '%Y-%m-%d')";
        if ($this->groupBy == 'Month') {
            $groupBy = "DATE_FORMAT(created_at, '%Y-%m')";
        } elseif ($this->groupBy == 'Week') {
            $groupBy = "CONCAT(YEAR(created_at), '/', WEEK(created_at))";
        } elseif ($this->groupBy == 'Year') {
            $groupBy = "Year(created_at)";
        }
        $query = Bet::query();

        if ($this->search) {
            $query->where(function (Builder $query) {
                $query->where('number', 'like', "%$this->search%")
                      ->orWhere('bet_amount', 'like', "%$this->search%")
                      ->orWhere('result', 'like', "%$this->search%")
                      ->orWhere('commission', 'like', "%$this->search%")
                      ->orWhere('win_amount', 'like', "%$this->search%");
            });
        }
        
        $query
            ->selectRaw("user_id,SUM(bet_amount) as bet_amount, SUM(win_amount) as win_amount, {$groupBy} as date")
            ->groupBy(DB::raw("{$groupBy}"), "user_id");

        if (auth()->user()->role == 'sadmin') 
            $data = $query->with('user')->paginate($this->bet_limit);
        else 
            $data = $query->userId(auth()->user()->id)->paginate($this->bet_limit);   

        return view(
            'livewire.backend.report-users',
            [
                'bets' => $data
            ]
        );
    }
}
