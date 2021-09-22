<?php

namespace App\Http\Livewire\Backend;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

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

    public function mount()
    {
        $this->user = auth()->user();
    }

    public function render()
    {
        return view(
            'livewire.backend.users',
            [
                'users' => User::where('role', '!=', 'sadmin')->latest()->paginate($this->user_limit)
            ]
        );
    }
}
