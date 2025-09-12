<?php

namespace App\Livewire\Backend\Accounts\Income;

use App\Models\Income;
use App\Models\IncomeHead;
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

    public $income_head_id, $amount, $date, $payment_method_id, $note, $incomeId;
    public $invoice_number, $attachment, $existing_attachment;
    public $search = '';
    public $sortField = 'date';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $confirmingDeleteId = null;

    protected function rules()
    {
        return [
            'income_head_id'   => 'required|exists:income_heads,id',
            'amount'           => 'required|numeric|min:0',
            'date'             => 'required|date',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'note'             => 'nullable|string|max:255',
            'invoice_number'   => 'nullable|string|max:50',
            'attachment'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
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

        // ✅ Handle file upload
        if ($this->attachment) {
            $data['attachment'] = $this->attachment->store('attachments/incomes', 'public');
        } elseif ($this->incomeId && $this->existing_attachment) {
            $data['attachment'] = $this->existing_attachment;
        }

        if ($this->incomeId) {
            Income::findOrFail($this->incomeId)->update($data);
            $message = 'Income updated successfully.';
        } else {
            Income::create($data);
            $message = 'Income created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->dispatch('close-modal');
        $this->resetForm();
    }

    public function edit($id)
    {
        $income = Income::findOrFail($id);
        $this->incomeId = $income->id;
        $this->income_head_id = $income->income_head_id;
        $this->amount = $income->amount;
        $this->date = $income->date;
        $this->payment_method_id = $income->payment_method_id;
        $this->note = $income->note;
        $this->invoice_number = $income->invoice_number;
        $this->existing_attachment = $income->attachment;
    }

    public function confirmDelete($id)
    {
        $this->resetForm();
        $this->confirmingDeleteId = $id;
        $this->dispatch('open-delete-modal');
    }

    public function deleteConfirmed()
    {
        $income = Income::findOrFail($this->confirmingDeleteId);

        if ($income->attachment && Storage::disk('public')->exists($income->attachment)) {
            Storage::disk('public')->delete($income->attachment);
        }

        $income->delete();

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Income deleted successfully.']);
        $this->dispatch('close-delete-modal');
        $this->confirmingDeleteId = null;
    }

    public function resetForm()
    {
        $this->reset(['income_head_id', 'amount', 'date', 'payment_method_id', 'note', 'invoice_number', 'attachment', 'existing_attachment', 'incomeId']);
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
        $incomes = Income::with(['head', 'paymentMethod', 'user'])
            ->where(function ($q) {
                $q->whereHas('head', fn($q2) => $q2->where('name', 'like', '%' . $this->search . '%'))
                    ->orWhere('note', 'like', '%' . $this->search . '%')
                    ->orWhere('invoice_number', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.accounts.income.index', [
            'incomes' => $incomes,
            'heads' => IncomeHead::all(),
            'paymentMethods' => PaymentMethod::all(),
        ]);
    }
}
