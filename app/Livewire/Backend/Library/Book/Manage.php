<?php

namespace App\Livewire\Backend\Library\Book;

use App\Models\Book;
use App\Models\BookCategory;
use Livewire\Component;
use Livewire\WithFileUploads;

class Manage extends Component
{
    use WithFileUploads;

    public $bookId;
    public $title;
    public $author;
    public $isbn;
    public $book_category_id;
    public $publisher;
    public $published_year;
    public $quantity;
    public $available_quantity;
    public $shelf_location;
    public $cover_image;      // existing cover
    public $new_cover_image;  // newly uploaded cover

    // Mount method to load book if ID is provided
    public function mount($bookId = null)
    {
        if ($bookId) {
            $this->bookId = $bookId;
            $this->loadBook();
        }
    }

    protected function loadBook()
    {
        $book = Book::findOrFail($this->bookId);

        $this->title = $book->title;
        $this->author = $book->author;
        $this->isbn = $book->isbn;
        $this->book_category_id = $book->book_category_id;
        $this->publisher = $book->publisher;
        $this->published_year = $book->published_year;
        $this->quantity = $book->quantity;
        $this->available_quantity = $book->available_quantity;
        $this->shelf_location = $book->shelf_location;
        $this->cover_image = $book->cover_image;
        $this->new_cover_image = null;
    }

    public function rules()
    {
        $id = $this->bookId ?? 'NULL';
        return [
            'title' => 'required|string|max:191|unique:books,title,' . $id,
            'author' => 'required|string|max:191',
            'isbn' => 'nullable|string|max:50',
            'book_category_id' => 'required|exists:book_categories,id',
            'publisher' => 'nullable|string|max:191',
            'published_year' => 'nullable|digits:4|integer',
            'quantity' => 'required|integer|min:0',
            'available_quantity' => 'required|integer|min:0',
            'shelf_location' => 'nullable|string|max:100',
            'new_cover_image' => 'nullable|image|max:2048',
        ];
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'author' => $this->author,
            'isbn' => $this->isbn,
            'book_category_id' => $this->book_category_id,
            'publisher' => $this->publisher,
            'published_year' => $this->published_year,
            'quantity' => $this->quantity,
            'available_quantity' => $this->available_quantity,
            'shelf_location' => $this->shelf_location,
        ];

        if ($this->new_cover_image) {
            $data['cover_image'] = $this->new_cover_image->store('books', 'public');
        }

        if ($this->bookId) {
            Book::findOrFail($this->bookId)->update($data);
            $message = 'Book updated successfully.';
        } else {
            Book::create($data);
            $message = 'Book created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'bookId','title','author','isbn','book_category_id','publisher',
            'published_year','quantity','available_quantity','shelf_location',
            'cover_image','new_cover_image'
        ]);
        $this->resetValidation();
    }

    public function render()
    {
        $categories = BookCategory::orderBy('name')->get();
        return view('livewire.backend.library.book.manage', compact('categories'));
    }

}
