<?php

namespace App\Livewire\Backend\Library\BookIssue;

use Livewire\Component;
use App\Models\BookIssue;
use App\Models\Book;
use App\Models\LibraryMember;
use App\Models\User;

class Manage extends Component
{
    public $bookIssueId;
    public $book_id;
    public $member_search;
    public $member_id;
    public $issue_date;
    public $due_date;
    public $return_date;
    public $fine_amount;
    public $status = 'issued';

    public $books = [];
    public $members = [];
    public $users = [];

    protected function rules()
    {
        return [
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:library_members,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'return_date' => 'nullable|date|after_or_equal:issue_date',
            'fine_amount' => 'nullable|numeric',
            'status' => 'required|string',
        ];
    }

    public function mount($bookIssueId = null)
    {
        $this->bookIssueId = $bookIssueId;
        $this->books = Book::all();
        $this->users = User::all();

        if ($this->bookIssueId) {
            $issue = BookIssue::findOrFail($this->bookIssueId);
            $this->book_id = $issue->book_id;
            $this->member_id = $issue->member_id;
            $this->issue_date = $issue->issue_date;
            $this->due_date = $issue->due_date;
            $this->return_date = $issue->return_date;
            $this->fine_amount = $issue->fine_amount;
            $this->status = $issue->status;
        }
    }

    public function updatedMemberSearch($value)
    {
        $this->members = LibraryMember::where('member_id', 'like', "%{$value}%")->get();
    }

    public function selectMember($id)
    {
        $this->member_id = $id;
        $this->members = [];
        $this->member_search = LibraryMember::find($id)?->member_id;
    }

    public function save()
    {
        $data = $this->validate();
        
        if ($this->bookIssueId) {
            $data['issued_by'] = auth()->id();
            BookIssue::findOrFail($this->bookIssueId)->update($data);
            $message = "Book issue updated successfully.";
        } else {
            BookIssue::create($data);
            $message = "Book issued successfully.";
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
        $this->emitUp('refreshBookIssues');
    }

    public function resetForm()
    {
        $this->reset(['book_id', 'member_search', 'member_id', 'issue_date', 'due_date', 'return_date', 'fine_amount', 'status', 'members']);
    }

    public function render()
    {
        return view('livewire.backend.library.book-issue.manage');
    }
}
