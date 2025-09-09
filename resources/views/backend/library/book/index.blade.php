@extends('backend.layouts.app')

@section('title', 'Book List')

@section('content')
<div class="container-fluid">

    <div class="mb-3">
        <a href="{{ route('admin.library.book.create') }}" wire:navigate class="btn btn-primary">Add New Book</a>
    </div>

    <livewire:backend.library.book.index />
</div>
@endsection