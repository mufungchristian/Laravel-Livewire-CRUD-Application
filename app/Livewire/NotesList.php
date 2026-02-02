<?php

namespace App\Livewire;

use App\Models\Note;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Rule;

class NotesList extends Component
{
    use WithPagination;
    #[Rule('required|min:3')]
    public $title;
    #[Rule('required|min:3')]
    public $description;
    public $search;
    public $editingID;
    public $editingTitle;
    public $editingDescription;

    public function create(){
    //validate the input field
    $validatedFields =$this->validate();
    //create the Note
    Note::create($validatedFields);
    //clear the inputs
    $this->reset();
    //send the flash message
    session()->flash("success","Note created successfully");
    }
    public function render()
    {
        $notes =Note::latest()->where('title','like',"%{$this->search}%")->paginate(3);
        return view('livewire.notes-list',['notes'=>$notes]);
    }
    public function delete($noteID){
      Note::findOrFail($noteID)->delete();
      session()->flash('success','Note Deleted successfully');
    }
    public function edit($noteID){
        $note =Note::findOrFail($noteID);
        $this->editingID=$noteID;
        $this->editingTitle=$note->title;
        $this->editingDescription=$note->description;
    }
    public function cancelEdit(){
        $this->reset('editingTitle','editingDescription','editingID');
    }
    public function update(){
       Note::findOrFail($this->editingID)->update([
        'title'=>$this->editingTitle,
        'description'=>$this->editingDescription
       ]);
       session()->flash('success','Updated successfully');
       $this->cancelEdit();
    }
}