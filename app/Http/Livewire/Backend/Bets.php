<?php

namespace App\Http\Livewire\Backend;

use Carbon\Carbon;
use App\Models\Bet;
use Livewire\Component;
use App\Enums\BetStatus;
use App\Models\Category;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class Bets extends Component
{
    protected $listeners = [
        'bet-new' => 'newBet',
        'bet-save' => 'saveBet',
        'bet-delete' => 'betDelete',
        'bets-detail' => 'betsDetail',
        'declare-result' => 'declareResult',
    ];

    protected $rules = [
        'bet.user_id' => 'required|exists:users,id',
        'bet.category_id' => 'required|exists:categories,id',
        'bet.device_id' => 'nullable|min:2',
        'bet.number' => 'required|string',
        'bet.bet_amount' => 'required|numeric',
    ];

    public Bet $bet;
    public $category;
    public $categories;
    public $bets;
    public $betsData;
    public $start_time;
    public $end_time;
    public $current_time;
    public $start_timer;
    public $stop_timer;
    public $user;
    public $bet_status;

    public $result;
    public $result_value;

    public function newBet($number)
    {
        $this->bet = new Bet();
        $this->bet->number = $number;
    }

    public function saveBet($data, $category_id)
    {
        $user = auth()->user();
        $end_time = $this->dateFormatToHis($this->category->end_time);
        $current_time = $this->dateFormatToHis(Carbon::now());  
        
        if ($current_time < $end_time) {
            if (count($data) > 0) {
                foreach ($data as $key => $value) {
                    Bet::create([
                        'user_id'     => $user->id,
                        'category_id' => $category_id,
                        'device_id'   => 'unique_id',
                        'number'      => $key,
                        'bet_amount'  => $value,
                    ]);
                }

                session()->flash('success', 'New Bet added successfully.');
                $this->dispatchBrowserEvent('close-bet-form');
            } 
        } else {
            session()->flash('error', 'Bet time expired.');
            $this->dispatchBrowserEvent('close-bet-form');
        }
        
        // $this->rules['bet.bet_amount'] = 'required|numeric|min:' . ($this->category->min_bet) . '|max:' . ($this->category->max_bet);
        // $this->bet->category_id = $this->category->id;
        // $this->bet->user_id = $user->id;
        // $this->bet->device_id = "unique_id";
        // $this->validate($this->rules);
        // $this->bet->save();
        // session()->flash('success', 'New bet added in ' . $this->category->name . ' on number ' . $this->bet->number . ' with amount ' . $this->bet->bet_amount);
        // $this->bet = new Bet();
        // $this->dispatchBrowserEvent('close-bet-form');
    }

    public function betDelete(Bet $bet)
    {
        $bet->delete();
    }

    public function betsDetail($number)
    {
        $bet = Bet::whereDate('created_at', Carbon::today())->number($number)->latest();

        if ($this->user->role == 'sadmin' || $this->user->role == 'admin')
            $this->betsData = $bet->with('user')->get();
        else 
            $this->betsData = $bet->userId($this->user->id)->get();
    }

    public function declareResult()
    {
        $settings = config('settings');

        $data = $this->validate([
            'result_value' => ['required', 'numeric']
        ]);
        $data['result_time'] = date('Y-m-d');

        $update_query = Bet::where("category_id", $this->category->id)->whereDate('created_at', Carbon::today());
        $update_query->update([
            'result' => $data['result_value'],
            'result_time' => $data['result_time'],
            'commission' => DB::raw('ROUND(bet_amount*' . $settings['commission'] . '/100,2)'),
        ]);

        $update_query->where("number", $data['result_value'])->update([
            'win_amount' => DB::raw('ROUND(bet_amount*' . $settings['profit'] . ',2)'),
        ]);

        session()->flash('success', 'Result (' . $this->result_value . ') declared successfully.');
        $this->dispatchBrowserEvent('close-declare-result');
    }

    private function dateFormatToHis($date)
    {
        return Carbon::parse($date)->format('H:i:s');
    }

    public function changeCategory($cat_id){
        $this->category = Category::find($cat_id);
    }

    public function mount($category = null)
    {
        $this->categories = Category::where("enabled", true)->get();
        $this->category=$category;
        if(empty($category))
            $this->category = Category::where("enabled", true)->first();
        $this->user = auth()->user();
    }

    public function render()
    {
        $query = Bet::where("category_id", @$this->category->id)->whereDate('created_at', Carbon::today());
        
        $this->bets = (clone $query)->selectRaw('number, SUM(bet_amount) as total')->groupBy('number')->get()->pluck('total', 'number');

        $this->result = (clone $query)->selectRaw('result, SUM(bet_amount) as bet_amount, SUM(win_amount) as win_amount')->groupBy('result')->first();
        
        $this->start_time = $this->dateFormatToHis($this->category->start_time);
        $this->end_time = $this->dateFormatToHis($this->category->end_time);
        $this->current_time = $this->dateFormatToHis(Carbon::now());
        $start_time_diff = Carbon::now()->diff($this->category->start_time);
        $this->start_timer = $start_time_diff->h .':'. $start_time_diff->i .':'. $start_time_diff->s;
        $end_time_diff = Carbon::now()->diff($this->category->end_time);
        $this->stop_timer = $end_time_diff->h .':'. $end_time_diff->i .':'. $end_time_diff->s;
        
        if ($this->current_time < $this->category->start_time) 
            $this->bet_status = BetStatus::NOT_STARTED;
        else if ($this->current_time > $this->category->start_time && $this->current_time < $this->category->end_time)  
            $this->bet_status = BetStatus::STARTED;
        else 
            $this->bet_status = BetStatus::EXPIRED;    
        
        return view('livewire.backend.bets');
    }
}
