@extends('backend.layouts.app')

@section('title', 'Mark configuration')

@section('content')

<div class="container-fluid">
    <!-- button for create page -->
    <div class="d-flex justify-content-end align-items-center mb-3">

        <a href="{{ route('admin.final-mark-configuration.create') }}" wire:navigate class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New
        </a>
    </div>

    <livewire:backend.final-mark-configuration.index />
</div>
@endsection