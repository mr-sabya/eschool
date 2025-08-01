@extends('backend.layouts.app')

@section('title', 'Subject Mark Distribution')

@section('content')

<div class="container-fluid">
    <!-- button for create page -->
    <div class="d-flex justify-content-end align-items-center mb-3">

        <a href="{{ route('admin.subject-mark-distribution.create') }}" wire:navigate class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New
        </a>
    </div>

    <livewire:backend.subject-mark-distribution.index />
</div>
@endsection