<?php

namespace App\Livewire\Backend\Accounts\Expense;

use App\Models\Expense;
use App\Models\ExpenseHead;
use App\Models\PaymentMethod;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Index extends Component
{
    use WithPagination, WithoutUrlPagination, WithFileUploads;

    public $expense_head_id, $amount, $date, $payment_method_id, $note, $expenseId;
    public $invoice_number, $attachment, $existing_attachment;
    public $search = '';
    public $sortField = 'date';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

    protected function rules()
    {
        return [
            'expense_head_id'   => 'required|exists:expense_heads,id',
            'amount'            => 'required|numeric|min:0',
            'date'              => 'required|date',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'note'              => 'nullable|string|max:255',
            'invoice_number'    => 'nullable|string|max:50',
            'attachment'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function create()
    {
        $this->resetForm();
    }

    public function save()
    {
        $data = $this->validate();
        $data['created_by'] = Auth::id();

        // Handle attachment upload
        if ($this->attachment) {
            $data['attachment'] = $this->attachment->store('attachments/expenses', 'public');
        } elseif ($this->expenseId && $this->existing_attachment) {
            $data['attachment'] = $this->existing_attachment;
        }

        if ($this->expenseId) {
            Expense::findOrFail($this->expenseId)->update($data);
            $message = 'Expense updated successfully.';
        } else {
            Expense::create($data);
            $message = 'Expense created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->dispatch('close-modal');
        $this->resetForm();
    }

    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        $this->expenseId = $expense->id;
        $this->expense_head_id = $expense->expense_head_id;
        $this->amount = $expense->amount;
        $this->date = $expense->date;
        $this->payment_method_id = $expense->payment_method_id;
        $this->note = $expense->note;
        $this->invoice_number = $expense->invoice_number;
        $this->existing_attachment = $expense->attachment;
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
        $this->dispatch('open-delete-modal');
    }

    public function deleteConfirmed()
    {
        $expense = Expense::findOrFail($this->confirmingDeleteId);

        if ($expense->attachment && Storage::disk('public')->exists($expense->attachment)) {
            Storage::disk('public')->delete($expense->attachment);
        }

        $expense->delete();

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Expense deleted successfully.']);
        $this->dispatch('close-delete-modal');
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset([
            'expense_head_id',
            'amount',
            'date',
            'payment_method_id',
            'note',
            'invoice_number',
            'attachment',
            'existing_attachment',
            'expenseId'
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $expenses = Expense::with(['head', 'paymentMethod', 'user'])
            ->where(function ($q) {
                $q->whereHas('head', fn($q2) => $q2->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhere('note', 'like', '%' . $this->search . '%')
                    ->orWhere('invoice_number', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.accounts.expense.index', [
            'expenses' => $expenses,
            'heads' => ExpenseHead::all(),
            'paymentMethods' => PaymentMethod::all(),
        ]);
    }
}
