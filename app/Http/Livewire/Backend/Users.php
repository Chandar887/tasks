<?php

namespace App\Http\Livewire\Backend;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class Users extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'user-new' => 'new',
        'user-save' => 'save',
        'user-edit' => 'edit',
        'user-update' => 'update',
        'user-delete' => 'delete',
    ];

    protected $rules = [
        'user.name' => 'required|string|min:2|max:255',
        'user.username' => 'required|alpha_dash|min:4|max:255|unique:users,username',
        'user.email' => 'required|email|unique:users,email|max:255',
        'user.role' => 'required|string|min:2|max:255|in:admin,user',
        'user.enabled' => 'required|boolean',
        'password' => 'required|min:8|max:255',
        'user.commission' => 'required|numeric|max:100',
        'user.profit' => 'required|numeric|max:100',
    ];

    public $user_limit = 10;
    public $user;
    public $password;
    public $search;
    public $role;
    
    public function new()
    {
        $this->user = new User();
        $this->user->enabled = true;
        $this->password = null;
    }

    public function save()
    {
        $this->validate($this->rules);
        $this->user->password = Hash::make($this->password);
        $this->user->save();
        session()->flash('success', 'New user added successfully.');
        $this->dispatchBrowserEvent('close-user-form');
    }

    public function edit($id)
    {
        $user = User::find($id);
        $this->user = $user;
        $this->dispatchBrowserEvent('open-user-form');
    }

    public function update()
    {
        $this->rules['user.username'] = 'required|alpha_dash|min:4|max:255|unique:users,username,' . $this->user->id;
        $this->rules['user.email'] = 'required|email|max:255|unique:users,email,' . $this->user->id;
        $this->rules['password'] = 'nullable|min:8|max:255';
        $this->validate($this->rules);
        if ($this->password) {
            $this->user->password = Hash::make($this->password);
        }
        $this->user->update();
        session()->flash('success', 'User updated successfully.');
        $this->dispatchBrowserEvent('close-user-form');
    }

    public function delete($id)
    {
        $cat = User::find($id);
        $cat->delete();
        session()->flash('success', 'user deleted successfully.');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRole()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->user = auth()->user();
    }

    public function render()
    {
        $query = User::query();
        if ($this->search) {
            $query->where(function (Builder $query) {
                $query->where('name', 'like', "%$this->search%")
                      ->orWhere('email', 'like', "%$this->search%")
                      ->orWhere('username', 'like', "%$this->search%")
                      ->orWhere('commission', 'like', "%$this->search%")
                      ->orWhere('profit', 'like', "%$this->search%");
            });
        }

        if ($this->role) {
            $query->where('role', $this->role);
        }
        
        $users = $query->latest()->paginate($this->user_limit);
        
        return view('livewire.backend.users',
            [
                'users' => $users
            ]
        );
    }
}
