@extends('backend.layouts.app')

@section('title', 'Member List')

@section('content')
<div class="container-fluid">

    <div class="mb-3">
        <a href="{{ route('admin.library.book-issue.create') }}" wire:navigate class="btn btn-primary">Issue Book</a>
    </div>

    <livewire:backend.library.book-issue.index />
</div>
@endsection