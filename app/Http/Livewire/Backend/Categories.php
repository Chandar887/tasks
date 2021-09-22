<?php

namespace App\Http\Livewire\Backend;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Categories extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'category-new' => 'new',
        'category-save' => 'save',
        'category-edit' => 'edit',
        'category-update' => 'update',
        'category-delete' => 'delete',
    ];

    protected $rules = [
        'category.name' => 'required|min:2',
        'category.start_time' => 'required|date_format:H:i',
        'category.end_time' => 'required|date_format:H:i',
        'category.min_bet' => 'required|numeric',
        'category.max_bet' => 'required|numeric',
        'category.enabled' => 'required|boolean',
    ];

    public $cat_limit = 10;
    public Category $category;
    public $user;

    public function new()
    {
        $this->category = new Category();
        $this->category->enabled = true;
    }

    public function save()
    {
        $this->validate();
        $this->category->save();
        session()->flash('success', 'New category added successfully.');
        $this->dispatchBrowserEvent('close-category-form');
    }

    public function edit($id)
    {
        $cat = Category::find($id);
        $this->category = $cat;
        $this->dispatchBrowserEvent('open-category-form');
    }

    public function update()
    {
        $this->validate();
        $this->category->update();
        session()->flash('success', 'Category updated successfully.');
        $this->dispatchBrowserEvent('close-category-form');
    }

    public function delete($id)
    {
        $cat = Category::find($id);
        $cat->delete();
        session()->flash('success', 'Category deleted successfully.');
    }

    public function mount()
    {
        // $this->category = new Category();
        // $this->category->enabled = true;
        $this->user = auth()->user();
    }

    public function render()
    {
        return view(
            'livewire.backend.categories',
            [
                'categories' => Category::latest()->paginate($this->cat_limit)
            ]
        );
    }
}
