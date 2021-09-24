<?php

namespace App\Http\Livewire\Backend;

use App\Models\Bet;
use App\Models\User;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
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
    public $items;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function mount($user_id)
    {
        $this->user = User::find($user_id);
    }

    public function render()
    {
        $query = Bet::query();
        if ($this->search) {
            $query->where(function (Builder $query) {
                $query->where('device_id', 'like', "%$this->search%")
                      ->orWhere('number', 'like', "%$this->search%")
                      ->orWhere('bet_amount', 'like', "%$this->search%")
                      ->orWhere('result', 'like', "%$this->search%")
                      ->orWhere('commission', 'like', "%$this->search%")
                      ->orWhere('win_amount', 'like', "%$this->search%");
            });
        }
        
        $this->categories = Category::all();
        $items = $query->latest()->paginate($this->detail_limit);
      
        return view('livewire.backend.user-details',
        [
            'items' => $items
        ]);
    }
}
