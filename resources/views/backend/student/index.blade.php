@extends('backend.layouts.app')

@section('title', 'Class')

@section('content')
<div class="container-fluid">
    <!-- student import link -->
    <div class="mb-3">
        <a href="{{ route('admin.student.import') }}" wire:navigate class="btn btn-primary">Import Students</a>
    </div>
    
    <livewire:backend.student.index />
</div>
@endsection