<?php

namespace App\Http\Livewire\Backend;

use App\Models\Bet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ReportUsers extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $bet_limit = 10;
    public $groupBy = "Day";

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
        $query
            ->selectRaw("user_id,SUM(bet_amount) as bet_amount, SUM(win_amount) as win_amount, {$groupBy} as date")
            ->groupBy(DB::raw("{$groupBy}"), "user_id");
        return view(
            'livewire.backend.report-users',
            [
                'bets' => $query->with('user')->paginate($this->bet_limit)
            ]
        );
    }
}
