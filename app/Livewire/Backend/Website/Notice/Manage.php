<?php

namespace App\Livewire\Backend\Website\Notice;

use App\Models\Notice;
use Livewire\Component;
use Livewire\WithFileUploads;

class Manage extends Component
{
    use WithFileUploads;

    public $noticeId, $title, $description, $notice_type = 'general', $start_date, $end_date, $is_active = true;
    public $attachment, $new_attachment;

    protected function rules()
    {
        $id = $this->noticeId ?? 'NULL';
        return [
            'title' => 'required|unique:notices,title,' . $id,
            'description' => 'nullable|string',
            'notice_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'new_attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
        ];
    }

    public function mount($noticeId = null)
    {
        if ($noticeId) {
            $notice = Notice::findOrFail($noticeId);
            $this->noticeId = $notice->id;
            $this->title = $notice->title;
            $this->description = $notice->description;
            $this->notice_type = $notice->notice_type;
            $this->start_date = $notice->start_date->format('Y-m-d');
            $this->end_date = $notice->end_date?->format('Y-m-d');
            $this->is_active = $notice->is_active;
            $this->attachment = $notice->attachment;
        }
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->new_attachment) {
            $data['attachment'] = $this->new_attachment->store('notices', 'public');
        } else {
            unset($data['attachment']);
        }

        if ($this->noticeId) {
            $notice = Notice::findOrFail($this->noticeId);
            $notice->update($data);
            $message = 'Notice updated successfully.';
        } else {
            Notice::create($data);
            $message = 'Notice created successfully.';
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => $message]);
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'noticeId',
            'title',
            'description',
            'notice_type',
            'start_date',
            'end_date',
            'is_active',
            'attachment',
            'new_attachment'
        ]);
    }


    public function render()
    {
        return view('livewire.backend.website.notice.manage');
    }
}
