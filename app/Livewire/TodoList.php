<?php

namespace App\Livewire;

use App\Models\Todo;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;

class TodoList extends Component
{
    use WithPagination;
   #[Rule("required|min:3|max:50")]
    public $name;
    public $search;
    public $editID;
    #[Rule("required|min:3|max:50")]
    public $editTodoName;
    public function create(){
        //validate the todo
        $validated= $this->validateOnly('name');
        //create the todo
        Todo::create($validated);
        //clear the inputs
        $this->reset('name');
        //send the flash message
        session()->flash("success","Todo created successfully");
        $this->resetPage();
    }
    public function edit($todoID){
      $this->editID=$todoID;
      $this->editTodoName=Todo::findOrFail($todoID)->name;
    }
    public function update(){
        $this->validateOnly('editTodoName');
        Todo::findOrFail($this->editID)->update(
            ['name'=>$this->editTodoName]
        );
        $this->cancelEdit();
    }
    public function delete($todoID){
        try {
            Todo::findOrFail($todoID)->delete();
        } catch (Exception $e) {
           session('')->flash('error','Failed to delete the Item');
           return;
        }
    }
    public function toggle($todoID){
        $todo=Todo::findOrFail($todoID);
        $todo->completed=!$todo->completed;
        $todo->save();
    }
    public function cancelEdit(){
        $this->reset('editTodoName','editID');
    }

    public function render()
    {
        //Get All Todos
        // $todos =Todo::latest()->get();
        // $todos =Todo::latest()->paginate(5);
        $todos =Todo::latest()->where('name','like',"%{$this->search}%")->paginate(5);
        return view('livewire.todo-list',['todos'=>$todos]);
    }
}